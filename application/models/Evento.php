<?php
class Evento
{
        public static $erro;

        public static function normalizarDataHora($dataHora)
        {
                if ($dataHora === null || trim((string) $dataHora) === '') {
                        return null;
                }

                $dataHora = trim((string) $dataHora);
                $formatos = ['Y-m-d\TH:i', 'Y-m-d H:i', 'Y-m-d H:i:s', 'd/m/Y H:i', 'd/m/Y H:i:s'];

                foreach ($formatos as $formato) {
                        $data = DateTime::createFromFormat($formato, $dataHora);
                        if ($data instanceof DateTime) {
                                return $data->format('Y-m-d H:i:s');
                        }
                }

                try {
                        $data = new DateTime($dataHora);
                        return $data->format('Y-m-d H:i:s');
                } catch (Exception $e) {
                        return false;
                }
        }

        public static function getLabel($titulo, $data_hora)
        {
                return $titulo . ' - ' . (new DateTime($data_hora))->format('d/m/Y H:i:s');
        }

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
                        $registro['label'] = self::getLabel($registro['titulo'], $registro['data_hora']);
                        return $registro;
                }
        }

        public static function confereVagas($id_evento, $limite_vagas = false)
        {
                $db = Zend_Registry::get('db');

                if ($limite_vagas === false) {
                        $evento = self::buscaId($id_evento);
                        $limite_vagas =  $evento['limite_vagas'];
                }

                $select = "select count(*) as inscritos 
                                from eventos_inscricao 
                                where id_evento = " . $id_evento . " 
                                and status not in ('DUPLICADO','CANCELADO','ENCERRADO');";

                $registros = $db->fetchAll($select);
                $inscritos = $registros[0]['inscritos'];

                return !($inscritos >= $limite_vagas);
        }

        public static function lista($futuros = false)
        {
                $db = Zend_Registry::get('db');

                $where = " where a.ativo ";
                if ($futuros) {
                        $where .= " and a.data_hora > '" . date('Y-m-d H:i:s') . "' ";
                }
                $select = "select a.* 
                        from eventos_evento a " . $where . "
                        order by a.data_hora desc";

                $registros = $db->fetchAll($select);

                $arrAux = [];

                if (count($registros)) {
                        foreach ($registros as $value) {
                                $value['label'] = self::getLabel($value['titulo'], $value['data_hora']);
                                $arrAux[] = $value;
                        }
                }

                return $arrAux;
        }

        public static function prepararCampos($campos)
        {
                $titulo = isset($campos['titulo']) ? trim((string) $campos['titulo']) : '';

                if (strlen($titulo) < 1) {
                        self::$erro = 'Informe uma turma válida para o evento.';
                        return false;
                }

                $dataHora = self::normalizarDataHora(isset($campos['data_hora']) ? $campos['data_hora'] : null);
                if (!$dataHora) {
                        self::$erro = 'Informe o Treinamento D1 do evento.';
                        return false;
                }

                $dataHora2 = self::normalizarDataHora(isset($campos['data_hora_2']) ? $campos['data_hora_2'] : null);
                if ($dataHora2 === false) {
                        self::$erro = 'A segunda data/hora informada e invalida.';
                        return false;
                }

                $auditoria = self::normalizarDataHora(isset($campos['auditoria']) ? $campos['auditoria'] : null);
                if ($auditoria === false) {
                        self::$erro = 'A data/hora de auditoria informada e invalida.';
                        return false;
                }

                $limiteVagas = isset($campos['limite_vagas']) && $campos['limite_vagas'] !== '' ? (int) $campos['limite_vagas'] : null;
                if ($limiteVagas === null || $limiteVagas < 1) {
                        self::$erro = 'Informe um limite de vagas valido.';
                        return false;
                }

                return array(
                        'titulo' => $titulo,
                        'data_hora' => $dataHora,
                        'limite_vagas' => $limiteVagas,
                        'data_hora_2' => $dataHora2,
                        'auditoria' => $auditoria,
                        'observacao' => isset($campos['observacao']) && trim((string) $campos['observacao']) !== '' ? trim((string) $campos['observacao']) : null
                );
        }

        public static function insert($campos)
        {
                $data = self::prepararCampos($campos);
                if ($data === false) {
                        return false;
                }

                $db = Zend_Registry::get('db');
                $data['ativo'] = true;

                $db->insert('eventos_evento', $data);

                return true;
        }

        public static function update($idEvento, $campos)
        {
                $idEvento = (int) $idEvento;
                if (!$idEvento) {
                        self::$erro = 'Evento invalido.';
                        return false;
                }

                if (!self::buscaId($idEvento)) {
                        return false;
                }

                $data = self::prepararCampos($campos);
                if ($data === false) {
                        return false;
                }

                $db = Zend_Registry::get('db');
                $db->update('eventos_evento', $data, 'id_evento = ' . $idEvento);

                return true;
        }

        public static function desativar($idEvento)
        {
                $idEvento = (int) $idEvento;
                if (!$idEvento) {
                        self::$erro = 'Evento invalido.';
                        return false;
                }

                $db = Zend_Registry::get('db');
                $db->update('eventos_evento', array('ativo' => false), 'id_evento = ' . $idEvento);

                return true;
        }
}
