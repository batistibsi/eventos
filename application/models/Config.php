<?php
class Config
{
        public static $erro;
        const DASHBOARD_UPLOAD_DIR = 'dashboard';

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
                        'material_video_principal_titulo' => 'Video principal',
                        'material_video_principal_link' => '',
                        'material_videos_secundarios' => "Video 1 | \nVideo 2 | \nVideo 3 | \nVideo 4 | \nVideo 5 | \nVideo 6 | ",
                        'material_arquivos' => "Arquivo 1 | \nArquivo 2 | \nArquivo 3 | \nEdital | ",
                        'material_links_topo' => "3 pdf\nAlguns links",
                        'material_links_lista' => "Titulo do link | https://"
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

        public static function salvarMaterial($campos)
        {
                $db = Zend_Registry::get('db');

                $titulo = self::validarTexto($campos['material_titulo'] ?? '', 'o titulo da tela de material', 150, true);
                if ($titulo === false) {
                        return false;
                }

                $videoPrincipalTitulo = self::validarTexto($campos['material_video_principal_titulo'] ?? '', 'o titulo do video principal', 150, true);
                if ($videoPrincipalTitulo === false) {
                        return false;
                }

                $data = array(
                        'material_titulo' => $titulo,
                        'material_video_principal_titulo' => $videoPrincipalTitulo,
                        'material_video_principal_link' => trim((string) ($campos['material_video_principal_link'] ?? '')) ?: null,
                        'material_videos_secundarios' => trim((string) ($campos['material_videos_secundarios'] ?? '')) ?: null,
                        'material_arquivos' => trim((string) ($campos['material_arquivos'] ?? '')) ?: null,
                        'material_links_topo' => trim((string) ($campos['material_links_topo'] ?? '')) ?: null,
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
}
