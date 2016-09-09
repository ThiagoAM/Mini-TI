<?php
	include 'php/funcoes.php';
	include 'php/constantes.php';
	session_start();

    $desejaExcluir = $msgErroSenha = "";
	if (!verificaSeSessaoExiste()) {
		$msgErroSessao = "Faça login para acessar o sistema!";
	} else {
		$nome = $_SESSION["nome"];
		$email = $_SESSION["email"];
		$senha = $_SESSION["senha"];
		$telefone = $_SESSION["telefone"];
		$endereco = $_SESSION["endereco"];

        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            if (!empty($_GET["desejaExcluir"])) {
                if (trataEntrada($_GET["desejaExcluir"]) === "sim")  {
                    $desejaExcluir = "sim";
                }
            }
        }

		if ($_SERVER["REQUEST_METHOD"] == "POST") {

			$desejaExcluir = "sim";

			if (empty($_POST["senha"])) {
				$msgErroSenha = "Digite a sua senha!";
			} else {
				$senha = trataEntrada($_POST["senha"]);
				if (!verificaSeSenhaEValida($senha)) {
					$msgErroSenha = "Senha incorreta!";
				} else {
					if (!verificaSeEmailESenhaEstaoCorretos($_SESSION["email"], $senha)) {
						$msgErroSenha = "Senha incorreta!";
					} else {
						if (!deletaCliente($_SESSION["idCliente"], $_SESSION["email"])) {
							terminaSessao();
							$msgErroSessao = "Erro no servidor, volte mais tarde!";
						} else {
							terminaSessao();
							$mensagemGeral = "Sua conta foi excluída com sucesso!";
							mudaDePagina("index.php?mensagemGeral=$mensagemGeral");
						}
					}
				}
			}
		}
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Deletar Conta Mini TI!</title>
	<link href="css/estilos_gerais.css" rel="stylesheet" type="text/css">
</head>
<body>
		<?php
		if (verificaSeSessaoExiste()) {
		?>
            <div class='caixaInterface sistema'>

                <div class='cabecalhoSistema deletarConta'>
                    <p class='bemVindo'>Excluir conta</p>
                    <a href='editarConta.php'><button type='button' class='botao sair'>Voltar</button></a>
                </div>
                <?php
                if ($desejaExcluir === "sim") {
                ?>
					<div class='oqDesejaFazer'>
						<p>Digite sua senha para excluir a sua conta:</p>
					</div>

                    <div class='caixaInterface deletarConta senha'>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                            <input type="password" name="senha" class="campoDeEntrada" placeholder="senha" required>
							<?php verificaMsgECriaBalao($msgErroSenha, "balaoMsg erro");?>
                            <input type="submit" name="botaoEnviarDados" value="Excluir Conta">
                        </form>
                    </div>
                <?php
                } else {
                ?>
                    <div class='caixaInterface deletarConta'>
                        <p> Tem certeza que deseja exluir sua conta? </p>
                        <br><a href="editarConta.php"><button type="button" class="botao botaoCadastrese">Não</button></a>
                        <br><a href="deletarConta.php?desejaExcluir=sim"><button type="button" class="botao amareloEscuro">Sim</button></a>
                    </div>
                <?php
                }
                ?>

            </div>



		<?php
		} else {
			apresentaTelaDeErro($msgErroSessao);
		}
		?>
</body>
</html>
