<?php
	include 'php/funcoes.php';
	include 'php/constantes.php';
	$msgSucessoCadastro = $email = "";
	$msgErroEmail = $msgErroSenha = $msgErroLogin = $msgErroConexao = "";
	$msgFimDeSessao = "";
	if ($_SERVER["REQUEST_METHOD"] == "GET") {

		if (!empty($_GET["msgErroConexao"])) {
			$_msgErroConexao = trataEntrada($_GET["msgErroConexao"]);
		}


		if (!empty($_GET["nome"])) {
			$nome = trataEntrada($_GET["nome"]);
			$primeiraPalavra = (explode(' ',trim($nome)))[0];
			$nome = $primeiraPalavra;
			if (!verificaSeNomeEValido($nome)) {
				$nome = "";
			} else {
				$msgSucessoCadastro = "Parabéns, $nome! Você já possui um cadastro!";
			}
		}
		if (!empty($_GET["email"])) {
			$email = trataEntrada($_GET["email"]);

			if (!verificaSeEmailEValido($email)) {
				$email = "";
			}
		}
		if (!empty($_GET["finalizaSessao"])) {
			session_start();
			terminaSessao();
			$msgFimDeSessao = "Sessão finalizada com sucesso!";
		}
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Verifica se 'email' foi enviado pelo POST:
		if (empty($_POST["email"])) {
			$msgErroEmail = "E-mail é requerido!";
		} else {
			$email = trataEntrada($_POST["email"]);
			if (!verificaSeEmailEValido($email)) {
				$msgErroEmail = "Formato de e-mail inválido!";
			}
		}
		// Verifica se 'senha' foi enviada pelo POST:
		if (empty($_POST["senha"])) {
			$msgErroSenha = "Digite a sua senha!";
		} else {
			$senha = trataEntrada($_POST["senha"]);

			if (!verificaSeSenhaEValida($senha)) {
				$msgErroSenha = "Formato de senha inválido!";
			}
		}
		if (($msgErroEmail == "") && ($msgErroSenha == "")) {

			$fezLogin = tentaLoginComoCliente($email, $senha);

			if ($fezLogin === true) {
				// Sucesso no login:
				mudaDePagina("sistema.php");
				exit();
			} else if ($fezLogin === false) {
				// Falha no login:
				$msgErroLogin = "Falha ao tentar login, email e/ou senha inválidos!";
			} else if ($fezLogin === null) {
				// Falha no login:
				$msgErroLogin = "Não foi possível conectar ao servidor, volte mais tarde!";
			}

			/*if (tentaLoginComoCliente($email, $senha)) {
				// Sucesso no login:
				mudaDePagina("sistema.php");
				exit();
			} else {
				// Falha no login:
				$msgErroLogin = "Falha ao tentar login, email e/ou senha inválidos!";
			}*/
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Faça seu login!</title>
	<link href="css/estilos_gerais.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="caixaInterface login">
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<input type="text" name="email" class="campoDeEntrada" placeholder="e-mail" value="<?php echo $email;?>" required>
		<?php verificaMsgECriaBalao($msgSucessoCadastro, "balaoMsg");?>
		<?php verificaMsgECriaBalao($msgErroConexao, "balaoMsg");?>
		<?php verificaMsgECriaBalao($msgErroEmail, "balaoMsg erro");?>
		<?php verificaMsgECriaBalao($msgErroLogin, "balaoMsg erro");?>
		<?php verificaMsgECriaBalao($msgFimDeSessao, "balaoMsg");?>
		<input type="password" name="senha" class="campoDeEntrada" placeholder="senha" required>
		<?php verificaMsgECriaBalao($msgErroSenha, "balaoMsg erro");?>
		<br><input type="submit" name="botaoEnviarDados" value="Entrar">
	</form>
	<a href="cadastro.php"><button type="button" class="botao botaoCadastrese">Cadastre-se!</button></a>
</div>
</body>
</html>
