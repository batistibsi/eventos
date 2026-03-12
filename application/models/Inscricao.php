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

        public static function url($token, $acao)
        {
                $url = Util::request_origin(false);

                return $url . '/site/' . $acao . '?token=' . $token;
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

                $link_cancelar = self::url($token, 'cancelar_inscricao.php');
                $link_confirmar = self::url($token, 'confirmar_inscricao.php');

                $msg = '<p>Você acaba solicitar a inscrição no evento.</p>'
                        . '<p>Confirme a inscrição clicando no link abaixo:</p>'
                        . '<p><a target="_blank" href="' . $link_confirmar . '">' . $link_confirmar . '</a></p>'
                        . '<br></br><p>Se deseja <strong>CANCELAR A INSCRIÇÃO</strong>, clique neste link:</p>'
                        . '<p><a target="_blank" href="' . $link_cancelar . '">' . $link_cancelar . '</a></p>';

                Email::enviar($campos['email'], 'Solicitação de inscrição', $msg);

                return $registros[0]['id_inscricao'];
        }

        public static function consulta($inicio, $fim)
        {
                $db = Zend_Registry::get('db');

                $inicio = $db->quote($inicio.' 00:00:00');
                $fim = $db->quote($fim.' 23:59:59');

                $select = "select a.*, b.titulo, b.data_hora
                        from eventos_inscricao a
                        inner join eventos_evento b on a.id_evento = b.id_evento
                        where a.created_at between " . $inicio . " and " . $fim;

                $registros = $db->fetchAll($select);

                return $registros;
        }
}
