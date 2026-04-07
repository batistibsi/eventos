<?php
if (Zend_Registry::get('permissao') != 1) exit();

class EventoController extends Zend_Controller_Action
{
        public function indexAction()
        {
                $this->view->usuario = Zend_Registry::get('usuario');
                $this->view->id_usuario = Zend_Registry::get('id_usuario');
                $this->view->permissao = Zend_Registry::get('permissao');
                $this->view->registros = Evento::lista();
        }

        public function cadastroAction()
        {
                $this->view->usuario = Zend_Registry::get('usuario');
                $this->view->id_usuario = Zend_Registry::get('id_usuario');
                $this->view->permissao = Zend_Registry::get('permissao');

                $idEvento = isset($_REQUEST['id_evento']) ? (int) $_REQUEST['id_evento'] : 0;
                $this->view->registro = $idEvento ? Evento::buscaId($idEvento) : false;
        }

        public function salvarAction()
        {
                $this->_helper->viewRenderer->setNoRender();

                $idEvento = isset($_REQUEST['id_evento']) ? (int) $_REQUEST['id_evento'] : 0;
                $campos = [];
                $campos['titulo'] = !empty($_REQUEST['titulo']) ? $_REQUEST['titulo'] : null;
                $campos['data_hora'] = !empty($_REQUEST['data_hora']) ? $_REQUEST['data_hora'] : null;
                $campos['limite_vagas'] = isset($_REQUEST['limite_vagas']) && $_REQUEST['limite_vagas'] !== '' ? (int) $_REQUEST['limite_vagas'] : null;
                $campos['data_hora_2'] = !empty($_REQUEST['data_hora_2']) ? $_REQUEST['data_hora_2'] : null;
                $campos['auditoria'] = !empty($_REQUEST['auditoria']) ? $_REQUEST['auditoria'] : null;
                $campos['data_inscricao'] = !empty($_REQUEST['data_inscricao']) ? $_REQUEST['data_inscricao'] : null;
                $campos['data_submissao'] = !empty($_REQUEST['data_submissao']) ? $_REQUEST['data_submissao'] : null;
                $campos['data_correcao'] = !empty($_REQUEST['data_correcao']) ? $_REQUEST['data_correcao'] : null;
                $campos['data_resultado'] = !empty($_REQUEST['data_resultado']) ? $_REQUEST['data_resultado'] : null;
                $campos['data_summit'] = !empty($_REQUEST['data_summit']) ? $_REQUEST['data_summit'] : null;
                $campos['observacao'] = !empty($_REQUEST['observacao']) ? $_REQUEST['observacao'] : null;

                if (!$idEvento) {
                        $result = Evento::insert($campos);
                } else {
                        $result = Evento::update($idEvento, $campos);
                }

                if (!$result) echo Evento::$erro;
        }

        public function removerAction()
        {
                $this->_helper->viewRenderer->setNoRender();

                $idEvento = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
                $result = Evento::desativar($idEvento);

                if (!$result) echo Evento::$erro;
        }
}
