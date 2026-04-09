<?php
if (Zend_Registry::get('permissao') != 1 && Zend_Registry::get('permissao') != 2) exit();

class CadastroController extends Zend_Controller_Action
{
	public function indexAction()
	{
		// Passando o usuário logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
	}

	public function dashboardAction()
	{
		if (Zend_Registry::get('permissao') != 1) exit();

		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->registro = Config::dashboard();
	}

	public function salvardashboardAction()
	{
		if (Zend_Registry::get('permissao') != 1) exit();

		$this->_helper->viewRenderer->setNoRender();

		$campos = array(
			'dashboard_titulo' => $_REQUEST['dashboard_titulo'] ?? null,
			'dashboard_carrossel_imagens' => $_REQUEST['dashboard_carrossel_imagens'] ?? null,
			'dashboard_aviso_texto' => $_REQUEST['dashboard_aviso_texto'] ?? null
		);

		if (!Config::salvarDashboard($campos, $_FILES['dashboard_carrossel_upload'] ?? null)) {
			echo Config::$erro;
		}
	}

	public function materialAction()
	{
		if (Zend_Registry::get('permissao') != 1) exit();

		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->registro = Config::material();
	}

	public function salvamaterialAction()
	{
		if (Zend_Registry::get('permissao') != 1) exit();

		$this->_helper->viewRenderer->setNoRender();

		$campos = array(
			'material_titulo' => $_REQUEST['material_titulo'] ?? null,
			'material_video_principal_titulo' => $_REQUEST['material_video_principal_titulo'] ?? null,
			'material_video_principal_link' => $_REQUEST['material_video_principal_link'] ?? null,
			'material_videos_secundarios' => $_REQUEST['material_videos_secundarios'] ?? null,
			'material_arquivos' => $_REQUEST['material_arquivos'] ?? null,
			'material_links_topo' => $_REQUEST['material_links_topo'] ?? null,
			'material_links_lista' => $_REQUEST['material_links_lista'] ?? null
		);

		if (!Config::salvarMaterial($campos)) {
			echo Config::$erro;
		}
	}
}
