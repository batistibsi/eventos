<?php
class Inscricao
{
        public static $erro;

        public static function uniqueInscricao($email)
        {
                $db = Zend_Registry::get('db');

                $select = "select * from eventos_inscricao where email = " . $db->quote($email) . " and status not in ('CANCELADO','ENCERRADO')";

                $registros = $db->fetchAll($select);

                if (count($registros) == 0) return true;

                return false;
        }

        public static function novo($campos)
        {
                $db = Zend_Registry::get('db');

                if (!self::uniqueInscricao($campos['email'])) {
                        self::$erro = 'Inscrição já realizada para o email: ' . $campos['email'] . '!';
                        return false;
                }

                if (!Evento::confereVagas($campos['id_evento'])) {
                        self::$erro = 'Limite de vagas já atingida para o evento!';
                        return false;
                }

                $tokenValidadeHoras = 24;

                // token seguro
                $token = bin2hex(random_bytes(24));
                $expiraEm = (new DateTimeImmutable())->modify("+{$tokenValidadeHoras} hours")->format('Y-m-d H:i:s');

                $db->beginTransaction();

                $data = array(
                        'id_evento' => $campos['id_evento'],
                        'nome' => $campos['nome'],
                        'email' => $campos['email'],
                        'status' => 'CRIADO',
                        'token_confirmacao' => $token,
                        'token_expira_em' => $expiraEm
                );

                $db->insert("eventos_inscricao", $data);

                $select = "select max(id_inscricao) as id_inscricao from eventos_inscricao;";

                $registros = $db->fetchAll($select);

                $db->commit();

                return $registros[0]['id_inscricao'];
        }
}
