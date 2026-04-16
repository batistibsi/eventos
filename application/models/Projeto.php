<?php
class Projeto
{
	public static $erro;
	const MAX_NOME = 150;
	const MAX_RESPONSAVEL = 120;
	const MAX_PROJETOS_POR_USUARIO = 15;

	public static function statusOpcoes()
	{
		return array(
			0 => 'Rascunho',
			1 => 'Submetido',
			2 => 'Ajustar',
			3 => 'Validado',
			4 => 'Invalido'
		);
	}

	public static function statusLabel($status)
	{
		$status = (int) $status;
		$opcoes = self::statusOpcoes();
		return isset($opcoes[$status]) ? $opcoes[$status] : 'Desconhecido';
	}

	private static function tipoEvidenciaValido($tipo)
	{
		return in_array($tipo, array('qualitativa', 'itens', 'pessoas', 'parceiros'), true);
	}

	private static function podeEditarRegistro($registro, $permissao = null, $idUsuarioLogado = null)
	{
		if ((int) $permissao === 1) {
			return true;
		}

		if (!$registro || (int) $registro['id_usuario'] !== (int) $idUsuarioLogado) {
			self::$erro = 'Projeto nao encontrado.';
			return false;
		}

		if ((int) $registro['status_projeto'] !== 0) {
			self::$erro = 'Projetos submetidos nao podem mais ser editados ou removidos.';
			return false;
		}

		return true;
	}

	private static function uploadDir()
	{
		return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'projetos';
	}

	private static function normalizarArquivos($arquivos)
	{
		$normalizados = array();

		if (!isset($arquivos['name']) || !is_array($arquivos['name'])) {
			return $normalizados;
		}

		foreach ($arquivos['name'] as $indice => $nome) {
			if (($arquivos['error'][$indice] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
				continue;
			}

			$normalizados[] = array(
				'name' => $nome,
				'type' => $arquivos['type'][$indice] ?? null,
				'tmp_name' => $arquivos['tmp_name'][$indice] ?? null,
				'error' => $arquivos['error'][$indice] ?? UPLOAD_ERR_NO_FILE,
				'size' => $arquivos['size'][$indice] ?? 0
			);
		}

		return $normalizados;
	}

	private static function validarArquivoEvidencia($arquivo)
	{
		if (!$arquivo || !isset($arquivo['tmp_name']) || !is_uploaded_file($arquivo['tmp_name'])) {
			self::$erro = 'Arquivo de evidencia invalido.';
			return false;
		}

		if (($arquivo['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
			self::$erro = 'Falha ao enviar um dos arquivos de evidencia.';
			return false;
		}

		if (($arquivo['size'] ?? 0) > 100 * 1024 * 1024) {
			self::$erro = 'Cada arquivo de evidencia deve ter no maximo 100 MB.';
			return false;
		}

		$extensao = strtolower(pathinfo($arquivo['name'] ?? '', PATHINFO_EXTENSION));
		$permitidas = array('pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'mp4', 'mov', 'avi', 'wmv', 'mp3', 'wav', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'zip', 'rar');

		if (!in_array($extensao, $permitidas, true)) {
			self::$erro = 'Tipo de arquivo de evidencia nao permitido.';
			return false;
		}

		return $extensao;
	}

	private static function salvarArquivosEvidencia($idProjeto, $arquivos, $tipoEvidencia = 'qualitativa')
	{
		if (!self::tipoEvidenciaValido($tipoEvidencia)) {
			self::$erro = 'Tipo de evidencia invalido.';
			return false;
		}

		$arquivos = self::normalizarArquivos($arquivos);
		if (!count($arquivos)) {
			return array();
		}

		if (count($arquivos) > 5) {
			self::$erro = 'Envie no maximo 5 arquivos de evidencia por vez.';
			return false;
		}

		$diretorio = self::uploadDir();
		if (!is_dir($diretorio) && !mkdir($diretorio, 0775, true)) {
			self::$erro = 'Nao foi possivel preparar a pasta de upload dos projetos.';
			return false;
		}

		$db = Zend_Registry::get('db');
		$salvos = array();

		foreach ($arquivos as $arquivo) {
			$extensao = self::validarArquivoEvidencia($arquivo);
			if ($extensao === false) {
				foreach ($salvos as $salvo) {
					@unlink($diretorio . DIRECTORY_SEPARATOR . basename($salvo['caminho_arquivo']));
				}
				return false;
			}

			$hash = sha1(uniqid((string) $idProjeto, true) . '_' . ($arquivo['name'] ?? 'arquivo'));
			$nomeFisico = 'projeto_' . (int) $idProjeto . '_' . $hash . '.' . $extensao;
			$destino = $diretorio . DIRECTORY_SEPARATOR . $nomeFisico;

			if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
				foreach ($salvos as $salvo) {
					@unlink($diretorio . DIRECTORY_SEPARATOR . basename($salvo['caminho_arquivo']));
				}
				self::$erro = 'Nao foi possivel salvar um dos arquivos de evidencia.';
				return false;
			}

			$registro = array(
				'id_projeto' => (int) $idProjeto,
				'tipo_evidencia' => $tipoEvidencia,
				'nome_original' => substr((string) $arquivo['name'], 0, 255),
				'caminho_arquivo' => 'projetos/' . $nomeFisico,
				'tamanho_bytes' => (int) ($arquivo['size'] ?? 0),
				'tipo_mime' => mime_content_type($destino) ?: null
			);

			$db->insert('eventos_projeto_arquivo', $registro);
			$salvos[] = $registro;
		}

		return $salvos;
	}

	public static function listaArquivos($idProjeto, $tipoEvidencia = null)
	{
		$db = Zend_Registry::get('db');
		$where = ' where id_projeto = ' . (int) $idProjeto;
		if ($tipoEvidencia && self::tipoEvidenciaValido($tipoEvidencia)) {
			$where .= " and coalesce(tipo_evidencia, 'qualitativa') = " . $db->quote($tipoEvidencia);
		}
		return $db->fetchAll('select * from eventos_projeto_arquivo' . $where . ' order by id_projeto_arquivo asc');
	}

	public static function removerArquivo($idProjetoArquivo, $idUsuarioLogado = null, $permissao = null)
	{
		$db = Zend_Registry::get('db');
		$idProjetoArquivo = (int) $idProjetoArquivo;

		if (!$idProjetoArquivo) {
			self::$erro = 'Arquivo invalido.';
			return false;
		}

		$select = "select a.*, p.id_usuario, p.status_projeto
				from eventos_projeto_arquivo a
				inner join eventos_projeto p on p.id_projeto = a.id_projeto
				where a.id_projeto_arquivo = " . $idProjetoArquivo;

		$registro = $db->fetchRow($select);
		if (!$registro) {
			self::$erro = 'Arquivo nao encontrado.';
			return false;
		}

		if ((int) $permissao !== 1 && (int) $registro['id_usuario'] !== (int) $idUsuarioLogado) {
			self::$erro = 'Voce nao tem permissao para remover este arquivo.';
			return false;
		}
		if ((int) $permissao !== 1 && (int) $registro['status_projeto'] !== 0) {
			self::$erro = 'Projetos submetidos nao podem mais ser editados ou removidos.';
			return false;
		}

		$db->delete('eventos_projeto_arquivo', 'id_projeto_arquivo = ' . $idProjetoArquivo);

		$caminho = self::uploadDir() . DIRECTORY_SEPARATOR . basename((string) $registro['caminho_arquivo']);
		if (is_file($caminho)) {
			@unlink($caminho);
		}

		return true;
	}

	public static function buscaId($idProjeto, $idUsuarioLogado = null, $permissao = null)
	{
		$idProjeto = (int) $idProjeto;
		if (!$idProjeto) {
			self::$erro = 'Projeto invalido.';
			return false;
		}

		$db = Zend_Registry::get('db');

		$where = ' where p.ativo and p.id_projeto = ' . $idProjeto;
		if ((int) $permissao === 2 && $idUsuarioLogado) {
			$where .= ' and exists (
				select 1
				from eventos_inscricao i
				where i.id_usuario = p.id_usuario
					and i.id_evento = p.id_evento
					and i.id_auditor = ' . (int) $idUsuarioLogado . '
			)';
		} elseif ((int) $permissao !== 1 && $idUsuarioLogado) {
			$where .= ' and p.id_usuario = ' . (int) $idUsuarioLogado;
		}

		$select = "select p.*,
					u.nome as nome_usuario,
					(
						select i.nome_organizacao
						from eventos_inscricao i
						where i.id_usuario = p.id_usuario
						order by i.id_inscricao desc
						limit 1
					) as nome_organizacao,
					e.titulo as titulo_evento,
					e.data_hora
				from eventos_projeto p
				inner join eventos_usuario u on u.id_usuario = p.id_usuario
				inner join eventos_evento e on e.id_evento = p.id_evento" . $where;

		$registros = $db->fetchAll($select);

		if (count($registros) == 0) {
			self::$erro = 'Projeto nao encontrado.';
			return false;
		}

		$registro = $registros[0];
		$registro['status_projeto_label'] = self::statusLabel($registro['status_projeto']);
		$registro['arquivos'] = self::listaArquivos($registro['id_projeto'], 'qualitativa');
		$registro['arquivos_qualitativos'] = $registro['arquivos'];
		$registro['arquivos_itens'] = self::listaArquivos($registro['id_projeto'], 'itens');
		$registro['arquivos_pessoas'] = self::listaArquivos($registro['id_projeto'], 'pessoas');
		$registro['arquivos_parceiros'] = self::listaArquivos($registro['id_projeto'], 'parceiros');
		return $registro;
	}

	public static function lista($idUsuarioLogado = null, $permissao = null, $idInscricao = null)
	{
		$db = Zend_Registry::get('db');

		$where = ' where p.ativo';
		if ($idInscricao) {
			$inscricao = Inscricao::buscaId((int) $idInscricao);
			if (!$inscricao || !is_array($inscricao)) {
				return array();
			}

			if ((int) $permissao === 2 && (int) ($inscricao['id_auditor'] ?? 0) !== (int) $idUsuarioLogado) {
				return array();
			}
			if ((int) $permissao === 3 && (int) ($inscricao['id_usuario'] ?? 0) !== (int) $idUsuarioLogado) {
				return array();
			}

			$where .= ' and p.id_usuario = ' . (int) ($inscricao['id_usuario'] ?? 0);
			$where .= ' and p.id_evento = ' . (int) ($inscricao['id_evento'] ?? 0);
		} elseif ((int) $permissao === 2) {
			return array();
		} elseif ((int) $permissao !== 1 && $idUsuarioLogado) {
			$where .= ' and p.id_usuario = ' . (int) $idUsuarioLogado;
		}

		$select = "select p.*,
					u.nome as nome_usuario,
					(
						select i.nome_organizacao
						from eventos_inscricao i
						where i.id_usuario = p.id_usuario
						order by i.id_inscricao desc
						limit 1
					) as nome_organizacao,
					e.titulo as titulo_evento,
					e.data_hora
				from eventos_projeto p
				inner join eventos_usuario u on u.id_usuario = p.id_usuario
				inner join eventos_evento e on e.id_evento = p.id_evento" . $where . "
				order by p.id_projeto desc";

		$registros = $db->fetchAll($select);

		foreach ($registros as $indice => $registro) {
			$registros[$indice]['status_projeto_label'] = self::statusLabel($registro['status_projeto']);
		}

		return $registros;
	}

	public static function todosListadosNoStatus($statusEsperado, $idUsuarioLogado = null, $permissao = null)
	{
		$registros = self::lista($idUsuarioLogado, $permissao);
		if (!count($registros)) {
			return false;
		}

		foreach ($registros as $registro) {
			if ((int) $registro['status_projeto'] !== (int) $statusEsperado) {
				return false;
			}
		}

		return true;
	}

	public static function existeListadoNoStatus($statusEsperado, $idUsuarioLogado = null, $permissao = null)
	{
		$registros = self::lista($idUsuarioLogado, $permissao);
		if (!count($registros)) {
			return false;
		}

		foreach ($registros as $registro) {
			if ((int) $registro['status_projeto'] === (int) $statusEsperado) {
				return true;
			}
		}

		return false;
	}

	public static function quantidadeProjetosUsuario($idUsuario)
	{
		$idUsuario = (int) $idUsuario;
		if (!$idUsuario) {
			return 0;
		}

		$db = Zend_Registry::get('db');
		return (int) $db->fetchOne('select count(*) from eventos_projeto where ativo and id_usuario = ' . $idUsuario);
	}

	public static function submeterListados($idUsuarioLogado = null, $permissao = null)
	{
		if ((int) $permissao === 1) {
			self::$erro = 'A submissao em lote nao se aplica ao perfil administrador.';
			return false;
		}

		if (!self::todosListadosNoStatus(0, $idUsuarioLogado, $permissao)) {
			self::$erro = 'Somente projetos em rascunho podem ser submetidos em lote.';
			return false;
		}

		$db = Zend_Registry::get('db');
		$where = 'status_projeto = 0';

		if ((int) $permissao !== 1 && $idUsuarioLogado) {
			$where .= ' AND id_usuario = ' . (int) $idUsuarioLogado;
		}

		$db->beginTransaction();

		try {
			$linhas = $db->update('eventos_projeto', array('status_projeto' => 1), $where);
			if (!$linhas) {
				self::$erro = 'Nenhum projeto foi submetido.';
				$db->rollBack();
				return false;
			}

			$whereInscricao = 'id_inscricao = (
				select id_inscricao
				from eventos_inscricao
				where id_usuario = ' . (int) $idUsuarioLogado . '
				order by id_inscricao desc
				limit 1
			)';
			$db->update('eventos_inscricao', array('id_status_auditoria' => 1), $whereInscricao);

			$db->commit();
		} catch (Exception $e) {
			if ($db->getConnection()->inTransaction()) {
				$db->rollBack();
			}
			self::$erro = 'Nao foi possivel submeter os projetos.';
			return false;
		}

		return true;
	}

	public static function prepararCampos($campos, $permissao = null, $idUsuarioLogado = null, $anoReferencia = null)
	{
		$idUsuario = isset($campos['id_usuario']) ? (int) $campos['id_usuario'] : 0;
		if ((int) $permissao !== 1) {
			$idUsuario = (int) $idUsuarioLogado;
		}

		if (!$idUsuario || !Usuario::buscaId($idUsuario)) {
			self::$erro = 'Usuario invalido.';
			return false;
		}

		$eventoVinculado = Inscricao::buscaEventoVinculadoUsuario($idUsuario);
		if (!$eventoVinculado || empty($eventoVinculado['id_evento'])) {
			self::$erro = 'O usuario precisa ter uma inscricao vinculada para definir o evento do projeto.';
			return false;
		}
		$idEvento = (int) $eventoVinculado['id_evento'];

		$statusProjeto = isset($campos['status_projeto']) && $campos['status_projeto'] !== '' ? (int) $campos['status_projeto'] : 0;
		if ((int) $permissao !== 1) {
			$statusProjeto = 0;
		}
		if (!array_key_exists($statusProjeto, self::statusOpcoes())) {
			self::$erro = 'Status do projeto invalido.';
			return false;
		}

		$nome = isset($campos['nome']) ? trim((string) $campos['nome']) : '';
		if ($nome === '') {
			self::$erro = 'Informe o nome do projeto.';
			return false;
		}
		if (strlen($nome) > self::MAX_NOME) {
			self::$erro = 'O nome do projeto deve ter no maximo ' . self::MAX_NOME . ' caracteres.';
			return false;
		}

		$responsavel = isset($campos['responsavel']) ? trim((string) $campos['responsavel']) : '';
		if (strlen($responsavel) > self::MAX_RESPONSAVEL) {
			self::$erro = 'O nome do responsavel deve ter no maximo ' . self::MAX_RESPONSAVEL . ' caracteres.';
			return false;
		}

		$dataInicializacao = Evento::normalizarData(isset($campos['data_inicializacao']) ? $campos['data_inicializacao'] : null);
		if (!$dataInicializacao) {
			self::$erro = 'Informe a data de inicializacao do projeto.';
			return false;
		}

		$dataFinalizacao = Evento::normalizarData(isset($campos['data_finalizacao']) ? $campos['data_finalizacao'] : null);
		if (!$dataFinalizacao) {
			self::$erro = 'Informe a data de finalizacao do projeto.';
			return false;
		}

		$anoReferencia = $anoReferencia ? (int) $anoReferencia : (int) date('Y');
		$anoPassado = $anoReferencia - 1;
		$anoRetrasado = $anoReferencia - 2;
		$anosPermitidos = array($anoRetrasado, $anoPassado);
		$anoDataInicializacao = (int) date('Y', strtotime($dataInicializacao));
		$anoDataFinalizacao = (int) date('Y', strtotime($dataFinalizacao));
		if (!in_array($anoDataInicializacao, $anosPermitidos, true)) {
			self::$erro = 'A data de inicializacao deve estar entre os anos de ' . $anoRetrasado . ' e ' . $anoPassado . '.';
			return false;
		}
		if (!in_array($anoDataFinalizacao, $anosPermitidos, true)) {
			self::$erro = 'A data de finalizacao deve estar entre os anos de ' . $anoRetrasado . ' e ' . $anoPassado . '.';
			return false;
		}

		$justificativa = isset($campos['justificativa']) ? trim((string) $campos['justificativa']) : '';
		if ($justificativa === '') {
			self::$erro = 'Informe a justificativa do projeto.';
			return false;
		}

		$areaAtuacao = isset($campos['area_atuacao']) ? trim((string) $campos['area_atuacao']) : '';
		if (!in_array($areaAtuacao, array('Ambiental', 'Social', 'Governanca'), true)) {
			self::$erro = 'Informe a area de atuacao do projeto.';
			return false;
		}

		$objetivos = isset($campos['objetivos']) ? trim((string) $campos['objetivos']) : '';
		if ($objetivos === '') {
			self::$erro = 'Informe os objetivos do projeto.';
			return false;
		}

		$odsPrincipal = isset($campos['ods_principal']) ? trim((string) $campos['ods_principal']) : '';
		if (!preg_match('/^ODS ([1-9]|1[0-7])$/', $odsPrincipal)) {
			self::$erro = 'Informe o ODS principal do projeto.';
			return false;
		}

		$demaisOds = isset($campos['demais_ods_relacionados']) ? $campos['demais_ods_relacionados'] : array();
		if (!is_array($demaisOds)) {
			$demaisOds = array();
		}
		$demaisOdsValidos = array();
		foreach ($demaisOds as $ods) {
			$ods = trim((string) $ods);
			if (preg_match('/^ODS ([1-9]|1[0-7])$/', $ods)) {
				$demaisOdsValidos[] = $ods;
			}
		}
		$demaisOdsValidos = array_values(array_unique($demaisOdsValidos));
		if (in_array($odsPrincipal, $demaisOdsValidos, true)) {
			self::$erro = 'O ODS principal nao pode ser selecionado entre os ODS relacionados.';
			return false;
		}

		$tipoItem = isset($campos['tipo_item']) ? trim((string) $campos['tipo_item']) : '';
		if (strlen($tipoItem) > 150) {
			self::$erro = 'O tipo de item deve ter no maximo 150 caracteres.';
			return false;
		}

		$quantidadeItens = null;
		if (isset($campos['quantidade_itens']) && $campos['quantidade_itens'] !== '' && $campos['quantidade_itens'] !== null) {
			if (!is_numeric($campos['quantidade_itens'])) {
				self::$erro = 'Informe uma quantidade de itens valida.';
				return false;
			}
			$quantidadeItens = (float) $campos['quantidade_itens'];
			if ($quantidadeItens < 0) {
				self::$erro = 'A quantidade de itens nao pode ser negativa.';
				return false;
			}
		}

		$unidadeMedida = isset($campos['unidade_medida']) ? trim((string) $campos['unidade_medida']) : '';
		$unidadeMedidaOutro = isset($campos['unidade_medida_outro']) ? trim((string) $campos['unidade_medida_outro']) : '';
		if ($unidadeMedida === 'Outro') {
			$unidadeMedida = $unidadeMedidaOutro;
		}
		if (strlen($unidadeMedida) > 80) {
			self::$erro = 'A unidade de medida deve ter no maximo 80 caracteres.';
			return false;
		}

		$publicoTipo = isset($campos['publico_tipo']) ? $campos['publico_tipo'] : array();
		if (!is_array($publicoTipo)) {
			$publicoTipo = array();
		}
		$publicoTipoValido = array();
		foreach ($publicoTipo as $tipo) {
			$tipo = trim((string) $tipo);
			if (in_array($tipo, array('Interno', 'Externo'), true)) {
				$publicoTipoValido[] = $tipo;
			}
		}
		$publicoTipoValido = array_values(array_unique($publicoTipoValido));

		$quantidadePublicoInterno = null;
		if (isset($campos['quantidade_publico_interno']) && $campos['quantidade_publico_interno'] !== '' && $campos['quantidade_publico_interno'] !== null) {
			if (!is_numeric($campos['quantidade_publico_interno'])) {
				self::$erro = 'Informe uma quantidade valida para o publico interno.';
				return false;
			}
			$quantidadePublicoInterno = (int) $campos['quantidade_publico_interno'];
			if ($quantidadePublicoInterno < 0) {
				self::$erro = 'A quantidade do publico interno nao pode ser negativa.';
				return false;
			}
		}

		$quantidadePublicoExterno = null;
		if (isset($campos['quantidade_publico_externo']) && $campos['quantidade_publico_externo'] !== '' && $campos['quantidade_publico_externo'] !== null) {
			if (!is_numeric($campos['quantidade_publico_externo'])) {
				self::$erro = 'Informe uma quantidade valida para o publico externo.';
				return false;
			}
			$quantidadePublicoExterno = (int) $campos['quantidade_publico_externo'];
			if ($quantidadePublicoExterno < 0) {
				self::$erro = 'A quantidade do publico externo nao pode ser negativa.';
				return false;
			}
		}

		$quantidadeParceiros = null;
		if (isset($campos['quantidade_parceiros']) && $campos['quantidade_parceiros'] !== '' && $campos['quantidade_parceiros'] !== null) {
			if (!is_numeric($campos['quantidade_parceiros'])) {
				self::$erro = 'Informe uma quantidade valida de parceiros.';
				return false;
			}
			$quantidadeParceiros = (int) $campos['quantidade_parceiros'];
			if ($quantidadeParceiros < 0) {
				self::$erro = 'A quantidade de parceiros nao pode ser negativa.';
				return false;
			}
		}

		$parceiros = isset($campos['parceiros']) ? trim((string) $campos['parceiros']) : '';
		if (strlen($parceiros) > 255) {
			self::$erro = 'O campo parceiros deve ter no maximo 255 caracteres.';
			return false;
		}

		return array(
			'id_usuario' => $idUsuario,
			'id_evento' => $idEvento,
			'status_projeto' => $statusProjeto,
			'ativo' => true,
			'nome' => $nome,
			'responsavel' => $responsavel !== '' ? $responsavel : null,
			'data_inicializacao' => $dataInicializacao,
			'data_finalizacao' => $dataFinalizacao,
			'justificativa' => $justificativa,
			'area_atuacao' => $areaAtuacao,
			'objetivos' => $objetivos,
			'ods_principal' => $odsPrincipal,
			'demais_ods_relacionados' => count($demaisOdsValidos) ? implode(', ', $demaisOdsValidos) : null,
			'tipo_item' => $tipoItem !== '' ? $tipoItem : null,
			'quantidade_itens' => $quantidadeItens,
			'unidade_medida' => $unidadeMedida !== '' ? $unidadeMedida : null,
			'publico_tipo' => count($publicoTipoValido) ? implode(', ', $publicoTipoValido) : null,
			'quantidade_publico_interno' => $quantidadePublicoInterno,
			'quantidade_publico_externo' => $quantidadePublicoExterno,
			'quantidade_parceiros' => $quantidadeParceiros,
			'parceiros' => $parceiros !== '' ? $parceiros : null,
			'updated_at' => new Zend_Db_Expr('now()')
		);
	}

	public static function insert($campos, $permissao = null, $idUsuarioLogado = null, $arquivos = null, $arquivosItens = null, $arquivosPessoas = null, $arquivosParceiros = null)
	{
		$data = self::prepararCampos($campos, $permissao, $idUsuarioLogado, (int) date('Y'));
		if ($data === false) {
			return false;
		}

		if ((int) $permissao !== 1) {
			$quantidadeProjetos = self::quantidadeProjetosUsuario($data['id_usuario']);
			if ($quantidadeProjetos >= self::MAX_PROJETOS_POR_USUARIO) {
				self::$erro = 'O usuario pode cadastrar no maximo ' . self::MAX_PROJETOS_POR_USUARIO . ' projetos.';
				return false;
			}
		}

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try {
			$data['created_at'] = new Zend_Db_Expr('now()');
			$db->insert('eventos_projeto', $data);
			$idProjeto = (int) $db->lastInsertId();
			if (!$idProjeto) {
				$registro = $db->fetchRow('select currval(pg_get_serial_sequence(\'eventos_projeto\', \'id_projeto\')) as id_projeto');
				$idProjeto = isset($registro['id_projeto']) ? (int) $registro['id_projeto'] : 0;
			}

			if (!$idProjeto) {
				throw new Exception('Projeto nao identificado apos salvar.');
			}

			if ($arquivos) {
				$resultadoArquivos = self::salvarArquivosEvidencia($idProjeto, $arquivos, 'qualitativa');
				if ($resultadoArquivos === false) {
					throw new Exception(self::$erro);
				}
			}

			if ($arquivosItens) {
				$resultadoArquivosItens = self::salvarArquivosEvidencia($idProjeto, $arquivosItens, 'itens');
				if ($resultadoArquivosItens === false) {
					throw new Exception(self::$erro);
				}
			}

			if ($arquivosPessoas) {
				$resultadoArquivosPessoas = self::salvarArquivosEvidencia($idProjeto, $arquivosPessoas, 'pessoas');
				if ($resultadoArquivosPessoas === false) {
					throw new Exception(self::$erro);
				}
			}

			if ($arquivosParceiros) {
				$resultadoArquivosParceiros = self::salvarArquivosEvidencia($idProjeto, $arquivosParceiros, 'parceiros');
				if ($resultadoArquivosParceiros === false) {
					throw new Exception(self::$erro);
				}
			}

			$db->commit();
		} catch (Exception $e) {
			if ($db->getConnection()->inTransaction()) {
				$db->rollBack();
			}
			if (!self::$erro) {
				self::$erro = 'Nao foi possivel salvar o projeto.';
			}
			return false;
		}

		return true;
	}

	public static function update($idProjeto, $campos, $permissao = null, $idUsuarioLogado = null, $arquivos = null, $arquivosItens = null, $arquivosPessoas = null, $arquivosParceiros = null)
	{
		$registro = self::buscaId($idProjeto, $idUsuarioLogado, $permissao);
		if (!$registro) {
			return false;
		}
		if (!self::podeEditarRegistro($registro, $permissao, $idUsuarioLogado)) {
			return false;
		}

		$anoReferencia = !empty($registro['created_at']) ? (int) date('Y', strtotime($registro['created_at'])) : (int) date('Y');
		$data = self::prepararCampos($campos, $permissao, $idUsuarioLogado, $anoReferencia);
		if ($data === false) {
			return false;
		}

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try {
			$db->update('eventos_projeto', $data, 'id_projeto = ' . (int) $registro['id_projeto']);

			if ($arquivos) {
				$arquivosAtuais = self::listaArquivos($registro['id_projeto'], 'qualitativa');
				$novosArquivos = self::normalizarArquivos($arquivos);
				if (count($arquivosAtuais) + count($novosArquivos) > 5) {
					self::$erro = 'O projeto pode ter no maximo 5 arquivos de evidencia.';
					throw new Exception(self::$erro);
				}

				if (count($novosArquivos)) {
					$resultadoArquivos = self::salvarArquivosEvidencia($registro['id_projeto'], $arquivos, 'qualitativa');
					if ($resultadoArquivos === false) {
						throw new Exception(self::$erro);
					}
				}
			}

			if ($arquivosItens) {
				$arquivosAtuaisItens = self::listaArquivos($registro['id_projeto'], 'itens');
				$novosArquivosItens = self::normalizarArquivos($arquivosItens);
				if (count($arquivosAtuaisItens) + count($novosArquivosItens) > 5) {
					self::$erro = 'A evidencia de itens pode ter no maximo 5 arquivos.';
					throw new Exception(self::$erro);
				}

				if (count($novosArquivosItens)) {
					$resultadoArquivosItens = self::salvarArquivosEvidencia($registro['id_projeto'], $arquivosItens, 'itens');
					if ($resultadoArquivosItens === false) {
						throw new Exception(self::$erro);
					}
				}
			}

			if ($arquivosPessoas) {
				$arquivosAtuaisPessoas = self::listaArquivos($registro['id_projeto'], 'pessoas');
				$novosArquivosPessoas = self::normalizarArquivos($arquivosPessoas);
				if (count($arquivosAtuaisPessoas) + count($novosArquivosPessoas) > 5) {
					self::$erro = 'A evidencia de pessoas pode ter no maximo 5 arquivos.';
					throw new Exception(self::$erro);
				}

				if (count($novosArquivosPessoas)) {
					$resultadoArquivosPessoas = self::salvarArquivosEvidencia($registro['id_projeto'], $arquivosPessoas, 'pessoas');
					if ($resultadoArquivosPessoas === false) {
						throw new Exception(self::$erro);
					}
				}
			}

			if ($arquivosParceiros) {
				$arquivosAtuaisParceiros = self::listaArquivos($registro['id_projeto'], 'parceiros');
				$novosArquivosParceiros = self::normalizarArquivos($arquivosParceiros);
				if (count($arquivosAtuaisParceiros) + count($novosArquivosParceiros) > 5) {
					self::$erro = 'A evidencia de parceiros pode ter no maximo 5 arquivos.';
					throw new Exception(self::$erro);
				}

				if (count($novosArquivosParceiros)) {
					$resultadoArquivosParceiros = self::salvarArquivosEvidencia($registro['id_projeto'], $arquivosParceiros, 'parceiros');
					if ($resultadoArquivosParceiros === false) {
						throw new Exception(self::$erro);
					}
				}
			}

			$db->commit();
		} catch (Exception $e) {
			if ($db->getConnection()->inTransaction()) {
				$db->rollBack();
			}
			if (!self::$erro) {
				self::$erro = 'Nao foi possivel atualizar o projeto.';
			}
			return false;
		}

		return true;
	}

	public static function delete($idProjeto, $idUsuarioLogado = null, $permissao = null)
	{
		$registro = self::buscaId($idProjeto, $idUsuarioLogado, $permissao);
		if (!$registro) {
			return false;
		}
		if (!self::podeEditarRegistro($registro, $permissao, $idUsuarioLogado)) {
			return false;
		}

		$db = Zend_Registry::get('db');
		$db->update('eventos_projeto', array('ativo' => false), 'id_projeto = ' . (int) $registro['id_projeto']);

		return true;
	}
}
