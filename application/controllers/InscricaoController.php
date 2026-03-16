<?php
class InscricaoController extends Zend_Controller_Action
{

	public function indexAction()
	{
		// Passando o usuÃ¡rio logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$inicio = !empty($_REQUEST['inicio']) ? $_REQUEST['inicio'] : 'first day of this month';
		$fim = !empty($_REQUEST['fim']) ? $_REQUEST['fim'] : 'last day of this month';

		$dtInicio = new DateTime($inicio);
		$inicio = $dtInicio->format('Y-m-d');

		$dtFim = new DateTime($fim);
		$fim = $dtFim->format('Y-m-d');

		$this->view->inicio = $inicio;
		$this->view->fim = $fim;

		$this->view->registros = Inscricao::consulta($inicio, $fim);
	}

	public function detalheAction()
	{
		// Passando o usuÃ¡rio logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$id_inscricao = !empty($_REQUEST['id_inscricao']) ? (int)$_REQUEST['id_inscricao'] : null;

		if (!$id_inscricao) die('InscriÃ§Ã£o nÃ£o informada');

		$this->view->registro = Inscricao::buscaId($id_inscricao);
		$this->view->statusDisponiveis = Inscricao::statusDisponiveis();
	}

	public function alterarstatusAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		$id_inscricao = !empty($_REQUEST['id_inscricao']) ? (int) $_REQUEST['id_inscricao'] : 0;
		$status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : null;

		$result = Inscricao::alterarStatus($id_inscricao, $status);

		if (!$result) echo Inscricao::$erro;
	}
}
