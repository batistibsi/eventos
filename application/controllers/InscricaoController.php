<?php
//if (Zend_Registry::get('permissao') != 1) exit();

class InscricaoController extends Zend_Controller_Action
{
	public function indexAction()
	{
		if (Zend_Registry::get('permissao') != 1) exit();

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
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$id_inscricao = !empty($_REQUEST['id_inscricao']) ? (int) $_REQUEST['id_inscricao'] : 0;
		if (!$id_inscricao) {
			die('Inscricao nao informada');
		}

		$this->view->registro = Inscricao::buscaId($id_inscricao);
		if (!$this->view->registro || !is_array($this->view->registro)) {
			die('Inscricao nao encontrada');
		}

		if (Zend_Registry::get('permissao') != 1 && (int) ($this->view->registro['id_usuario'] ?? 0) !== (int) Zend_Registry::get('id_usuario')) {
			die('Nao permitido!');
		}

		$this->view->statusDisponiveis = Inscricao::statusDisponiveis();
	}

	public function alterarstatusAction()
	{
		$this->_helper->viewRenderer->setNoRender();

		if (Zend_Registry::get('permissao') != 1) die('Nao permitido!');

		$id_inscricao = !empty($_REQUEST['id_inscricao']) ? (int) $_REQUEST['id_inscricao'] : 0;
		$status = !empty($_REQUEST['status']) ? $_REQUEST['status'] : null;

		$result = Inscricao::alterarStatus($id_inscricao, $status);

		if (!$result) echo Inscricao::$erro;
	}
}
