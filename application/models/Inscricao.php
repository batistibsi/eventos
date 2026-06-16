<?php
class Inscricao
{
        public static $erro;
        const MAX_NOME_RESPONSAVEL = 120;
        const MAX_NOME_ORGANIZACAO = 150;
        const MAX_REPRESENTANTE_NOME = 120;
        const MAX_NOME_CERTIFICADO = 150;
        const MAX_ENDERECO = 200;
        const MAX_COMO_SOUBE = 120;
        const MAX_INDICACAO_ORGANIZACAO = 500;
        const MAX_SUMMIT_INSCRICOES = 3;
        const MAX_SUMMIT_NOME = 150;
        const MAX_SUMMIT_CARGO = 150;

        public static function statusDisponiveis()
        {
                return ['CRIADO', 'CONFIRMADO', 'ISENTO CONFIRMADO', 'BLOQUEADO', 'DUPLICADO', 'CANCELADO', 'ENCERRADO'];
        }

        public static function statusInativos()
        {
                return ['BLOQUEADO', 'DUPLICADO', 'CANCELADO', 'ENCERRADO'];
        }

        public static function buscaId($id_inscricao)
        {
                $db = Zend_Registry::get('db');
                $id_inscricao = (int) $id_inscricao;

                $select = "select a.*,
                                    b.titulo as evento_titulo,
                                    b.observacao as evento_observacao,
                                    b.data_hora as evento_data_hora,
                                    b.data_hora_2 as evento_data_hora_2,
                                    b.limite_vagas as evento_limite_vagas,
                                    b.data_inscricao_summit as data_inscricao_summit,
                                    b.data_summit as data_summit,
                                    sa.nome as status_auditoria_nome,
                                    sa.descricao as status_auditoria_descricao,
                                    fp.descricao as forma_pagamento_descricao,
                                    u.id_usuario as usuario_id,
                                    u.nome as usuario_nome,
                                    u.email as usuario_email,
                                    p.descricao as usuario_perfil
                             from eventos_inscricao a
                             left join eventos_evento b on b.id_evento = a.id_evento
                             left join eventos_status_auditoria sa on sa.id_status_auditoria = a.id_status_auditoria
                             left join eventos_forma_pagamento fp on fp.id_forma_pagamento = a.id_forma_pagamento
                             left join eventos_usuario u on a.id_usuario = u.id_usuario and u.ativo
                             left join eventos_perfil p on u.id_perfil = p.id_perfil
                             where a.id_inscricao = " . $db->quote($id_inscricao) . ";";

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

        public static function uniqueInscricaoEmailExceto($email, $id_inscricao)
        {
                $db = Zend_Registry::get('db');
                $id_inscricao = (int) $id_inscricao;

                $select = "select * from eventos_inscricao where email = " . $db->quote($email) . " and id_inscricao <> " . $id_inscricao . " and status not in (" . implode(',', array_map([$db, 'quote'], self::statusInativos())) . ")";

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

        public static function uniqueInscricaoCNPJExceto($cnpj, $id_inscricao)
        {
                $db = Zend_Registry::get('db');
                $id_inscricao = (int) $id_inscricao;

                $select = "select * from eventos_inscricao where cnpj = " . $db->quote($cnpj) . " and id_inscricao <> " . $id_inscricao . " and status not in (" . implode(',', array_map([$db, 'quote'], self::statusInativos())) . ")";

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
                        $telefone = self::normalizarTelefone($campos['summit_telefone_' . $i] ?? '');
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

                        if (!self::telefoneValido($telefone)) {
                                self::$erro = 'Informe um telefone valido com DDD para o representante do Summit ' . $i . '.';
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

        private static function normalizarTelefone($valor)
        {
                return preg_replace('/\D+/', '', trim((string) $valor));
        }

        private static function telefoneValido($valor)
        {
                return (bool) preg_match('/^\d{10,11}$/', (string) $valor);
        }

        private static function emailValido($valor)
        {
                return filter_var((string) $valor, FILTER_VALIDATE_EMAIL) !== false;
        }

        private static function documentoNumerico($valor)
        {
                return preg_replace('/\D+/', '', trim((string) $valor));
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

                $idFormaPagamento = isset($campos['id_forma_pagamento']) ? (int) $campos['id_forma_pagamento'] : 0;
                $formaPagamento = FormaPagamento::buscaId($idFormaPagamento);
                if ($idFormaPagamento <= 0 || !$formaPagamento || empty($formaPagamento['ativo'])) {
                        self::$erro = 'Informe uma forma de pagamento valida.';
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
                                'id_forma_pagamento' => $idFormaPagamento,
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

        public static function podeVisualizar($inscricao, $id_usuario, $permissao)
        {
                if (!$inscricao || !is_array($inscricao)) {
                        return false;
                }

                $permissao = (int) $permissao;
                $id_usuario = (int) $id_usuario;

                if ($permissao === 1) {
                        return true;
                }

                if ($permissao === 2) {
                        return (int) ($inscricao['id_auditor'] ?? 0) === $id_usuario;
                }

                if ($permissao === 3) {
                        return (int) ($inscricao['id_usuario'] ?? 0) === $id_usuario;
                }

                return false;
        }

        public static function definirAuditor($id_inscricao, $id_auditor)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;
                $id_auditor = $id_auditor !== null && $id_auditor !== '' ? (int) $id_auditor : null;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                if ($id_auditor !== null) {
                        $auditor = Usuario::buscaId($id_auditor);
                        if (!$auditor || !is_array($auditor) || (int) ($auditor['id_perfil'] ?? 0) !== 2) {
                                self::$erro = 'Auditor invalido.';
                                return false;
                        }
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $data = ['id_auditor' => $id_auditor];

                        if (empty($inscricao['id_auditor']) && $id_auditor !== null) {
                                $data['id_status_auditoria'] = 2;
                        }

                        $db->update('eventos_inscricao', $data, $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel definir o auditor.';
                        return false;
                }

                return true;
        }

        public static function podeAvancarStatusAuditoria($id_status_auditoria, $permissao)
        {
                $id_status_auditoria = (int) $id_status_auditoria;
                $permissao = (int) $permissao;

                $permissoesPorStatus = array(
                        2 => array(1, 2),
                        3 => array(1, 3),
                        4 => array(1, 2)
                );

                return isset($permissoesPorStatus[$id_status_auditoria])
                        && in_array($permissao, $permissoesPorStatus[$id_status_auditoria], true);
        }

        public static function avancarStatusAuditoria($id_inscricao, $id_usuario = 0, $permissao = 0)
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

                if (!self::podeVisualizar($inscricao, $id_usuario, $permissao)) {
                        self::$erro = 'Nao permitido!';
                        return false;
                }

                $idStatusAtual = !empty($inscricao['id_status_auditoria']) ? (int) $inscricao['id_status_auditoria'] : 0;
                if (!$idStatusAtual) {
                        self::$erro = 'A inscricao ainda nao entrou no fluxo de auditoria.';
                        return false;
                }

                if (!in_array($idStatusAtual, array(2, 3, 4), true)) {
                        self::$erro = 'O avancar de status de auditoria nao esta disponivel nesta etapa.';
                        return false;
                }

                if (!self::podeAvancarStatusAuditoria($idStatusAtual, $permissao)) {
                        self::$erro = 'Voce nao tem permissao para avancar esta etapa da auditoria.';
                        return false;
                }

                $proximoStatus = Auditoria::buscaProximoStatus($idStatusAtual);
                if (!$proximoStatus || !is_array($proximoStatus) || empty($proximoStatus['id_status_auditoria'])) {
                        self::$erro = 'Nao existe proximo status de auditoria para esta inscricao.';
                        return false;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update(
                                'eventos_inscricao',
                                array('id_status_auditoria' => (int) $proximoStatus['id_status_auditoria']),
                                $where
                        );
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel avancar o status de auditoria.';
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

        public static function salvarFormaPagamento($id_inscricao, $id_forma_pagamento)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;
                $id_forma_pagamento = (int) $id_forma_pagamento;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                if ($id_forma_pagamento <= 0) {
                        self::$erro = 'Forma de pagamento nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $formaPagamento = FormaPagamento::buscaId($id_forma_pagamento);
                if (!$formaPagamento) {
                        self::$erro = 'Forma de pagamento invalida.';
                        return false;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', ['id_forma_pagamento' => $id_forma_pagamento], $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar a forma de pagamento da inscricao.';
                        return false;
                }

                return true;
        }

        public static function salvarEvento($id_inscricao, $id_evento)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;
                $id_evento = (int) $id_evento;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                if ($id_evento <= 0) {
                        self::$erro = 'Evento nao informado.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $evento = Evento::buscaId($id_evento);
                if (!$evento || !is_array($evento)) {
                        self::$erro = 'Evento invalido.';
                        return false;
                }

                $idEventoAtual = (int) ($inscricao['id_evento'] ?? 0);
                if ($idEventoAtual === $id_evento) {
                        return true;
                }

                if (!Evento::confereVagas($id_evento, $evento['limite_vagas'])) {
                        self::$erro = 'Limite de vagas ja atingida para o evento!';
                        return false;
                }

                try {
                        $db->beginTransaction();

                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', ['id_evento' => $id_evento], $where);

                        $idUsuario = !empty($inscricao['id_usuario']) ? (int) $inscricao['id_usuario'] : 0;
                        if ($idUsuario > 0 && !Projeto::sincronizarEventoPorUsuario($idUsuario, $id_evento)) {
                                if ($db->getConnection()->inTransaction()) {
                                        $db->rollBack();
                                }

                                self::$erro = Projeto::$erro ?: 'Nao foi possivel sincronizar o evento dos projetos vinculados.';
                                return false;
                        }

                        $db->commit();
                } catch (Exception $e) {
                        if ($db->getConnection()->inTransaction()) {
                                $db->rollBack();
                        }

                        self::$erro = 'Nao foi possivel salvar o evento da inscricao.';
                        return false;
                }

                return true;
        }

        public static function salvarConfirmacaoEncontrosFormacao($id_inscricao, $campos)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $dados = array(
                        'encontro_formacao_1' => !empty($campos['encontro_formacao_1']),
                        'encontro_formacao_2' => !empty($campos['encontro_formacao_2'])
                );

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', $dados, $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar as confirmacoes dos encontros de formacao.';
                        return false;
                }

                return true;
        }

        public static function salvarRepresentantes($id_inscricao, $campos)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $dados = [];

                for ($i = 1; $i <= 3; $i++) {
                        $nome = self::validarTamanhoTexto($campos['representante_' . $i . '_nome'] ?? '', self::MAX_REPRESENTANTE_NOME, 'nome do representante ' . $i, false);
                        if ($nome === false) {
                                return false;
                        }

                        $email = trim((string) ($campos['representante_' . $i . '_email'] ?? ''));
                        $telefone = self::normalizarTelefone($campos['representante_' . $i . '_telefone'] ?? '');

                        if ($email !== '' && !self::emailValido($email)) {
                                self::$erro = 'Informe um e-mail valido para o representante ' . $i . '.';
                                return false;
                        }

                        if ($email !== '' && strlen($email) > 150) {
                                self::$erro = 'O e-mail do representante ' . $i . ' deve ter no maximo 150 caracteres.';
                                return false;
                        }

                        if ($telefone !== '' && !self::telefoneValido($telefone)) {
                                self::$erro = 'Informe um telefone valido com DDD para o representante ' . $i . '.';
                                return false;
                        }

                        $dados['representante_' . $i . '_nome'] = $nome !== '' ? $nome : null;
                        $dados['representante_' . $i . '_email'] = $email !== '' ? $email : null;
                        $dados['representante_' . $i . '_telefone'] = $telefone !== '' ? $telefone : null;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', $dados, $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar os representantes.';
                        return false;
                }

                return true;
        }

        public static function salvarResponsavel($id_inscricao, $campos)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $nome = self::validarTamanhoTexto($campos['nome'] ?? '', self::MAX_NOME_RESPONSAVEL, 'nome do responsavel');
                if ($nome === false) {
                        return false;
                }

                $cpf = self::documentoNumerico($campos['cpf_responsavel'] ?? '');
                if (!Util::validaCPF($cpf)) {
                        self::$erro = 'Informe um CPF valido para o responsavel.';
                        return false;
                }

                $email = trim((string) ($campos['email'] ?? ''));
                if ($email === '' || !self::emailValido($email)) {
                        self::$erro = 'Informe um e-mail valido para o responsavel.';
                        return false;
                }
                if (strlen($email) > 120) {
                        self::$erro = 'O e-mail do responsavel deve ter no maximo 120 caracteres.';
                        return false;
                }
                if (!self::uniqueInscricaoEmailExceto($email, $id_inscricao)) {
                        self::$erro = 'Inscricao ja realizada para o email: ' . $email . '!';
                        return false;
                }

                $telefone = self::normalizarTelefone($campos['telefone'] ?? '');
                if (!self::telefoneValido($telefone)) {
                        self::$erro = 'Informe um telefone valido com DDD para o responsavel.';
                        return false;
                }

                $endereco = self::validarTamanhoTexto($campos['endereco'] ?? '', self::MAX_ENDERECO, 'endereco');
                if ($endereco === false) {
                        return false;
                }

                $dados = array(
                        'nome' => $nome,
                        'cpf_responsavel' => $cpf,
                        'email' => $email,
                        'telefone' => $telefone,
                        'endereco' => $endereco
                );

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', $dados, $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar os dados do responsavel.';
                        return false;
                }

                return true;
        }

        public static function salvarOrganizacao($id_inscricao, $campos, $logo = null)
        {
                $db = Zend_Registry::get('db');

                $id_inscricao = (int) $id_inscricao;

                if ($id_inscricao <= 0) {
                        self::$erro = 'Inscricao nao informada.';
                        return false;
                }

                $inscricao = self::buscaId($id_inscricao);
                if (!$inscricao || !is_array($inscricao)) {
                        self::$erro = 'Inscricao nao encontrada.';
                        return false;
                }

                $nomeOrganizacao = self::validarTamanhoTexto($campos['nome_organizacao'] ?? '', self::MAX_NOME_ORGANIZACAO, 'nome da organizacao');
                if ($nomeOrganizacao === false) {
                        return false;
                }

                $cnpj = self::documentoNumerico($campos['cnpj'] ?? '');
                if (!Util::validaCNPJ($cnpj)) {
                        self::$erro = 'Informe um CNPJ valido.';
                        return false;
                }
                if (!self::uniqueInscricaoCNPJExceto($cnpj, $id_inscricao)) {
                        self::$erro = 'Inscricao ja realizada para o CNPJ: ' . $cnpj . '!';
                        return false;
                }

                $numeroColaboradores = isset($campos['numero_colaboradores']) ? (int) $campos['numero_colaboradores'] : 0;
                if ($numeroColaboradores <= 0) {
                        self::$erro = 'Informe a quantidade de colaboradores.';
                        return false;
                }

                $nomeCertificado = self::validarTamanhoTexto($campos['nome_certificado'] ?? '', self::MAX_NOME_CERTIFICADO, 'nome da organizacao no certificado');
                if ($nomeCertificado === false) {
                        return false;
                }

                $primeiraParticipacao = trim((string) ($campos['primeira_participacao'] ?? ''));
                if (!in_array($primeiraParticipacao, array('sim', 'nao'), true)) {
                        self::$erro = 'Informe corretamente se e a primeira participacao.';
                        return false;
                }

                $comoSoube = self::validarTamanhoTexto($campos['como_soube'] ?? '', self::MAX_COMO_SOUBE, 'como ficou sabendo da certificacao');
                if ($comoSoube === false) {
                        return false;
                }

                $indicacaoOrganizacao = self::validarTamanhoTexto($campos['indicacao_organizacao'] ?? '', self::MAX_INDICACAO_ORGANIZACAO, 'organizacao indicada', false);
                if ($indicacaoOrganizacao === false) {
                        return false;
                }

                $dados = array(
                        'nome_organizacao' => $nomeOrganizacao,
                        'cnpj' => $cnpj,
                        'numero_colaboradores' => $numeroColaboradores,
                        'nome_certificado' => $nomeCertificado,
                        'primeira_participacao' => $primeiraParticipacao === 'sim',
                        'como_soube' => $comoSoube,
                        'indicacao_organizacao' => $indicacaoOrganizacao !== '' ? $indicacaoOrganizacao : null
                );

                $logoPath = null;
                if ($logo && isset($logo['error']) && (int) $logo['error'] !== UPLOAD_ERR_NO_FILE) {
                        if ((int) $logo['error'] !== UPLOAD_ERR_OK) {
                                self::$erro = 'Nao foi possivel receber a logo enviada.';
                                return false;
                        }

                        $tokenLogo = !empty($inscricao['token_confirmacao']) ? $inscricao['token_confirmacao'] : 'edicao_' . $id_inscricao . '_' . time();
                        $logoPath = self::salvarLogo($logo, $tokenLogo);
                        if ($logoPath === false) {
                                return false;
                        }
                        $dados['logo_organizacao'] = $logoPath;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', $dados, $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar os dados da organizacao.';
                        return false;
                }

                return true;
        }

        public static function aceiteProjetosPendentePorUsuario($id_usuario)
        {
                $resumo = self::buscaResumoVinculadoUsuario($id_usuario);
                if (!$resumo || !is_array($resumo)) {
                        return false;
                }

                return empty($resumo['aceite_termo']);
        }

        public static function salvarAceiteTermo($id_inscricao, $id_usuario = 0, $permissao = 0)
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

                if (!self::podeVisualizar($inscricao, $id_usuario, $permissao)) {
                        self::$erro = 'Nao permitido!';
                        return false;
                }

                try {
                        $where = $db->quoteInto('id_inscricao = ?', $id_inscricao);
                        $db->update('eventos_inscricao', array('aceite_termo' => true), $where);
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel registrar o aceite do termo.';
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

                $statusConfirmados = implode(',', array_map([$db, 'quote'], ['CONFIRMADO', 'ISENTO CONFIRMADO']));

                $select = "select a.id_inscricao
                             from eventos_inscricao a
                            where a.id_usuario = " . $id_usuario . "
                              and upper(coalesce(a.status, '')) in (" . $statusConfirmados . ")
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
                                  a.aceite_termo,
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
