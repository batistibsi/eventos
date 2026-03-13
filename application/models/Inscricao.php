<?php
class Inscricao
{
        public static $erro;

        public static function buscaToken($token)
        {
                $db = Zend_Registry::get('db');

                $select = "select a.* 
                             from eventos_inscricao a
                             where a.token = " . $db->quote($token) . ";";

                $registros = $db->fetchAll($select);

                if (count($registros) == 0) return true;

                return $registros[0];
        }

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

        private static function uploadDir()
        {
                return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'inscricoes';
        }

        private static function validarArquivoLogo($arquivo)
        {
                if (!$arquivo || !isset($arquivo['tmp_name']) || !is_uploaded_file($arquivo['tmp_name'])) {
                        self::$erro = 'Arquivo da logo invalido.';
                        return false;
                }

                if (($arquivo['size'] ?? 0) > 10 * 1024 * 1024) {
                        self::$erro = 'A logo deve ter no maximo 10 MB.';
                        return false;
                }

                $extensao = strtolower(pathinfo($arquivo['name'] ?? '', PATHINFO_EXTENSION));
                $permitidas = ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp'];

                if (!in_array($extensao, $permitidas, true)) {
                        self::$erro = 'Envie a logo em PDF ou imagem valida.';
                        return false;
                }

                $mime = mime_content_type($arquivo['tmp_name']);
                $mimesPermitidos = [
                        'application/pdf',
                        'image/png',
                        'image/jpeg',
                        'image/gif',
                        'image/webp'
                ];

                if ($mime === false || !in_array($mime, $mimesPermitidos, true)) {
                        self::$erro = 'Tipo de arquivo da logo nao permitido.';
                        return false;
                }

                return $extensao;
        }

        private static function salvarLogo($arquivo, $token)
        {
                $extensao = self::validarArquivoLogo($arquivo);
                if ($extensao === false) {
                        return false;
                }

                $diretorio = self::uploadDir();
                if (!is_dir($diretorio) && !mkdir($diretorio, 0775, true)) {
                        self::$erro = 'Nao foi possivel preparar a pasta de upload da logo.';
                        return false;
                }

                $nomeArquivo = 'logo_inscricao_' . $token . '.' . $extensao;
                $destino = $diretorio . DIRECTORY_SEPARATOR . $nomeArquivo;

                if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
                        self::$erro = 'Nao foi possivel salvar a logo enviada.';
                        return false;
                }

                return 'inscricoes/' . $nomeArquivo;
        }

        public static function novo($campos)
        {
                $db = Zend_Registry::get('db');

                if (!self::uniqueInscricao($campos['email'])) {
                        self::$erro = 'Inscricao ja realizada para o email: ' . $campos['email'] . '!';
                        return false;
                }

                $evento = Evento::buscaId($campos['id_evento']);

                if (!$evento) {
                        self::$erro = 'Erro ao resgatar o evento!';
                        return false;
                }

                $tokenValidadeHoras = 24;
                $token = bin2hex(random_bytes(24));
                $expiraEm = (new DateTimeImmutable())->modify("+{$tokenValidadeHoras} hours")->format('Y-m-d H:i:s');

                $logoPath = self::salvarLogo($campos['logo_organizacao'], $token);
                if ($logoPath === false) {
                        return false;
                }

                $db->beginTransaction();

                try {
                        if (!Evento::confereVagas($campos['id_evento'], $evento['limite_vagas'])) {
                                self::$erro = 'Limite de vagas ja atingida para o evento!';
                                @unlink(self::uploadDir() . DIRECTORY_SEPARATOR . basename($logoPath));
                                $db->rollBack();
                                return false;
                        }

                        $data = array(
                                'id_evento' => $campos['id_evento'],
                                'nome' => $campos['nome'],
                                'email' => $campos['email'],
                                'cpf_responsavel' => $campos['cpf_responsavel'],
                                'nome_organizacao' => $campos['nome_organizacao'],
                                'cnpj' => $campos['cnpj'],
                                'endereco' => $campos['endereco'],
                                'numero_colaboradores' => $campos['numero_colaboradores'],
                                'representante_1_nome' => $campos['representante_1_nome'],
                                'representante_1_email' => $campos['representante_1_email'],
                                'representante_1_telefone' => $campos['representante_1_telefone'],
                                'representante_2_nome' => !empty($campos['representante_2_nome']) ? $campos['representante_2_nome'] : null,
                                'representante_2_email' => !empty($campos['representante_2_email']) ? $campos['representante_2_email'] : null,
                                'representante_2_telefone' => !empty($campos['representante_2_telefone']) ? $campos['representante_2_telefone'] : null,
                                'representante_3_nome' => !empty($campos['representante_3_nome']) ? $campos['representante_3_nome'] : null,
                                'representante_3_email' => !empty($campos['representante_3_email']) ? $campos['representante_3_email'] : null,
                                'representante_3_telefone' => !empty($campos['representante_3_telefone']) ? $campos['representante_3_telefone'] : null,
                                'primeira_participacao' => $campos['primeira_participacao'] === 'sim',
                                'nome_certificado' => $campos['nome_certificado'],
                                'logo_organizacao' => $logoPath,
                                'como_soube' => $campos['como_soube'],
                                'indicacao_organizacao' => !empty($campos['indicacao_organizacao']) ? $campos['indicacao_organizacao'] : null,
                                'status' => 'CRIADO',
                                'token_confirmacao' => $token,
                                'token_expira_em' => $expiraEm
                        );

                        $db->insert('eventos_inscricao', $data);

                        $select = 'select max(id_inscricao) as id_inscricao from eventos_inscricao;';
                        $registros = $db->fetchAll($select);

                        $db->commit();
                } catch (Exception $e) {
                        if ($db->getConnection()->inTransaction()) {
                                $db->rollBack();
                        }
                        @unlink(self::uploadDir() . DIRECTORY_SEPARATOR . basename($logoPath));
                        self::$erro = 'Nao foi possivel concluir a inscricao.';
                        return false;
                }

                /*
                $link_cancelar = self::url($token, 'cancelar_inscricao.php');
                $link_confirmar = self::url($token, 'confirmar_inscricao.php');

                $msg = '<p>Voce acaba solicitar a inscricao no evento ' . $evento['label'] . '.</p>'
                        . '<p>Confirme a inscricao clicando no link abaixo:</p>'
                        . '<p><a target="_blank" href="' . $link_confirmar . '">' . $link_confirmar . '</a></p>'
                        . '<br></br><p>Se deseja <strong>CANCELAR A INSCRICAO</strong>, clique neste link:</p>'
                        . '<p><a target="_blank" href="' . $link_cancelar . '">' . $link_cancelar . '</a></p>';

                Email::enviar($campos['email'], 'Solicitacao de inscricao', $msg);
                */
                
                return $registros[0]['id_inscricao'];
        }

        public static function consulta($inicio, $fim)
        {
                $db = Zend_Registry::get('db');

                $inicio = $db->quote($inicio . ' 00:00:00');
                $fim = $db->quote($fim . ' 23:59:59');

                $select = "select a.*, b.titulo, b.data_hora
                        from eventos_inscricao a
                        inner join eventos_evento b on a.id_evento = b.id_evento
                        where a.created_at between " . $inicio . " and " . $fim;

                $registros = $db->fetchAll($select);

                return $registros;
        }
}
