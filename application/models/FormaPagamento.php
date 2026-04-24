<?php
class FormaPagamento
{
        public static $erro;
        const MAX_DESCRICAO = 120;

        public static function listar($apenasAtivas = false)
        {
                $db = Zend_Registry::get('db');
                $where = $apenasAtivas ? ' where ativo = true ' : '';

                $select = "select id_forma_pagamento,
                                  descricao,
                                  ativo,
                                  created_at
                             from eventos_forma_pagamento
                             $where
                            order by ativo desc, descricao asc";

                $registros = $db->fetchAll($select);
                return is_array($registros) ? $registros : [];
        }

        public static function buscaId($id_forma_pagamento)
        {
                $db = Zend_Registry::get('db');
                $id_forma_pagamento = (int) $id_forma_pagamento;

                if ($id_forma_pagamento <= 0) {
                        return false;
                }

                $select = "select id_forma_pagamento,
                                  descricao,
                                  ativo,
                                  created_at
                             from eventos_forma_pagamento
                            where id_forma_pagamento = " . $db->quote($id_forma_pagamento);

                $registro = $db->fetchRow($select);
                return $registro ?: false;
        }

        public static function salvar($campos)
        {
                $db = Zend_Registry::get('db');
                $id = isset($campos['id_forma_pagamento']) ? (int) $campos['id_forma_pagamento'] : 0;
                $descricao = trim((string) ($campos['descricao'] ?? ''));
                $ativo = !empty($campos['ativo']);

                if ($descricao === '') {
                        self::$erro = 'Informe a descricao da forma de pagamento.';
                        return false;
                }

                if (strlen($descricao) > self::MAX_DESCRICAO) {
                        self::$erro = 'A descricao da forma de pagamento deve ter no maximo ' . self::MAX_DESCRICAO . ' caracteres.';
                        return false;
                }

                try {
                        if ($id > 0) {
                                $registro = self::buscaId($id);
                                if (!$registro) {
                                        self::$erro = 'Forma de pagamento nao encontrada.';
                                        return false;
                                }

                                $where = $db->quoteInto('id_forma_pagamento = ?', $id);
                                $db->update('eventos_forma_pagamento', [
                                        'descricao' => $descricao,
                                        'ativo' => $ativo
                                ], $where);

                                return $id;
                        }

                        $db->insert('eventos_forma_pagamento', [
                                'descricao' => $descricao,
                                'ativo' => $ativo
                        ]);

                        return (int) $db->fetchOne("select currval(pg_get_serial_sequence('eventos_forma_pagamento', 'id_forma_pagamento'))");
                } catch (Exception $e) {
                        self::$erro = 'Nao foi possivel salvar a forma de pagamento.';
                        return false;
                }
        }
}
