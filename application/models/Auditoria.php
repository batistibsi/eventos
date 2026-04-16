<?php
class Auditoria
{
	public static function listaStatusPorPerfil($idPerfil)
	{
		$db = Zend_Registry::get('db');
		$idPerfil = (int) $idPerfil;

		if (!$idPerfil) {
			return array();
		}

		$select = "select s.id_status_auditoria,
					s.nome,
					s.ordem,
					coalesce(s.cor, '#475569') as cor
				from eventos_status_auditoria s
				inner join eventos_auditoria_perfil p on p.id_status_auditoria = s.id_status_auditoria
				where p.id_perfil = " . $db->quote($idPerfil) . "
				order by s.ordem asc, s.nome asc";

		return $db->fetchAll($select);
	}

	public static function listaInscricoesPorPerfil($idPerfil, $idUsuario = null)
	{
		$db = Zend_Registry::get('db');
		$idPerfil = (int) $idPerfil;
		$idUsuario = (int) $idUsuario;

		if (!$idPerfil) {
			return array();
		}

		$whereAuditor = '';
		if ($idPerfil !== 1) {
			if (!$idUsuario) {
				return array();
			}

			$whereAuditor = ' and i.id_auditor = ' . $db->quote($idUsuario);
		}

		$select = "select i.id_inscricao,
					i.id_status_auditoria,
					i.nome_organizacao,
					i.nome,
					i.email,
					i.telefone,
					e.titulo as evento_titulo,
					e.data_hora as evento_data_hora
				from eventos_inscricao i
				inner join eventos_auditoria_perfil p on p.id_status_auditoria = i.id_status_auditoria
				left join eventos_evento e on e.id_evento = i.id_evento
				where i.id_status_auditoria is not null
					and p.id_perfil = " . $db->quote($idPerfil) . $whereAuditor . "
				order by i.id_status_auditoria asc, i.nome_organizacao asc, i.nome asc";

		return $db->fetchAll($select);
	}
}
