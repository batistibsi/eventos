<?php
class Inscricao
{
        public static $erro;
        const MAX_NOME_RESPONSAVEL = 120;
        const MAX_NOME_ORGANIZACAO = 150;
        const MAX_REPRESENTANTE_NOME = 120;
        const MAX_NOME_CERTIFICADO = 150;
        const MAX_SUMMIT_INSCRICOES = 3;
        const MAX_SUMMIT_NOME = 150;
        const MAX_SUMMIT_CARGO = 150;

        public static function statusDisponiveis()
        {
                return ['CRIADO', 'CONFIRMADO', 'BLOQUEADO', 'DUPLICADO', 'CANCELADO', 'ENCERRADO'];
        }

        public static function statusInativos()
        {
                return ['BLOQUEADO', 'DUPLICADO', 'CANCELADO', 'ENCERRADO'];
        }

        public static function buscaId($id_inscricao)
        {
                $db = Zend_Registry::get('db');

                $select = "select a.*,
                                    b.titulo as evento_titulo,
                                    b.observacao as evento_observacao,
                                    b.data_hora as evento_data_hora,
                                    b.data_hora_2 as evento_data_hora_2,
                                    b.limite_vagas as evento_limite_vagas,
                                    b.data_inscricao_summit as data_inscricao_summit,
                                    b.data_summit as data_summit,
                                    u.id_usuario as usuario_id,
                                    u.nome as usuario_nome,
                                    u.email as usuario_email,
                                    p.descricao as usuario_perfil
                             from eventos_inscricao a
                             left join eventos_evento b on b.id_evento = a.id_evento
                             left join eventos_usuario u on a.id_usuario = u.id_usuario and u.ativo
                             left join eventos_perfil p on u.id_perfil = p.id_perfil
                             where a.id_inscricao = " . $id_inscricao . ";";

                $registros = $db->fetchAll($select);

                if (count($registros) == 0) return true;

                return $registros[0];
        }

        public static function listaInscricoesSummit($id_inscricao)
        {
                $db = Zend_Registry::get('db');
                $id_inscricao = (int) $id_inscricao;

                if ($id_inscricao <= 0) {
                        return [];
                }

                $select = "select id_inscricao_summit,
                                  id_inscricao,
                                  nome_representante,
                                  cargo_representante,
                                  telefone_contato,
                                  ordem,
                                  created_at
                             from eventos_inscricao_summit
                            where id_inscricao = " . $id_inscricao . "
                            order by ordem asc";

                $registros = $db->fetchAll($select);
                return is_array($registros) ? $registros : [];
        }

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

        public static function uniqueInscricaoEmail($email)
        {
                $db = Zend_Registry::get('db');

                $select = "select * from eventos_inscricao where email = " . $db->quote($email) . " and status not in (" . implode(',', array_map([$db, 'quote'], self::statusInativos())) . ")";

                $registros = $db->fetchAll($select);

                if (count($registros) == 0) return true;

                return false;
        }

        public static function uniqueInscricaoCNPJ($cnpj)
        {
                $db = Zend_Registry::get('db');

                $select = "select * from eventos_inscricao where cnpj = " . $db->quote($cnpj) . " and status not in (" . implode(',', array_map([$db, 'quote'], self::statusInativos())) . ")";

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

        private static function validarTamanhoTexto($valor, $limite, $rotulo, $obrigatorio = true)
        {
                $valor = trim((string) $valor);

                if ($obrigatorio && $valor === '') {
                        self::$erro = 'Informe ' . $rotulo . '.';
                        return false;
                }

                if ($valor !== '' && strlen($valor) > $limite) {
                        self::$erro = 'O campo ' . $rotulo . ' deve ter no maximo ' . $limite . ' caracteres.';
                        return false;
                }

                return $valor;
        }

        private static function prazoSummitEncerrado($dataSummit)
        {
                if (empty($dataSummit)) {
                        self::$erro = 'A data limite do Summit nao esta configurada para este evento.';
                        return true;
                }

                try {
                        $hoje = new DateTimeImmutable(date('Y-m-d'));
                        $limite = new DateTimeImmutable((new DateTimeImmutable($dataSummit))->format('Y-m-d'));
                } catch (Exception $e) {
                        self::$erro = 'A data limite do Summit e invalida.';
                        return true;
                }

                if ($hoje > $limite) {
                        self::$erro = 'O prazo para preencher a inscricao no Summit foi encerrado.';
                        return true;
                }

                return false;
        }

        private static function prepararInscricoesSummit($campos)
        {
                $inscricoes = [];

                for ($i = 1; $i <= self::MAX_SUMMIT_INSCRICOES; $i++) {
                        $nome = trim((string) ($campos['summit_nome_' . $i] ?? ''));
                        $cargo = trim((string) ($campos['summit_cargo_' . $i] ?? ''));
                        $telefone = trim((string) ($campos['summit_telefone_' . $i] ?? ''));
                        $temAlgumValor = ($nome !== '' || $cargo !== '' || $telefone !== '');

                        if (!$temAlgumValor) {
                                continue;
                        }

                        $nome = self::validarTamanhoTexto($nome, self::MAX_SUMMIT_NOME, 'o nome completo do representante do Summit ' . $i);
                        if ($nome === false) {
                                return false;
                        }

                        $cargo = self::validarTamanhoTexto($cargo, self::MAX_SUMMIT_CARGO, 'o cargo do representante do Summit ' . $i);
                        if ($cargo === false) {
                                return false;
                        }

                        if ($telefone === '') {
                                self::$erro = 'Informe o telefone para contato do representante do Summit ' . $i . '.';
                                return false;
                        }

                        if (strlen($telefone) > 20) {
                                self::$erro = 'O telefone para contato do representante do Summit ' . $i . ' deve ter no maximo 20 caracteres.';
                                return false;
                        }

                        $inscricoes[] = [
                                'nome_representante' => $nome,
                                'cargo_representante' => $cargo,
                                'telefone_contato' => $telefone,
                                'ordem' => count($inscricoes) + 1
                        ];
                }

                return $inscricoes;
        }

        public static function novo($campos)
        {
                $db = Zend_Registry::get('db');

                $nome = self::validarTamanhoTexto($campos['nome'] ?? '', self::MAX_NOME_RESPONSAVEL, 'nome do responsavel');
                if ($nome === false) {
                        return false;
                }

                $nomeOrganizacao = self::validarTamanhoTexto($campos['nome_organizacao'] ?? '', self::MAX_NOME_ORGANIZACAO, 'nome da organizacao');
                if ($nomeOrganizacao === false) {
                        return false;
                }

                $representante1Nome = self::validarTamanhoTexto($campos['representante_1_nome'] ?? '', self::MAX_REPRESENTANTE_NOME, 'nome do representante 1');
                if ($representante1Nome === false) {
                        return false;
                }

                $representante2Nome = self::validarTamanhoTexto($campos['representante_2_nome'] ?? '', self::MAX_REPRESENTANTE_NOME, 'nome do representante 2', false);
                if ($representante2Nome === false) {
                        return false;
                }

                $representante3Nome = self::validarTamanhoTexto($campos['representante_3_nome'] ?? '', self::MAX_REPRESENTANTE_NOME, 'nome do representante 3', false);
                if ($representante3Nome === false) {
                        return false;
                }

                $nomeCertificado = self::validarTamanhoTexto($campos['nome_certificado'] ?? '', self::MAX_NOME_CERTIFICADO, 'nome da organizacao no certificado');
                if ($nomeCertificado === false) {
                        return false;
                }

                if (!self::uniqueInscricaoEmail($campos['email'])) {
                        self::$erro = 'Inscricao ja realizada para o email: ' . $campos['email'] . '!';
                        return false;
                }

                if (!self::uniqueInscricaoCNPJ($campos['cnpj'])) {
                        self::$erro = 'Inscricao ja realizada para o CNPJ: ' . $campos['cnpj'] . '!';
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
                                'nome' => $nome,
                                'email' => $campos['email'],
                                'telefone' => $campos['telefone'],
                                'cpf_responsavel' => $campos['cpf_responsavel'],
                                'nome_organizacao' => $nomeOrganizacao,
                                'cnpj' => $campos['cnpj'],
                                'endereco' => $campos['endereco'],
                                'numero_colaboradores' => $campos['numero_colaboradores'],
                                'representante_1_nome' => $representante1Nome,
                                'representante_1_email' => $campos['representante_1_email'],
                                'representante_1_telefone' => $campos['representante_1_telefone'],
                                'representante_2_nome' => $representante2Nome !== '' ? $representante2Nome : null,
                                'representante_2_email' => !empty($campos['representante_2_email']) ? $campos['representante_2_email'] : null,
                                'representante_2_telefone' => !empty($campos['representante_2_telefone']) ? $campos['representante_2_telefone'] : null,
                                'representante_3_nome' => $representante3Nome !== '' ? $representante3Nome : null,
                                'representante_3_email' => !empty($campos['representante_3_email']) ? $campos['representante_3_email'] : null,
                                'representante_3_telefone' => !empty($campos['representante_3_telefone']) ? $campos['representante_3_telefone'] : null,
                                'primeira_participacao' => $campos['primeira_participacao'] === 'sim',
                                'nome_certificado' => $nomeCertificado,
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

                $select = "select a.*, b.titulo, b.data_hora, u.nome as usuario
                        from eventos_inscricao a
                        inner join eventos_evento b on a.id_evento = b.id_evento                        
                        left join eventos_usuario u on a.id_usuario = u.id_usuario and u.ativo
                        where a.created_at between " . $inicio . " and " . $fim;

                $registros = $db->fetchAll($select);

                return $registros;
        }

        public static function alterarStatus($id_inscricao, $status)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;
                $status = strtoupper(trim((string) $status));

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                if (!in_array($status, self::statusDisponiveis(), true)) {
                        self::$erro = 'Status invalido.';
                        return false;
                }

                $atual = self::buscaId($id_inscricao);

                if (!$atual || !is_array($atual)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', ['status' => $status], $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel atualizar o status.';
                        return false;
                }

                return true;
        }

        public static function salvarInscricoesSummit($id_inscricao, $campos, $id_usuario = 0, $permissao = 0)
        {
                $db = Zend_Registry::get('db');
                $id_inscricao = (int) $id_inscricao;
                $id_usuario = (int) $id_usuario;
                $permissao = (int) $permissao;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                if ($permissao !== 1 && (int) ($inscricao['id_usuario'] ?? 0) !== $id_usuario) {
                        self::$erro = 'Nao permitido!';
                        return false;
                }

                if (self::prazoSummitEncerrado($inscricao['data_inscricao_summit'] ?? null)) {
                        return false;
                }

                $inscricoes = self::prepararInscricoesSummit($campos);
                if ($inscricoes === false) {
                        return false;
                }

                try {
                        $db->beginTransaction();
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->delete('eventos_inscricao_summit', $where);

                        foreach ($inscricoes as $item) {
                                $db->insert('eventos_inscricao_summit', [
                                        'id_inscricao' => $id_inscricao,
                                        'nome_representante' => $item['nome_representante'],
                                        'cargo_representante' => $item['cargo_representante'],
                                        'telefone_contato' => $item['telefone_contato'],
                                        'ordem' => $item['ordem']
                                ]);
                        }

                        $db->commit();
                } catch (Exception $e) {
                        if ($db->getConnection()->inTransaction()) {
                                $db->rollBack();
                        }

                        self::$erro = 'Nao foi possivel salvar as inscricoes do Summit.';
                        return false;
                }

                return true;
        }

        public static function vincularUsuario($id_inscricao, $id_usuario)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;
                $id_usuario = (int) $id_usuario;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                if ($id_usuario <= 0 || !Usuario::buscaId($id_usuario)) {
                        self::$erro = 'Usuario invalido.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', ['id_usuario' => $id_usuario], $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel vincular o usuario a inscricao.';
                        return false;
                }

                return true;
        }

        public static function buscaEventoVinculadoUsuario($id_usuario)
        {
                $db = Zend_Registry::get('db');
                $id_usuario = (int) $id_usuario;

                if ($id_usuario <= 0) {
                        self::$erro = 'Usuario nao informado.';
                        return false;
                }

                $select = "select a.id_evento,
                                  e.titulo,
                                  e.data_hora,
                                  e.data_submissao
                             from eventos_inscricao a
                             inner join eventos_evento e on e.id_evento = a.id_evento
                             where a.id_usuario = " . $id_usuario . "
                             order by a.id_inscricao desc
                             limit 1";

                $registro = $db->fetchRow($select);
                if (!$registro) {
                        self::$erro = 'Nao foi encontrada inscricao vinculada para este usuario.';
                        return false;
                }

                $registro['label'] = Evento::getLabel($registro['titulo'], $registro['data_hora']);
                return $registro;
        }

        public static function usuarioPossuiInscricaoConfirmada($id_usuario)
        {
                $db = Zend_Registry::get('db');
                $id_usuario = (int) $id_usuario;

                if ($id_usuario <= 0) {
                        self::$erro = 'Usuario nao informado.';
                        return false;
                }

                $select = "select a.id_inscricao
                             from eventos_inscricao a
                            where a.id_usuario = " . $id_usuario . "
                              and upper(coalesce(a.status, '')) = 'CONFIRMADO'
                            order by a.id_inscricao desc
                            limit 1";

                return (bool) $db->fetchOne($select);
        }

        public static function buscaResumoVinculadoUsuario($id_usuario)
        {
                $db = Zend_Registry::get('db');
                $id_usuario = (int) $id_usuario;

                if ($id_usuario <= 0) {
                        self::$erro = 'Usuario nao informado.';
                        return false;
                }

                $select = "select a.id_inscricao,
                                  a.id_evento,
                                  a.nome_organizacao,
                                  a.cnpj,
                                  a.nome_certificado,
                                  a.numero_colaboradores,
                                  a.nome,
                                  a.email,
                                  a.telefone,
                                  e.titulo,
                                  e.data_hora
                             from eventos_inscricao a
                             inner join eventos_evento e on e.id_evento = a.id_evento
                             where a.id_usuario = " . $id_usuario . "
                             order by a.id_inscricao desc
                             limit 1";

                $registro = $db->fetchRow($select);
                if (!$registro) {
                        self::$erro = 'Nao foi encontrada inscricao vinculada para este usuario.';
                        return false;
                }

                $registro['evento_label'] = Evento::getLabel($registro['titulo'], $registro['data_hora']);
                return $registro;
        }
}
