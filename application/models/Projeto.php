<?php
class Projeto
{
	public static $erro;

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

	private static function salvarArquivosEvidencia($idProjeto, $arquivos)
	{
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

	public static function listaArquivos($idProjeto)
	{
		$db = Zend_Registry::get('db');
		return $db->fetchAll('select * from eventos_projeto_arquivo where id_projeto = ' . (int) $idProjeto . ' order by id_projeto_arquivo asc');
	}

	public static function removerArquivo($idProjetoArquivo, $idUsuarioLogado = null, $permissao = null)
	{
		$db = Zend_Registry::get('db');
		$idProjetoArquivo = (int) $idProjetoArquivo;

		if (!$idProjetoArquivo) {
			self::$erro = 'Arquivo invalido.';
			return false;
		}

		$select = "select a.*, p.id_usuario
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

		$where = ' where p.id_projeto = ' . $idProjeto;
		if ((int) $permissao !== 1 && $idUsuarioLogado) {
			$where .= ' and p.id_usuario = ' . (int) $idUsuarioLogado;
		}

		$select = "select p.*,
					u.nome as nome_usuario,
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
		$registro['arquivos'] = self::listaArquivos($registro['id_projeto']);
		return $registro;
	}

	public static function lista($idUsuarioLogado = null, $permissao = null)
	{
		$db = Zend_Registry::get('db');

		$where = '';
		if ((int) $permissao !== 1 && $idUsuarioLogado) {
			$where = ' where p.id_usuario = ' . (int) $idUsuarioLogado;
		}

		$select = "select p.*,
					u.nome as nome_usuario,
					e.titulo as titulo_evento,
					e.data_hora
				from eventos_projeto p
				inner join eventos_usuario u on u.id_usuario = p.id_usuario
				inner join eventos_evento e on e.id_evento = p.id_evento" . $where . "
				order by p.id_projeto desc";

		return $db->fetchAll($select);
	}

	public static function prepararCampos($campos, $permissao = null, $idUsuarioLogado = null)
	{
		$idUsuario = isset($campos['id_usuario']) ? (int) $campos['id_usuario'] : 0;
		if ((int) $permissao !== 1) {
			$idUsuario = (int) $idUsuarioLogado;
		}

		if (!$idUsuario || !Usuario::buscaId($idUsuario)) {
			self::$erro = 'Usuario invalido.';
			return false;
		}

		$idEvento = isset($campos['id_evento']) ? (int) $campos['id_evento'] : 0;
		if (!$idEvento || !Evento::buscaId($idEvento)) {
			self::$erro = 'Evento invalido.';
			return false;
		}

		$statusProjeto = isset($campos['status_projeto']) && $campos['status_projeto'] !== '' ? (int) $campos['status_projeto'] : 0;
		if ($statusProjeto < 0) {
			self::$erro = 'Status do projeto invalido.';
			return false;
		}

		$nome = isset($campos['nome']) ? trim((string) $campos['nome']) : '';
		if ($nome === '') {
			self::$erro = 'Informe o nome do projeto.';
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

		return array(
			'id_usuario' => $idUsuario,
			'id_evento' => $idEvento,
			'status_projeto' => $statusProjeto,
			'nome' => $nome,
			'data_inicializacao' => $dataInicializacao,
			'data_finalizacao' => $dataFinalizacao,
			'justificativa' => $justificativa,
			'area_atuacao' => $areaAtuacao,
			'objetivos' => $objetivos,
			'ods_principal' => $odsPrincipal,
			'demais_ods_relacionados' => count($demaisOdsValidos) ? implode(', ', $demaisOdsValidos) : null
		);
	}

	public static function insert($campos, $permissao = null, $idUsuarioLogado = null, $arquivos = null)
	{
		$data = self::prepararCampos($campos, $permissao, $idUsuarioLogado);
		if ($data === false) {
			return false;
		}

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try {
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
				$resultadoArquivos = self::salvarArquivosEvidencia($idProjeto, $arquivos);
				if ($resultadoArquivos === false) {
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

	public static function update($idProjeto, $campos, $permissao = null, $idUsuarioLogado = null, $arquivos = null)
	{
		$registro = self::buscaId($idProjeto, $idUsuarioLogado, $permissao);
		if (!$registro) {
			return false;
		}

		$data = self::prepararCampos($campos, $permissao, $idUsuarioLogado);
		if ($data === false) {
			return false;
		}

		$db = Zend_Registry::get('db');
		$db->beginTransaction();

		try {
			$db->update('eventos_projeto', $data, 'id_projeto = ' . (int) $registro['id_projeto']);

			if ($arquivos) {
				$arquivosAtuais = self::listaArquivos($registro['id_projeto']);
				$novosArquivos = self::normalizarArquivos($arquivos);
				if (count($arquivosAtuais) + count($novosArquivos) > 5) {
					self::$erro = 'O projeto pode ter no maximo 5 arquivos de evidencia.';
					throw new Exception(self::$erro);
				}

				if (count($novosArquivos)) {
					$resultadoArquivos = self::salvarArquivosEvidencia($registro['id_projeto'], $arquivos);
					if ($resultadoArquivos === false) {
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

		$db = Zend_Registry::get('db');
		$db->delete('eventos_projeto', 'id_projeto = ' . (int) $registro['id_projeto']);

		return true;
	}
}
