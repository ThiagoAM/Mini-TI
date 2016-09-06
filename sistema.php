<?php
	include 'php/funcoes.php';
	include 'php/constantes.php';
	session_start();
?>

<!DOCTYPE html>
<html>
<head>	
	<meta charset="utf-8">
	<title>Bem-Vindo(a) ao sistema Mini TI!</title>
	<link href="css/estilos_gerais.css" rel="stylesheet" type="text/css">
</head>
<body>

	<?php
		if (verificaSeSessaoExiste()) {			
			apresentaOpcoesBasicasDoSistema();
		} else {					
			apresentaTelaDeErro();
		}
	?>
</body>
</html>

<?php
	function apresentaOpcoesBasicasDoSistema() {
		echo "
			<div class='caixaInterface sistema'>
				<div class='cabecalhoSistema'>
					<p class='bemVindo'>Olá, ".obtemPrimeiraPalavra($_SESSION["nome"])."!</p>
					<a href='index.php?finalizaSessao=1'><button type='button' class='botao sair'>Sair</button></a>
				</div>
				<div class='abaInfoSistema'>
					<p>
					E-mail: ".$_SESSION['email']."".criaEspacos(6)."Telefone: ".$_SESSION['telefone']."".criaEspacos(6)."Endereço: ".$_SESSION["endereco"]."
					</p>						
				</div>
				<div class='oqDesejaFazer'>
					<p>O que deseja fazer ?</p>
				</div>
				<div class='caixaItensSistema'>
					<br><a href='editarConta.php'><button type='button' class='botaoItemOqDesejaFazer editarMinhaConta'>Editar Minha Conta</button></a>
					<br><a href='index.php'><button type='button' class='botaoItemOqDesejaFazer acessarOForum'>Acessar o Fórum</button></a>
				</div>
			</div>
		";
	}
?>






