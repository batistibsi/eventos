<?php
class Evento
{
        public static $erro;

        public static function buscaId($id_evento)
        {

                $db = Zend_Registry::get('db');

                $select = "select * 
                        from eventos_evento 
                        where id_evento = " . $id_evento;

                $registros = $db->fetchAll($select);

                if (count($registros) == 0) {
                        self::$erro = "Evento não encontrado.";
                        return false;
                } else {
                        $registro = $registros[0];
                        return $registro;
                }
        }

        public static function confereVagas($id_evento)
        {
                $db = Zend_Registry::get('db');

                $evento = self::buscaId($id_evento);
                $limite_vagas =  $evento['limite_vagas'];

                $select = "select count(*) as inscritos 
                                from eventos_inscricao 
                                where id_evento = " . $id_evento . " 
                                and status not in ('CANCELADO','ENCERRADO');";

                $registros = $db->fetchAll($select);
                $inscritos = $registros[0]['inscritos'];

                return !($inscritos >= $limite_vagas);
        }
}
