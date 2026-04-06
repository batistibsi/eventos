<?php
if (Zend_Registry::get('permissao') != 1 && Zend_Registry::get('permissao') != 3) exit();
class ProjetoController extends Zend_Controller_Action
{
	public function indexAction()
	{
		// Passando o usuário logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');
	}
}
