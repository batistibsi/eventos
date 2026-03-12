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

        public static function lista($ativos = false)
        {
                $db = Zend_Registry::get('db');

                $where = $ativos ? " where a.ativo and a.data_hora > '" . date('Y-m-d H:i:s') . "' " : "";
                $select = "select a.* 
                        from eventos_evento a " . $where;

                $registros = $db->fetchAll($select);

                $arrAux = [];

                if (count($registros)) {
                        foreach ($registros as $value) {
                                $value['label'] = (new DateTime($value['data_hora']))->format('d/m/Y H:i:s');
                                $arrAux[] = $value;
                        }
                }

                return $arrAux;
        }
}
