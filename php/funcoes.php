<?php

	// - - - - - - - - - - - - - MYSQL - - - - - - - - - - - - - - - 

	function tentaLoginComoCliente($email, $senha) {		
		$conexao = mysqli_connect("".HOSPEDEIRO_BD.":".PORTA_BD."", USUARIO_BD, SENHA_BD, NOME_BD);
		// Verifica conexão:
		if (!$conexao) {
			// Falha na conexão:
			echo "A conexão com o banco de dados falhou. Erro: " . mysqli_connect_error();
			return false;
		} else {
			// Sucesso na conexão:										
			$comandoSQL = "SELECT * FROM clientes WHERE email = '". mysqli_real_escape_string($conexao, $email) ."' AND senha = '". mysqli_real_escape_string($conexao, $senha) ."'" ;
			$dadosSQL = mysqli_query($conexao,$comandoSQL);
			if (mysqli_num_rows($dadosSQL) === 1) {									
			    $coluna = mysqli_fetch_assoc($dadosSQL);
			    iniciaEAtribuiValoresSessao($coluna["nome"], $coluna["email"], $coluna["telefone"], $coluna["endereco"]);
				mysqli_close($conexao);
				return true;
			} else {				
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
			echo "A conexão com o banco de dados falhou. Erro: " . mysqli_connect_error();
			return null;
		} else {
			// Sucesso na conexão:
			$comandoSQL = "SELECT * FROM clientes WHERE email = '". mysqli_real_escape_string($conexao, $email) ."'";
			$dadosSQL = mysqli_query($conexao,$comandoSQL);
			if (mysqli_num_rows($dadosSQL) === 1) {	
				mysqli_close($conexao);									
				return true;
			} else {					
				mysqli_close($conexao);			
				return false;
			}			
		}
	}






	// - - - - - - - - - - - OUTRAS FUNÇÕES - - - - - - - - - - - - - -


	function iniciaEAtribuiValoresSessao($nome, $email, $telefone, $endereco) {
		if (!verificaSeSessaoExiste()) {
			session_start();			
			$_SESSION["nome"] = $nome;
			$_SESSION["email"] = $email;
			$_SESSION["telefone"] = $telefone;
			$_SESSION["endereco"] = $endereco;			
		} else {
			terminaSessao();			
			iniciaSessao($nome, $email, $telefone, $endereco);
		}		
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
		if ((!isset($_SESSION["nome"])) && (!isset($_SESSION["email"])) && (!isset($_SESSION["telefone"])) && (!isset($_SESSION["endereco"]))) {
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