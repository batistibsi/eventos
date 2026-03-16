<?php
class InscricaoController extends Zend_Controller_Action
{

	public function detalheAction()
	{
		// Passando o usuário logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$id_inscricao = !empty($_REQUEST['id_inscricao']) ? (int)$_REQUEST['id_inscricao'] : null;
		
		if(!$id_inscricao) die('Inscrição não informada');

		$this->view->registro = Inscricao::buscaId($id_inscricao);
	}
}
