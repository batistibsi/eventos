<?php
class StatusAuditoria
{
	public static $erro;
	const MAX_DESCRICAO = 500;

	public static function listar()
	{
		$db = Zend_Registry::get('db');

		$select = "select id_status_auditoria,
					nome,
					descricao,
					ordem,
					cor
				from eventos_status_auditoria
				order by ordem asc, id_status_auditoria asc";

		$registros = $db->fetchAll($select);
		return is_array($registros) ? $registros : array();
	}

	public static function buscaId($idStatusAuditoria)
	{
		$db = Zend_Registry::get('db');
		$idStatusAuditoria = (int) $idStatusAuditoria;

		if ($idStatusAuditoria <= 0) {
			return false;
		}

		$select = "select id_status_auditoria,
					nome,
					descricao,
					ordem,
					cor
				from eventos_status_auditoria
				where id_status_auditoria = " . $db->quote($idStatusAuditoria) . "
				limit 1";

		$registro = $db->fetchRow($select);
		return $registro ?: false;
	}

	public static function salvar($campos)
	{
		$db = Zend_Registry::get('db');
		$id = isset($campos['id_status_auditoria']) ? (int) $campos['id_status_auditoria'] : 0;
		$descricao = trim((string) ($campos['descricao'] ?? ''));

		if ($id <= 0) {
			self::$erro = 'Status de auditoria nao informado.';
			return false;
		}

		if (strlen($descricao) > self::MAX_DESCRICAO) {
			self::$erro = 'A descricao deve ter no maximo ' . self::MAX_DESCRICAO . ' caracteres.';
			return false;
		}

		if (!self::buscaId($id)) {
			self::$erro = 'Status de auditoria nao encontrado.';
			return false;
		}

		try {
			$where = $db->quoteInto('id_status_auditoria = ?', $id);
			$db->update('eventos_status_auditoria', array(
				'descricao' => $descricao !== '' ? $descricao : null
			), $where);

			return $id;
		} catch (Exception $e) {
			self::$erro = 'Nao foi possivel salvar a descricao do status.';
			return false;
		}
	}
}
