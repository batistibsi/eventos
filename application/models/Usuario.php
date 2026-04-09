<?php
class Usuario
{
	public static $erro;
	const MAX_NOME = 80;
	const MAX_EMAIL = 80;

	public function __construct() {}

	public static function buscaId($id_usuario)
	{

		$db = Zend_Registry::get('db');

		$select = "select * from eventos_usuario where id_usuario = " . $id_usuario;

		$registros = $db->fetchAll($select);

		if (count($registros) == 0) {
			Usuario::$erro = "Usuário não encontrado.";
			return false;
		} else {
			return $registros[0];
		}
	}

	public static function buscaEmail($email)
	{

		$db = Zend_Registry::get('db');

		$select = "select * from eventos_usuario where email = '" . $email . "' and ativo";

		$registros = $db->fetchAll($select);

		if (count($registros) == 0) {
			Usuario::$erro = "Usuário não encontrado.";
			return false;
		} else {
			return $registros[0];
		}
	}

	public static function alterarSenha($id_usuario, $senhaAtual, $novaSenha)
	{

		$hashAtual = Usuario::buscaId($id_usuario);
		$hashAtual = $hashAtual['senha'];

		if ($senhaAtual != $hashAtual) {
			Usuario::$erro = 'A senha atual informada não confere!';
			return false;
		}

		$db = Zend_Registry::get('db');

		$data = array(
			"senha" => $novaSenha
		);

		$db->update("eventos_usuario", $data, "id_usuario = '" . $id_usuario . "'");

		return true;
	}

	public static function uniqueEmail($id, $email)
	{
		$db = Zend_Registry::get('db');

		$select = "select * from eventos_usuario where id_usuario <> " . $id . " and email = '" . $email . "' and ativo";

		$registros = $db->fetchAll($select);

		if (count($registros) == 0) return true;

		return false;
	}

	public static function logLogin($id_usuario)
	{

		$db = Zend_Registry::get('db');

		$data = array(
			"id_usuario" => $id_usuario
		);

		$db->insert("eventos_login", $data);

		return true;
	}

	public static function insert($email, $nome, $idPerfil, $ativo, $senha, $confirmSenha)
	{

		if ($ativo) {
			$idPerfil = (int) $idPerfil;

			if (strlen($email) < 3 || strlen($email) > self::MAX_EMAIL) {
				Usuario::$erro = 'Email inválido!';
				return false;
			}

			if (strlen($nome) > self::MAX_NOME || strlen($nome) < 1) {
				Usuario::$erro = 'Nome inválido!';
				return false;
			}

			if (!Usuario::uniqueEmail(0, $email)) {
				Usuario::$erro = 'Já existe um registro com o email: ' . $email . '.';
				return false;
			}

			if (!$idPerfil) {
				Usuario::$erro = 'Perfil inválido!';
				return false;
			}

			if ($senha != "" && $confirmSenha != $senha) {
				Usuario::$erro = 'Senhas informadas não conferem!';
				return false;
			}

			if ($senha == "") {
				Usuario::$erro = 'Informe uma senha para salvar!';
				return false;
			}
		}

		$db = Zend_Registry::get('db');


		$data = array(
			"email" => $email,
			"nome" => $nome,
			"senha" => $senha,
			"id_perfil" => $idPerfil,
			"ativo" => $ativo
		);

		$db->insert("eventos_usuario", $data);

		$link = $_SERVER['HTTP_HOST'];

		$msg = '<p>Parabéns, seu cadastro na plataforma EVENTOS foi concluído com sucesso.</p>'
			. '<p>Acesse o sistema clicando no link abaixo</p>'
			. '<p><a href="' . $link . '">' . $link . '</a></p>';

		Email::enviar($email, 'Cadastro de acesso à Plataforma de GESTÃO DE CERTIFICAÇÃO IMPACTACIM confirmado', $msg);

		return true;
	}

	public static function update($email, $nome, $idPerfil, $ativo, $senha, $id_usuario, $confirmSenha)
	{

		if (strlen($email) < 3 || strlen($email) > self::MAX_EMAIL) {
			Usuario::$erro = 'Email inválido!';
			return false;
		}

		if (strlen($nome) > self::MAX_NOME || strlen($nome) < 1) {
			Usuario::$erro = 'Nome inválido!';
			return false;
		}

		if (!Usuario::uniqueEmail($id_usuario, $email)) {
			Usuario::$erro = 'Já existe um registro com o email: ' . $email . '.';
			return false;
		}

		if (!$idPerfil) {
			Usuario::$erro = 'Perfil inválido!';
			return false;
		}


		if ($senha != "" && $confirmSenha != $senha) {
			Usuario::$erro = 'Senhas informadas não conferem!';
			return false;
		}

		$db = Zend_Registry::get('db');

		$data = array(
			"email" => $email,
			"nome" => $nome,
			"id_perfil" => $idPerfil,
			"ativo" => $ativo
		);

		if ($senha != "") {
			$data['senha'] = $senha;
			self::emailSenha($email, $senha);
		}

		$db->update("eventos_usuario", $data, "id_usuario = " . $id_usuario);

		return true;
	}

	public static function emailSenha($email, $senha)
	{
		$msg = '<p>ATENÇÃO!</p>'
			. '<p>Sua nova Senha de acesso à Plataforma de GESTÃO DE CERTIFICAÇÃO IMPACTACIM é:</p>'
			. '<p><strong>' . $senha . '</strong></p>';

		Email::enviar($email, 'Mudança de senha', $msg);
	}

	public static function desativar($id_usuario)
	{

		if ($id_usuario == 1) {
			Usuario::$erro = 'Este usuário não pode ser removido!';
			return false;
		}

		$db = Zend_Registry::get('db');

		$data = array(
			"ativo" => false
		);

		$db->update("eventos_usuario", $data, "id_usuario = " . $id_usuario);

		return true;
	}

	public static function lista()
	{

		$db = Zend_Registry::get('db');

		$select = "select eventos_usuario.*,
					eventos_perfil.descricao as descricao_perfil
				  from eventos_usuario
				  left join eventos_perfil on eventos_usuario.id_perfil = eventos_perfil.id_perfil
				   where eventos_usuario.ativo
				   and eventos_usuario.id_usuario <> 1
				  order by eventos_usuario.nome";

		$retorno = $db->fetchAll($select);

		return $retorno;
	}

	public static function logins()
	{

		$db = Zend_Registry::get('db');

		$select = "select a.nome,
					a.email,
					p.descricao as descricao_perfil,
					l.*
				  from eventos_usuario a
				  inner join eventos_login l on l.id_usuario = a.id_usuario				  
				  left join eventos_perfil p on a.id_perfil = p.id_perfil
				  where a.id_usuario <> 1
				  order by a.nome";

		$retorno = $db->fetchAll($select);

		return $retorno;
	}

	public static function comboPerfil()
	{
		$db = Zend_Registry::get('db');

		$select = "select a.* 
					from eventos_perfil a
					order by a.id_perfil";

		$retorno = $db->fetchAll($select);

		return $retorno;
	}
}
