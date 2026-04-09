<?php
if (Zend_Registry::get('permissao') != 1 && Zend_Registry::get('permissao') != 3) exit();

class ProjetoController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->id_usuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->registros = Projeto::lista(Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));
		$ehAdmin = Zend_Registry::get('permissao') == 1;
		$this->view->mostrarBotaoSubmeter = !$ehAdmin && Projeto::todosListadosNoStatus(0, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));
		$this->view->mostrarBotaoNovo = $ehAdmin || !Projeto::existeListadoNoStatus(1, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));
		$this->view->permitirEditarExcluir = $ehAdmin || $this->view->mostrarBotaoNovo;
	}

	public function cadastroAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->id_usuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$idProjeto = isset($_REQUEST['id_projeto']) ? (int) $_REQUEST['id_projeto'] : 0;

		$this->view->registro = $idProjeto ? Projeto::buscaId($idProjeto, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao')) : false;
		if ($idProjeto && !$this->view->registro) {
			die(Projeto::$erro);
		}
		$this->view->usuarios = Usuario::lista();
		$idUsuarioFormulario = $this->view->registro ? (int) $this->view->registro['id_usuario'] : (int) Zend_Registry::get('id_usuario');
		$this->view->eventoVinculado = $idUsuarioFormulario ? Inscricao::buscaEventoVinculadoUsuario($idUsuarioFormulario) : false;
		$this->view->empresaVinculada = $idUsuarioFormulario ? Inscricao::buscaResumoVinculadoUsuario($idUsuarioFormulario) : false;
		$this->view->statusProjetoOpcoes = Projeto::statusOpcoes();
		$this->view->eventosPorUsuario = array();
		$this->view->empresasPorUsuario = array();
		if (Zend_Registry::get('permissao') == 1) {
			foreach ($this->view->usuarios as $usuario) {
				$eventoUsuario = Inscricao::buscaEventoVinculadoUsuario((int) $usuario['id_usuario']);
				$empresaUsuario = Inscricao::buscaResumoVinculadoUsuario((int) $usuario['id_usuario']);
				$this->view->eventosPorUsuario[(int) $usuario['id_usuario']] = array(
					'id_evento' => $eventoUsuario ? (int) $eventoUsuario['id_evento'] : null,
					'label' => $eventoUsuario ? $eventoUsuario['label'] : 'Nenhuma inscricao vinculada encontrada para este usuario.'
				);
				$this->view->empresasPorUsuario[(int) $usuario['id_usuario']] = array(
					'nome_organizacao' => $empresaUsuario ? ($empresaUsuario['nome_organizacao'] ?? '') : '',
					'cnpj' => $empresaUsuario ? ($empresaUsuario['cnpj'] ?? '') : '',
					'nome_certificado' => $empresaUsuario ? ($empresaUsuario['nome_certificado'] ?? '') : '',
					'numero_colaboradores' => $empresaUsuario ? (int) ($empresaUsuario['numero_colaboradores'] ?? 0) : '',
					'nome' => $empresaUsuario ? ($empresaUsuario['nome'] ?? '') : '',
					'email' => $empresaUsuario ? ($empresaUsuario['email'] ?? '') : '',
					'telefone' => $empresaUsuario ? ($empresaUsuario['telefone'] ?? '') : '',
					'evento_label' => $empresaUsuario ? ($empresaUsuario['evento_label'] ?? '') : '',
					'vazio' => !$empresaUsuario
				);
			}
		}
	}

	public function detalhesAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->id_usuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$idProjeto = isset($_REQUEST['id_projeto']) ? (int) $_REQUEST['id_projeto'] : 0;
		$this->view->registro = Projeto::buscaId($idProjeto, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));
	}

	public function salvarAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		$idProjeto = isset($_REQUEST['id_projeto']) ? (int) $_REQUEST['id_projeto'] : 0;
		$campos = [];
		$campos['id_usuario'] = isset($_REQUEST['id_usuario']) ? (int) $_REQUEST['id_usuario'] : 0;
		$campos['id_evento'] = isset($_REQUEST['id_evento']) ? (int) $_REQUEST['id_evento'] : 0;
		$campos['status_projeto'] = isset($_REQUEST['status_projeto']) ? (int) $_REQUEST['status_projeto'] : 0;
		$campos['nome'] = !empty($_REQUEST['nome']) ? $_REQUEST['nome'] : null;
		$campos['responsavel'] = !empty($_REQUEST['responsavel']) ? $_REQUEST['responsavel'] : null;
		$campos['data_inicializacao'] = !empty($_REQUEST['data_inicializacao']) ? $_REQUEST['data_inicializacao'] : null;
		$campos['data_finalizacao'] = !empty($_REQUEST['data_finalizacao']) ? $_REQUEST['data_finalizacao'] : null;
		$campos['justificativa'] = !empty($_REQUEST['justificativa']) ? $_REQUEST['justificativa'] : null;
		$campos['area_atuacao'] = !empty($_REQUEST['area_atuacao']) ? $_REQUEST['area_atuacao'] : null;
		$campos['objetivos'] = !empty($_REQUEST['objetivos']) ? $_REQUEST['objetivos'] : null;
		$campos['ods_principal'] = !empty($_REQUEST['ods_principal']) ? $_REQUEST['ods_principal'] : null;
		$campos['demais_ods_relacionados'] = isset($_REQUEST['demais_ods_relacionados']) ? $_REQUEST['demais_ods_relacionados'] : array();

		if (!$idProjeto) {
			$result = Projeto::insert($campos, Zend_Registry::get('permissao'), Zend_Registry::get('id_usuario'), isset($_FILES['evidencias']) ? $_FILES['evidencias'] : null);
		} else {
			$result = Projeto::update($idProjeto, $campos, Zend_Registry::get('permissao'), Zend_Registry::get('id_usuario'), isset($_FILES['evidencias']) ? $_FILES['evidencias'] : null);
		}

		if (!$result) echo Projeto::$erro;
	}

	public function removerAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		$idProjeto = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
		$result = Projeto::delete($idProjeto, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));

		if (!$result) echo Projeto::$erro;
	}

	public function removerarquivoAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		$idProjetoArquivo = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
		$result = Projeto::removerArquivo($idProjetoArquivo, Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));

		if (!$result) echo Projeto::$erro;
	}

	public function submeterlistadosAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		if (Zend_Registry::get('permissao') == 1) {
			echo 'A submissao em lote nao se aplica ao perfil administrador.';
			return;
		}

		$result = Projeto::submeterListados(Zend_Registry::get('id_usuario'), Zend_Registry::get('permissao'));
		if (!$result) echo Projeto::$erro;
	}
}
