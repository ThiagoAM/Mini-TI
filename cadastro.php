<?php
	include 'php/funcoes.php';
	include 'php/constantes.php';

	// Variáveis:
	$nome = $email = $senha = $confirmaSenha = $telefone = $endereco = "";
	$msgErroNome = $msgErroEmail = $msgErroSenha = $msgErroTelefone = $msgErroEndereco = "";

	// Verifica se método POST foi chamado:
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		// Verifica se 'nome' foi enviado pelo POST:
		if (empty($_POST["nome"])) {
			$msgErroNome = "Nome é requerido!";
		} else {
			$nome = trataEntrada($_POST["nome"]);

			if (!verificaSeNomeEValido($nome)) {
				$msgErroNome = "Nome deve conter apenas LETRAS e ESPAÇOS!";
			}
		}

		// Verifica se 'email' foi enviado pelo POST:
		if (empty($_POST["email"])) {
			$msgErroEmail = "E-mail é requerido!";
		} else {
			$email = trataEntrada($_POST["email"]);

			if (!verificaSeEmailEValido($email)) {
				$msgErroEmail = "Formato de e-mail inválido!";
			}

			$emailJaExiste = verificaSeEmailJaExiste($email);
			if ($emailJaExiste === true) {
				$msgErroEmail = "Este e-mail já foi cadastrado!";
			} else if ($emailJaExiste === null) {
				$msgErroNome = "Erro no servidor, tente novamente mais tarde!";
			}
		}

		// Verifica se 'senha' e 'confirmaSenha' foram enviadas pelo POST:
		if ((empty($_POST["senha"])) || (empty($_POST["confirmaSenha"]))) {
			$msgErroSenha = "Senhas são requeridas!";
		} else {
			$senha = trataEntrada($_POST["senha"]);
			$confirmaSenha = trataEntrada($_POST["confirmaSenha"]);

			// Verifica se ambas as senhas são iguais:
			if ($senha === $confirmaSenha) {
				if (!verificaSeSenhaEValida($senha)) {
					$msgErroSenha = "A senha deve ter no mínimo ".MIN_CHAR_SENHA." caracteres!";
				}
			} else {
				$msgErroSenha = "Ambas as senhas devem ser IDÊNTICAS!";
			}
		}

		$telefone = trataEntrada($_POST["telefone"]);
		if (!verificaSeTelefoneEValido($telefone)) {
			$msgErroTelefone = "Formato de telefone inválido!";
		}

		$endereco = trataEntrada($_POST["endereco"]);
		if (!verificaSeEnderecoEValido($endereco)) {
			$msgErroEndereco = "Número máximo de caracteres alcançado!";
		}


		// Grava na base de dados:
		if (verificaSeFormularioEValido($msgErroNome, $msgErroEmail, $msgErroSenha, $msgErroTelefone, $msgErroEndereco)) {

			if (insereDadosEmCliente($nome, $email, $senha, $telefone, $endereco)) {
				mudaDePagina("index.php?nome=$nome&email=$email");
			} else {
				$msgErroNome = "Erro no servidor, tente mais tarde!";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Cadastre-se!</title>
	<link href="css/estilos_gerais.css" rel="stylesheet" type="text/css">
</head>
<body>

<div class="caixaInterface cadastro">

	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

		<input type="text" name="nome" class="campoDeEntrada" placeholder="nome completo" value="<?php echo $nome;?>" maxlength="<?php echo MAX_CHAR_NOME;?>" required>
		<?php verificaMsgECriaBalao($msgErroNome, "balaoMsg erro");?>

		<input type="text" name="email" class="campoDeEntrada" placeholder="e-mail" value="<?php echo $email;?>" maxlength="<?php echo MAX_CHAR_EMAIL;?>" required>
		<?php verificaMsgECriaBalao($msgErroEmail, "balaoMsg erro");?>

		<input type="password" name="senha" class="campoDeEntrada" placeholder="senha" maxlength="<?php echo MAX_CHAR_SENHA;?>" required>
		<?php verificaMsgECriaBalao($msgErroSenha, "balaoMsg erro");?>
		<input type="password" name="confirmaSenha" class="campoDeEntrada" placeholder="confirme a senha" maxlength="<?php echo MAX_CHAR_SENHA;?>" required>

		<input type="text" name="telefone" class="campoDeEntrada" value="<?php echo $telefone;?>" placeholder="telefone" maxlength="<?php echo MAX_CHAR_TELEFONE;?>">
		<?php verificaMsgECriaBalao($msgErroTelefone, "balaoMsg erro");?>

		<input type="text" name="endereco" class="campoDeEntrada" value="<?php echo $endereco;?>" placeholder="endereço" maxlength="<?php echo MAX_CHAR_ENDERECO;?>">
		<?php verificaMsgECriaBalao($msgErroEndereco, "balaoMsg erro");?>

		<br><input type="submit" name="botaoEnviarDados" value="Cadastrar">

	</form>

	<a href="index.php"><button type="button" class="botao botaoVoltar">Voltar</button></a>

</div>


</body>
</html>
