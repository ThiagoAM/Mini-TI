<?php

	// - - - - - - - - - - - - - MYSQL - - - - - - - - - - - - - - -

	function atualizaDadosDeCliente($nome, $email, $senha, $telefone, $endereco) {
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			return false;
		} else {
			// Sucesso na conexão:
			$id = $_SESSION["idCliente"];

			$comandoSQL = $conexao->prepare('UPDATE clientes SET nome=?, email=?, telefone=?, endereco=? WHERE idCliente=?');
			if (!$comandoSQL) {
				return false;
			}
			$comandoSQL->bind_param('sssss', $nome, $email, $telefone, $endereco, $_SESSION["idCliente"]);
			$comandoSQL->execute();
			$comandoSQL->close();
			mysqli_close($conexao);
			atribuiValoresDaSessao($_SESSION["idCliente"], $nome, $email, $_SESSION["senha"], $telefone, $endereco); // remover isso...
			return true;
		}
	}

	function insereDadosEmCliente($nome, $email, $senha, $telefone, $endereco) {
		// Cria uma conexão:
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			return false;
		} else {
			$comandoSQL = $conexao->prepare('INSERT INTO clientes (nome, email, senha, telefone, endereco) VALUES (?, ?, ?, ?, ?)');
			// Verifica o comando SQL:
			if (!$comandoSQL) {
				// Falha na criação do comando SQL:
				return false;
			}
			$senhaCriptografada = criptografarSenha($senha);
			$comandoSQL->bind_param('sssss', $nome, $email, $senhaCriptografada, $telefone, $endereco);
			$comandoSQL->execute();
			$comandoSQL->close();
			mysqli_close($conexao);
			return true;
		}
	}

	function criptografarSenha($senha) {
		return password_hash($senha, PASSWORD_DEFAULT);
	}

	function verificaSeSenhaEstaCorreta($senha, $senhaCriptografada) {
		if (password_verify($senha, $senhaCriptografada)) {
			return true;
		} else {
			return false;
		}
	}

	function tentaLoginComoCliente($email, $senha) {
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			return null;
		} else {
			// Sucesso na conexão:
			$comandoSQL = $conexao->prepare('SELECT * FROM clientes WHERE email = ?');
			if (!$comandoSQL) {
				return false;
			}
			$comandoSQL->bind_param('s', $email);
			$comandoSQL->execute();
			$resultadoSQL = $comandoSQL->get_result();
			if ($resultadoSQL->num_rows === 1) {
				$coluna = $resultadoSQL->fetch_assoc();
				if (verificaSeSenhaEstaCorreta($senha, $coluna["senha"])) {
					iniciaEAtribuiValoresSessao($coluna["idCliente"], $coluna["nome"], $coluna["email"], $coluna["senha"], $coluna["telefone"], $coluna["endereco"]);
					$comandoSQL->close();
					mysqli_close($conexao);
					return true;
				} else {
					$comandoSQL->close();
					mysqli_close($conexao);
					return false;
				}
			} else {
				$comandoSQL->close();
				mysqli_close($conexao);
				return false;
			}
		}
	}

	function verificaSeEmailJaExiste($email) {
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			return null;
		} else {
			// Sucesso na conexão:
			$comandoSQL = $conexao->prepare('SELECT * FROM clientes WHERE email = ?');
			if (!$comandoSQL) {
				return null;
			}
			$comandoSQL->bind_param('s', $email);
			$comandoSQL->execute();
			$resultadoSQL = $comandoSQL->get_result();
			if ($resultadoSQL->num_rows >= 1) {
				$comandoSQL->close();
				mysqli_close($conexao);
				return true;
			} else {
				$comandoSQL->close();
				mysqli_close($conexao);
				return false;
			}
		}
	}


	function verificaSeOutroEmailExiste($email, $idCliente) {
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			return null;
		} else {
			// Sucesso na conexão:
			$comandoSQL = $conexao->prepare('SELECT * FROM clientes WHERE email = ?');
			if (!$comandoSQL) {
				return null;
			}
			$comandoSQL->bind_param('s', $email);
			$comandoSQL->execute();
			$resultadoSQL = $comandoSQL->get_result();
			if ($resultadoSQL->num_rows == 1) {
				$coluna = $resultadoSQL->fetch_assoc();
				if ($coluna["idCliente"] == $idCliente) {
					$comandoSQL->close();
					mysqli_close($conexao);
					return false;
				} else {
					$comandoSQL->close();
					mysqli_close($conexao);
					return true;
				}
			} else {
				$comandoSQL->close();
				mysqli_close($conexao);
				return false;
			}
		}
	}


	// - - - - - - - - - - - OUTRAS FUNÇÕES - - - - - - - - - - - - - -


	function apresentaTelaDeErro($msg) {
		echo "
			<div class='caixaInterface erro'>
				<p>
				Acesso Negado.
				<br>".$msg."
				</p>
				<br><a href='index.php'><button type='button' class='botao botaoVoltarInterfaceErro'>Voltar</button></a>
			</div>
		";
	}


	function iniciaEAtribuiValoresSessao($idCliente, $nome, $email, $senha, $telefone, $endereco) {
		if (!verificaSeSessaoExiste()) {
			session_start();
			atribuiValoresDaSessao($idCliente, $nome, $email, $senha, $telefone, $endereco);
		} else {
			terminaSessao();
			iniciaEAtribuiValoresSessao($idCliente, $nome, $email, $senha, $telefone, $endereco);
		}
	}

	function atribuiValoresDaSessao($idCliente, $nome, $email, $senha, $telefone, $endereco) {
		$_SESSION["idCliente"] = $idCliente;
		$_SESSION["nome"] = $nome;
		$_SESSION["email"] = $email;
		$_SESSION["senha"] = $senha;
		$_SESSION["telefone"] = $telefone;
		$_SESSION["endereco"] = $endereco;
	}

	function terminaSessao() {

		if (verificaSeSessaoExiste() === true) {
			// Remove todas as variáveis da sessão:
			session_unset();
			// Destroi a sessão:
			session_destroy();
		}
	}

	function verificaSeSessaoExiste() {
		if ((!isset($_SESSION["nome"])) && (!isset($_SESSION["email"])) && (!isset($_SESSION["telefone"])) && (!isset($_SESSION["endereco"])) && (!isset($_SESSION["senha"]))) {
			return false;
		} else {
			return true;
		}
	}

	function criaEspacos($quantidade) {
		$contador = 0;
		$stringFinal = "";
		while ($contador < $quantidade) {
			$stringFinal .= "&nbsp;";
			$contador += 1;
		}
		return $stringFinal;
	}

	function mudaDePagina($link) {
		header("Location: $link");
	}

	function obtemPrimeiraPalavra($string) {
		return (explode(' ',trim($string)))[0];
	}

	function verificaSeFormularioEValido($msgErroNome, $msgErroEmail, $msgErroSenha, $msgErroTelefone, $msgErroEndereco) {
		return (($msgErroNome === "") && ($msgErroEmail === "") && ($msgErroSenha === "") && ($msgErroTelefone === "") && ($msgErroEndereco === ""));
	}

	function verificaSeFormularioSemSenhaEValido($msgErroNome, $msgErroEmail, $msgErroTelefone, $msgErroEndereco) {
		return (($msgErroNome === "") && ($msgErroEmail === "") && ($msgErroTelefone === "") && ($msgErroEndereco === ""));
	}


	function verificaSeSenhaEValida($senha) {
		return (strlen($senha) <= MAX_CHAR_SENHA);
	}


	function verificaSeEnderecoEValido($endereco) {
		return (strlen($endereco) <= MAX_CHAR_ENDERECO);
	}

	function verificaSeTelefoneEValido($telefone) {
		return ((preg_match("/^[,()0-9 -]*$/", $telefone)) && (strlen($telefone) <= MAX_CHAR_TELEFONE));
	}

	function verificaSeEmailEValido($email) {
		return ((filter_var($email, FILTER_VALIDATE_EMAIL)) && (strlen($email) <= MAX_CHAR_EMAIL));
	}


	function verificaSeNomeEValido($nome) {
		return ((preg_match("/^[a-zA-Z ]*$/", $nome)) && (strlen($nome) <= MAX_CHAR_NOME));
	}

	function verificaMsgECriaBalao($mensagem, $classe) {
		if ($mensagem !== "") {
			echo criaBalaoMsg($mensagem, $classe);
		}
	}

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	function trataEntrada($dado) {
		$dado = trim($dado);
		$dado = stripslashes($dado);
		$dado = htmlspecialchars($dado);
		return $dado;
	}

	function criaBalaoMsg($mensagem, $classe) {
		return "
		<span>
			<div class='".$classe."'>$mensagem</div>
		</span>
		";
	}



?>
