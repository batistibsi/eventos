<?php
if (Zend_Registry::get('permissao') != 1 && Zend_Registry::get('permissao') != 3) exit();

class DashboardController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->configDashboard = Config::dashboard();

		$eventoVinculado = Inscricao::buscaEventoVinculadoUsuario(Zend_Registry::get('id_usuario'));
		$this->view->inscricaoDashboard = Inscricao::buscaResumoVinculadoUsuario(Zend_Registry::get('id_usuario'));
		$this->view->eventoDashboard = $eventoVinculado && !empty($eventoVinculado['id_evento'])
			? Evento::buscaId((int) $eventoVinculado['id_evento'])
			: false;
	}

	public function materialAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->configMaterial = Config::material();
	}
}
