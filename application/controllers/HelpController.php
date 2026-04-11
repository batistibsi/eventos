<?php
//if (Zend_Registry::get('permissao') != 3) exit();

class HelpController extends Zend_Controller_Action
{
	public function indexAction()
	{
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->id_usuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
		$this->view->registro = Config::help();
	}
}
