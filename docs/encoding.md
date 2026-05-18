# Encoding

O projeto deve ser mantido em `UTF-8` do arquivo ao navegador.

## Diagnosticar

Para listar arquivos suspeitos:

```bash
php scripts/check-encoding.php
```

Para tentar reparar os casos mais comuns de mojibake e criar backup `.bak-encoding`:

```bash
php scripts/check-encoding.php . --write
```

Revise o diff antes de publicar. O reparo automatico ajuda bastante em casos como `UsuÃ¡rio` e `UsuÃƒÂ¡rio`, mas nao substitui revisao humana.

## Corrigir sem espalhar o problema

1. Corrija primeiro os arquivos do projeto.
2. Depois valide a conexao com o banco.
3. So por ultimo ajuste dados antigos gravados no banco, se houver.

Misturar essas etapas costuma gerar dupla conversao.

## Banco de dados

Antes de mexer em dados, confirme:

- charset e collation do banco, tabelas e colunas
- charset da conexao PHP com o MySQL
- se o dado errado esta salvo no banco ou apenas exibido errado

Consultas uteis no MySQL:

```sql
SHOW VARIABLES LIKE 'character_set_%';
SHOW VARIABLES LIKE 'collation_%';
SHOW CREATE TABLE nome_da_tabela;
```

Se a aplicacao estiver em UTF-8, a conexao com o banco tambem deve estar em UTF-8. Em bases novas, prefira `utf8mb4`.

## Prevenir recorrencia

- edite arquivos sempre em UTF-8
- mantenha o `.editorconfig`
- evite abrir e salvar arquivos em editores que trocam para ANSI/Windows-1252
- valide encoding no CI ou antes de deploy com `php scripts/check-encoding.php`
- ao importar CSV/TXT, confirme o encoding antes de gravar no banco
