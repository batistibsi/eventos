<?php
class Config
{
        public static $erro;
        const DASHBOARD_UPLOAD_DIR = 'dashboard';
        const MATERIAL_UPLOAD_DIR = 'dashboard/material';
        const MATERIAL_ARQUIVOS_LIMITE = 4;
        const MATERIAL_VIDEOS_LIMITE = 6;

        public static function dashboardDefaults()
        {
                return array(
                        'dashboard_titulo' => 'Programe-se',
                        'dashboard_carrossel_imagens' => '',
                        'dashboard_aviso_texto' => 'Use este espaco para comunicados importantes, instrucoes e orientacoes do evento.'
                );
        }

        public static function materialDefaults()
        {
                return array(
                        'material_titulo' => 'Material de apoio e videos',
                        'material_video_principal_link' => '',
                        'material_videos_secundarios' => "Video 1 | \nVideo 2 | \nVideo 3 | \nVideo 4 | \nVideo 5 | \nVideo 6 | ",
                        'material_arquivos' => "Arquivo 1 | \nArquivo 2 | \nArquivo 3 | \nEdital | ",
                        'material_links_lista' => "Titulo do link | https://"
                );
        }

        public static function helpDefaults()
        {
                return array(
                        'help_titulo' => 'Cadastro de Projetos',
                        'help_subtitulo' => 'Orientacoes para preencher corretamente o formulario e evitar problemas no envio.',
                        'help_conteudo' => "O cadastro do projeto deve ser preenchido com atencao, contemplando todas as informacoes solicitadas ao longo do formulario.\n\nPreencha os dados principais do projeto, como nome, datas, justificativa, objetivos e ODS, sempre de acordo com a atuacao e os resultados efetivamente realizados.\n\nInclua as evidencias qualitativas e quantitativas em seus respectivos blocos, respeitando o limite de ate 5 arquivos por area.\n\nRevise os campos antes de submeter, pois apos o envio para analise o projeto nao podera mais ser editado.\n\nQuando houver campos sobre itens, pessoas, parceiros ou outros dados quantitativos, informe valores reais e coerentes com as evidencias anexadas.",
                        'help_contato_nome' => 'Henrique Nascimento',
                        'help_contato_whatsapp' => '44997399515'
                );
        }

        public static function busca()
        {
                $db = Zend_Registry::get('db');

                $select = "select * from eventos_config a where id_config = 1";
                $registros = $db->fetchAll($select);

                if (!count($registros)) {
                        return false;
                }

                return $registros[0];
        }

        public static function dashboard()
        {
                $config = self::busca();
                $defaults = self::dashboardDefaults();

                if (!$config || !is_array($config)) {
                        return $defaults;
                }

                return array_merge($defaults, array_intersect_key($config, $defaults));
        }

        public static function material()
        {
                $config = self::busca();
                $defaults = self::materialDefaults();

                if (!$config || !is_array($config)) {
                        return $defaults;
                }

                return array_merge($defaults, array_intersect_key($config, $defaults));
        }

        public static function help()
        {
                $config = self::busca();
                $defaults = self::helpDefaults();

                if (!$config || !is_array($config)) {
                        return $defaults;
                }

                return array_merge($defaults, array_intersect_key($config, $defaults));
        }

        private static function validarTexto($valor, $rotulo, $limite, $obrigatorio = false)
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

        private static function dashboardUploadDir()
        {
                return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . self::DASHBOARD_UPLOAD_DIR;
        }

        private static function materialUploadDir()
        {
                return dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, self::MATERIAL_UPLOAD_DIR);
        }

        private static function parseLinhasTituloUrl($texto, $limite = null)
        {
                $itens = array();
                $linhas = preg_split('/\r\n|\r|\n/', (string) $texto);

                foreach ($linhas as $linha) {
                        $linha = trim((string) $linha);
                        if ($linha === '') {
                                continue;
                        }

                        $partes = array_map('trim', explode('|', $linha, 2));
                        $itens[] = array(
                                'titulo' => $partes[0] !== '' ? $partes[0] : 'Item',
                                'url' => isset($partes[1]) ? trim((string) $partes[1]) : ''
                        );

                        if ($limite !== null && count($itens) >= $limite) {
                                break;
                        }
                }

                return $itens;
        }

        private static function normalizarArquivos($arquivos)
        {
                $normalizados = array();

                if (!isset($arquivos['name']) || !is_array($arquivos['name'])) {
                        return $normalizados;
                }

                foreach ($arquivos['name'] as $indice => $nome) {
                        if (($arquivos['error'][$indice] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                                continue;
                        }

                        $normalizados[] = array(
                                'name' => $nome,
                                'type' => $arquivos['type'][$indice] ?? null,
                                'tmp_name' => $arquivos['tmp_name'][$indice] ?? null,
                                'error' => $arquivos['error'][$indice] ?? UPLOAD_ERR_NO_FILE,
                                'size' => $arquivos['size'][$indice] ?? 0
                        );
                }

                return $normalizados;
        }

        private static function validarImagemDashboard($arquivo)
        {
                if (!$arquivo || !isset($arquivo['tmp_name']) || !is_uploaded_file($arquivo['tmp_name'])) {
                        self::$erro = 'Arquivo de imagem invalido.';
                        return false;
                }

                if (($arquivo['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                        self::$erro = 'Falha ao enviar uma das imagens do carrossel.';
                        return false;
                }

                if (($arquivo['size'] ?? 0) > 10 * 1024 * 1024) {
                        self::$erro = 'Cada imagem do carrossel deve ter no maximo 10 MB.';
                        return false;
                }

                $extensao = strtolower(pathinfo($arquivo['name'] ?? '', PATHINFO_EXTENSION));
                $permitidas = array('png', 'jpg', 'jpeg', 'webp');
                if (!in_array($extensao, $permitidas, true)) {
                        self::$erro = 'Envie imagens do carrossel em PNG, JPG, JPEG ou WEBP.';
                        return false;
                }

                $mime = mime_content_type($arquivo['tmp_name']);
                $mimesPermitidos = array('image/png', 'image/jpeg', 'image/webp');
                if ($mime === false || !in_array($mime, $mimesPermitidos, true)) {
                        self::$erro = 'Tipo de imagem do carrossel nao permitido.';
                        return false;
                }

                return $extensao;
        }

        private static function salvarImagensDashboard($arquivos)
        {
                $arquivos = self::normalizarArquivos($arquivos);
                if (!count($arquivos)) {
                        return array();
                }

                $diretorio = self::dashboardUploadDir();
                if (!is_dir($diretorio) && !mkdir($diretorio, 0775, true)) {
                        self::$erro = 'Nao foi possivel preparar a pasta de upload do dashboard.';
                        return false;
                }

                $salvas = array();
                foreach ($arquivos as $arquivo) {
                        $extensao = self::validarImagemDashboard($arquivo);
                        if ($extensao === false) {
                                return false;
                        }

                        $nomeFisico = 'dashboard_' . sha1(uniqid((string) mt_rand(), true) . '_' . ($arquivo['name'] ?? 'imagem')) . '.' . $extensao;
                        $destino = $diretorio . DIRECTORY_SEPARATOR . $nomeFisico;

                        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
                                self::$erro = 'Nao foi possivel salvar uma das imagens do carrossel.';
                                return false;
                        }

                        $salvas[] = '../../download.php?arquivo=' . rawurlencode(self::DASHBOARD_UPLOAD_DIR . '/' . $nomeFisico);
                }

                return $salvas;
        }

        private static function removerImagemDashboardArquivo($url)
        {
                $url = trim((string) $url);
                if ($url === '') {
                        return;
                }

                $prefixo = '../../download.php?arquivo=';
                if (strpos($url, $prefixo) !== 0) {
                        return;
                }

                $arquivo = rawurldecode(substr($url, strlen($prefixo)));
                if (strpos($arquivo, self::DASHBOARD_UPLOAD_DIR . '/') !== 0) {
                        return;
                }

                $caminho = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $arquivo);
                if (is_file($caminho)) {
                        @unlink($caminho);
                }
        }

        private static function removerArquivoInterno($url, $prefixoDiretorio = null)
        {
                $url = trim((string) $url);
                if ($url === '') {
                        return;
                }

                $prefixo = '../../download.php?arquivo=';
                if (strpos($url, $prefixo) !== 0) {
                        return;
                }

                $arquivo = rawurldecode(substr($url, strlen($prefixo)));
                if ($prefixoDiretorio !== null && strpos($arquivo, $prefixoDiretorio . '/') !== 0) {
                        return;
                }

                $caminho = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $arquivo);
                if (is_file($caminho)) {
                        @unlink($caminho);
                }
        }

        private static function validarArquivoMaterial($arquivo)
        {
                if (!$arquivo || !isset($arquivo['tmp_name']) || !is_uploaded_file($arquivo['tmp_name'])) {
                        self::$erro = 'Arquivo de material invalido.';
                        return false;
                }

                if (($arquivo['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                        self::$erro = 'Falha ao enviar um dos arquivos de material.';
                        return false;
                }

                if (($arquivo['size'] ?? 0) > 100 * 1024 * 1024) {
                        self::$erro = 'Cada arquivo de material deve ter no maximo 100 MB.';
                        return false;
                }

                $extensao = strtolower(pathinfo((string) ($arquivo['name'] ?? ''), PATHINFO_EXTENSION));
                $permitidas = array('pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'mp4', 'mov', 'avi', 'wmv', 'mp3', 'wav', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'zip', 'rar');
                if (!in_array($extensao, $permitidas, true)) {
                        self::$erro = 'Tipo de arquivo de material nao permitido.';
                        return false;
                }

                return $extensao;
        }

        private static function salvarArquivoMaterial($arquivo)
        {
                $extensao = self::validarArquivoMaterial($arquivo);
                if ($extensao === false) {
                        return false;
                }

                $diretorio = self::materialUploadDir();
                if (!is_dir($diretorio) && !mkdir($diretorio, 0775, true)) {
                        self::$erro = 'Nao foi possivel preparar a pasta de upload dos materiais.';
                        return false;
                }

                $nomeFisico = 'material_' . sha1(uniqid((string) mt_rand(), true) . '_' . ($arquivo['name'] ?? 'arquivo')) . '.' . $extensao;
                $destino = $diretorio . DIRECTORY_SEPARATOR . $nomeFisico;
                if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
                        self::$erro = 'Nao foi possivel salvar um dos arquivos de material.';
                        return false;
                }

                return '../../download.php?arquivo=' . rawurlencode(self::MATERIAL_UPLOAD_DIR . '/' . $nomeFisico);
        }

        public static function salvarDashboard($campos, $arquivos = null)
        {
                $db = Zend_Registry::get('db');
                $configAtual = self::dashboard();

                $titulo = self::validarTexto($campos['dashboard_titulo'] ?? '', 'o titulo principal do dashboard', 150, true);
                if ($titulo === false) {
                        return false;
                }

                $carrosselImagens = trim((string) ($campos['dashboard_carrossel_imagens'] ?? ''));
                $imagensAtuais = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $carrosselImagens))));
                $novasImagens = $arquivos ? self::salvarImagensDashboard($arquivos) : array();
                if ($novasImagens === false) {
                        return false;
                }
                $imagensCompletas = array_values(array_unique(array_merge($imagensAtuais, $novasImagens)));

                $imagensAnteriores = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($configAtual['dashboard_carrossel_imagens'] ?? '')))));
                $imagensRemovidas = array_diff($imagensAnteriores, $imagensCompletas);
                foreach ($imagensRemovidas as $imagemRemovida) {
                        self::removerImagemDashboardArquivo($imagemRemovida);
                }

                $data = array(
                        'dashboard_titulo' => $titulo,
                        'dashboard_carrossel_imagens' => count($imagensCompletas) ? implode("\n", $imagensCompletas) : null,
                        'dashboard_aviso_texto' => trim((string) ($campos['dashboard_aviso_texto'] ?? '')) ?: null
                );

                try {
                        $db->update('eventos_config', $data, 'id_config = 1');
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar as configuracoes do dashboard.';
                        return false;
                }

                return true;
        }

        private static function montarItensMaterialUpload($titulos, $linksAtuais, $arquivos, $limite, $rotuloItem)
        {
                $itensFinais = array();

                for ($i = 0; $i < $limite; $i++) {
                        $tituloItem = trim((string) ($titulos[$i] ?? ''));
                        $linkAtual = trim((string) ($linksAtuais[$i] ?? ''));
                        $arquivoNovo = null;

                        if (isset($arquivos['name'][$i]) && ($arquivos['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                                $arquivoNovo = array(
                                        'name' => $arquivos['name'][$i] ?? null,
                                        'type' => $arquivos['type'][$i] ?? null,
                                        'tmp_name' => $arquivos['tmp_name'][$i] ?? null,
                                        'error' => $arquivos['error'][$i] ?? UPLOAD_ERR_NO_FILE,
                                        'size' => $arquivos['size'][$i] ?? 0
                                );
                        }

                        if ($tituloItem === '' && !$arquivoNovo) {
                                continue;
                        }

                        if ($tituloItem === '') {
                                self::$erro = 'Informe o nome do ' . $rotuloItem . ' antes de enviar o arquivo.';
                                return false;
                        }

                        $linkFinal = $linkAtual;
                        if ($arquivoNovo) {
                                $linkFinal = self::salvarArquivoMaterial($arquivoNovo);
                                if ($linkFinal === false) {
                                        return false;
                                }

                                if ($linkAtual !== '') {
                                        self::removerArquivoInterno($linkAtual, self::MATERIAL_UPLOAD_DIR);
                                }
                        }

                        if ($linkFinal === '') {
                                self::$erro = 'Envie um arquivo para o item "' . $tituloItem . '".';
                                return false;
                        }

                        $itensFinais[] = $tituloItem . ' | ' . $linkFinal;
                }

                return $itensFinais;
        }

        public static function salvarMaterial($campos, $arquivos = null)
        {
                $db = Zend_Registry::get('db');
                $configAtual = self::material();

                $titulo = self::validarTexto($campos['material_titulo'] ?? '', 'o titulo da tela de material', 150, true);
                if ($titulo === false) {
                        return false;
                }

                $linkAtualVideoPrincipal = trim((string) ($campos['material_video_principal_atual'] ?? ''));
                $arquivoNovoVideoPrincipal = null;
                if (isset($arquivos['material_video_principal_upload']) && ($arquivos['material_video_principal_upload']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                        $arquivoNovoVideoPrincipal = $arquivos['material_video_principal_upload'];
                }

                $linkVideoPrincipalFinal = $linkAtualVideoPrincipal;
                if ($arquivoNovoVideoPrincipal) {
                        $linkVideoPrincipalFinal = self::salvarArquivoMaterial($arquivoNovoVideoPrincipal);
                        if ($linkVideoPrincipalFinal === false) {
                                return false;
                        }

                        if ($linkAtualVideoPrincipal !== '') {
                                self::removerArquivoInterno($linkAtualVideoPrincipal, self::MATERIAL_UPLOAD_DIR);
                        }
                }

                $titulosVideos = isset($campos['material_videos_secundarios_titulo']) && is_array($campos['material_videos_secundarios_titulo']) ? $campos['material_videos_secundarios_titulo'] : array();
                $linksAtuaisVideos = isset($campos['material_videos_secundarios_atual']) && is_array($campos['material_videos_secundarios_atual']) ? $campos['material_videos_secundarios_atual'] : array();
                $videosAtuaisConfig = self::parseLinhasTituloUrl($configAtual['material_videos_secundarios'] ?? '', self::MATERIAL_VIDEOS_LIMITE);
                $videosFinais = self::montarItensMaterialUpload($titulosVideos, $linksAtuaisVideos, $arquivos['material_videos_secundarios_upload'] ?? array(), self::MATERIAL_VIDEOS_LIMITE, 'video');
                if ($videosFinais === false) {
                        return false;
                }

                $titulosArquivos = isset($campos['material_arquivos_titulo']) && is_array($campos['material_arquivos_titulo']) ? $campos['material_arquivos_titulo'] : array();
                $linksAtuaisArquivos = isset($campos['material_arquivos_atual']) && is_array($campos['material_arquivos_atual']) ? $campos['material_arquivos_atual'] : array();
                $arquivosAtuaisConfig = self::parseLinhasTituloUrl($configAtual['material_arquivos'] ?? '', self::MATERIAL_ARQUIVOS_LIMITE);
                $arquivosFinais = self::montarItensMaterialUpload($titulosArquivos, $linksAtuaisArquivos, $arquivos['material_arquivos_upload'] ?? array(), self::MATERIAL_ARQUIVOS_LIMITE, 'arquivo de material');
                if ($arquivosFinais === false) {
                        return false;
                }

                $videosAnteriores = array();
                foreach ($videosAtuaisConfig as $videoAtual) {
                        $urlAtual = trim((string) ($videoAtual['url'] ?? ''));
                        if ($urlAtual !== '') {
                                $videosAnteriores[] = $urlAtual;
                        }
                }

                $videosMantidos = array();
                foreach ($videosFinais as $videoFinal) {
                        $partesVideoFinal = array_map('trim', explode('|', $videoFinal, 2));
                        if (!empty($partesVideoFinal[1])) {
                                $videosMantidos[] = $partesVideoFinal[1];
                        }
                }

                $videosRemovidos = array_diff($videosAnteriores, $videosMantidos);
                foreach ($videosRemovidos as $videoRemovido) {
                        self::removerArquivoInterno($videoRemovido, self::MATERIAL_UPLOAD_DIR);
                }

                $arquivosAnteriores = array();
                foreach ($arquivosAtuaisConfig as $arquivoAtual) {
                        $urlAtual = trim((string) ($arquivoAtual['url'] ?? ''));
                        if ($urlAtual !== '') {
                                $arquivosAnteriores[] = $urlAtual;
                        }
                }

                $arquivosMantidos = array();
                foreach ($arquivosFinais as $arquivoFinal) {
                        $partesArquivoFinal = array_map('trim', explode('|', $arquivoFinal, 2));
                        if (!empty($partesArquivoFinal[1])) {
                                $arquivosMantidos[] = $partesArquivoFinal[1];
                        }
                }

                $arquivosRemovidos = array_diff($arquivosAnteriores, $arquivosMantidos);
                foreach ($arquivosRemovidos as $arquivoRemovido) {
                        self::removerArquivoInterno($arquivoRemovido, self::MATERIAL_UPLOAD_DIR);
                }

                $data = array(
                        'material_titulo' => $titulo,
                        'material_video_principal_link' => $linkVideoPrincipalFinal !== '' ? $linkVideoPrincipalFinal : null,
                        'material_videos_secundarios' => count($videosFinais) ? implode("\n", $videosFinais) : null,
                        'material_arquivos' => count($arquivosFinais) ? implode("\n", $arquivosFinais) : null,
                        'material_links_lista' => trim((string) ($campos['material_links_lista'] ?? '')) ?: null
                );

                try {
                        $db->update('eventos_config', $data, 'id_config = 1');
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar as configuracoes da tela de material.';
                        return false;
                }

                return true;
        }

        public static function salvarHelp($campos)
        {
                $db = Zend_Registry::get('db');

                $titulo = self::validarTexto($campos['help_titulo'] ?? '', 'o titulo da pagina de help', 150, true);
                if ($titulo === false) {
                        return false;
                }

                $subtitulo = self::validarTexto($campos['help_subtitulo'] ?? '', 'o subtitulo da pagina de help', 255, false);
                if ($subtitulo === false) {
                        return false;
                }

                $conteudo = trim((string) ($campos['help_conteudo'] ?? ''));
                if ($conteudo === '') {
                        self::$erro = 'Informe o conteudo da pagina de help.';
                        return false;
                }

                $contatoNome = self::validarTexto($campos['help_contato_nome'] ?? '', 'o nome do contato da pagina de help', 150, true);
                if ($contatoNome === false) {
                        return false;
                }

                $contatoWhatsapp = preg_replace('/\D+/', '', (string) ($campos['help_contato_whatsapp'] ?? ''));
                if ($contatoWhatsapp === '' || strlen($contatoWhatsapp) < 10 || strlen($contatoWhatsapp) > 13) {
                        self::$erro = 'Informe um WhatsApp valido para a pagina de help.';
                        return false;
                }

                $data = array(
                        'help_titulo' => $titulo,
                        'help_subtitulo' => $subtitulo !== '' ? $subtitulo : null,
                        'help_conteudo' => $conteudo,
                        'help_contato_nome' => $contatoNome,
                        'help_contato_whatsapp' => $contatoWhatsapp
                );

                try {
                        $db->update('eventos_config', $data, 'id_config = 1');
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar as configuracoes da pagina de help.';
                        return false;
                }

                return true;
        }
}
