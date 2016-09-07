<?php
    include 'php/funcoes.php';
    include 'php/constantes.php';
    session_start();
    if (!verificaSeSessaoExiste()) {
        $msgErroSessao = "Faça login para acessar o sistema!";
    } else {
        $msgErroSessao = $msgErroSenha = $msgErroNovaSenha = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["senhaAtual"])) {
    			$msgErroSenha = "Digite a sua senha atual!";
    		} else {
    			$senhaAtual = trataEntrada($_POST["senhaAtual"]);
    			if (!verificaSeSenhaEValida($senhaAtual)) {
    				$msgErroSenha = "Formato de senha inválido!";
    			} else {
                    if (!verificaSeSenhaEstaCorreta($senhaAtual, $_SESSION["senha"])) {
                        $msgErroSenha = "Senha inválida! Digite sua senha atual!";
                    } else {
                        // Verifica novaSenha e novaSenha2:
                        if ((empty($_POST["novaSenha"])) || (empty($_POST["novaSenha2"]))) {
                			$msgErroNovaSenha = "Senhas são requeridas!";
                		} else {
                			$novaSenha = trataEntrada($_POST["novaSenha"]);
                			$novaSenha2 = trataEntrada($_POST["novaSenha2"]);
                			// Verifica se ambas as senhas são iguais:
                			if ($novaSenha === $novaSenha2) {
                				if (!verificaSeSenhaEValida($novaSenha)) {
                					$msgErroNovaSenha = "Formato de senha inválido!";
                				} else {
                                    // Senha antiga válida e novas senhas válidas!
                                    if (!atualizaSenhaDeCliente($novaSenha)) {
                                        $msgErroSessao = "Não foi possível conectar ao servidor!<br>Tente novamente mais tarde.";
                						terminaSessao();
                                    } else {
                                        mudaDePagina("editarConta.php");
                                    }
                                }
                			} else {
                				$msgErroNovaSenha = "Ambas as senhas devem ser IDÊNTICAS!";
                			}
                		}
                    }
                }
    		}
        } // if ($_SERVER["REQUEST_METHOD"] == "POST")
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Mudar Senha Mini TI</title>
        <link href="css/estilos_gerais.css" rel="stylesheet" type="text/css">
    </head>
    <body>

        <?php
		if (verificaSeSessaoExiste()) {
		?>

            <div class='caixaInterface sistema'>
                <div class='cabecalhoSistema editarDadosCadastrais'>
                    <p class='bemVindo'>Edite sua senha</p>
                    <a href='editarConta.php'><button type='button' class='botao sair'>Voltar</button></a>
                </div>
                <div class='caixaInterface editarSenha'>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                        <input type="password" name="senhaAtual" class="campoDeEntrada" placeholder="senha atual" required>
                        <?php verificaMsgECriaBalao($msgErroSenha, "balaoMsg erro");?>
                        <input type="password" name="novaSenha" class="campoDeEntrada" placeholder="nova senha" required>
                        <?php verificaMsgECriaBalao($msgErroNovaSenha, "balaoMsg erro");?>
                        <input type="password" name="novaSenha2" class="campoDeEntrada" placeholder="repita a nova senha" required>
                        <br><input type="submit" name="botaoEnviarDados" value="Atualizar Senha">
                    </form>
                </div>
            </div>

        <?php
		} else {
			apresentaTelaDeErro($msgErroSessao);
		}
		?>

    </body>
</html>
