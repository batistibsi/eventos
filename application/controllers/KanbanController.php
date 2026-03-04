<?php

/*if(!isset($_GET['ajax'])){
	Util::gotoIndex();
}*/

class KanbanController extends Zend_Controller_Action
{

	public function indexAction()
	{

		// Passando o usuário logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$this->view->estagios = Tarefa::listaTipos(true);

		$this->view->registros = Tarefa::listar();

		//print_r($this->view->registros);die();
	}
}
