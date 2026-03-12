<?php
class DashboardController extends Zend_Controller_Action
{

	public function indexAction()
	{
		// Passando o usuário logado para a view
		$this->view->usuario = Zend_Registry::get('usuario');
		$this->view->idUsuario = Zend_Registry::get('id_usuario');
		$this->view->permissao = Zend_Registry::get('permissao');

		$inicio = !empty($_REQUEST['inicio']) ? $_REQUEST['inicio'] : 'first day of this month';
		$fim = !empty($_REQUEST['fim']) ? $_REQUEST['fim'] : 'last day of this month';

		$id_empresa_p = Zend_Registry::get('permissao') > 1 ? Zend_Registry::get('id_empresa') : (!empty($_REQUEST['id_empresa']) ? (int)$_REQUEST['id_empresa'] : 0);

		$dtInicio = new DateTime($inicio);
		$inicio = $dtInicio->format('Y-m-d');

		$dtFim = new DateTime($fim);
		$fim = $dtFim->format('Y-m-d');

		$ano = $dtFim->format('Y');

		$this->view->inicio = $inicio;
		$this->view->fim = $fim;

		$this->view->id_empresa = $id_empresa_p;

		$estatistica = Formulario::estatistica($inicio, $fim, $id_empresa_p);
		$this->view->estatistica = $estatistica;

		if (!$id_empresa_p) {
			$empresas = Empresa::lista();
			$clientes = [];
			$dados = [];
			if (count($empresas)) {
				foreach ($empresas as $id_empresa => $empresa) {
					$estatisticaEmpresa = Formulario::estatistica($inicio, $fim, $id_empresa);
					foreach ($estatisticaEmpresa['formularios'] as $formulario) {
						$dados[$formulario['id_formulario']][] = $formulario['quantidade'];
					}
					$clientes[] = $empresa['titulo'];
				}
			}
			$this->view->dados = $dados;
			$this->view->clientes = $clientes;
		} else {
			$empresas = [Empresa::buscaId($id_empresa_p)];
		}

		$this->view->empresas = $empresas;

		$dadosBanco = Formulario::ano($ano);
		$dadosAno = [];

		if ($id_empresa_p) {
			$dadosAno[$id_empresa_p] = [];
			for ($i = 0; $i < 12; $i++) {
				$dadosAno[$id_empresa_p][] = 0;
			}
		} else {
			if (count($empresas)) {
				foreach ($empresas as $id_empresa => $empresa) {
					$dadosAno[$id_empresa] = [];
					for ($i = 0; $i < 12; $i++) {
						$dadosAno[$id_empresa][] = 0;
					}
				}
			}
		}

		if (count($dadosBanco)) {
			foreach ($dadosBanco as $value) {
				if (!isset($dadosAno[$value['id_empresa']])) continue;
				$dadosAno[$value['id_empresa']][(int)($value['mes'] - 1)] = $value['quantidade'];
			}
		}

		$this->view->dadosAno = $dadosAno;
	}
}
