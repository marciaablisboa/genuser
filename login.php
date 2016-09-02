<?php


//o envio do e-mail e senha usam ajax. a requisição espera que a resposta seja no formato JSON(data Type:'json')
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//session_start();

include("conexao.php");


if(isset($_POST['email']) && strlen($_POST['email']) > 0){

	if(!isset($_SESSION))
        session_start();

    
/*
    $_SESSION['email'] = $mysqli->escape_string($_POST['email']);
    $_SESSION['senha'] = md5(md5($_POST['senha']));

    $sql_code = "SELECT senha, codigo FROM membros WHERE email = '$_SESSION[email]'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);
    $dado = $sql_query->fetch_assoc();
    $total = $sql_query->num_rows;

    
    if($total == 0){
    	$erro[] = "Este email não pertence à nenhum usuário.";
    }else{

    	if($dado['senha'] == $_SESSION['senha']){

    		$_SESSION['usuario'] = $dado['codigo']; /////

    	}else{

    		$erro[] = "Senha incorreta.";

    	}

    }

    if(count($erro) == 0 || !isset($erro)){
    	echo "<script>alert('Login efetuado com sucesso'); location.href='inicial.php';</script>";
    }*/

    // Recebe os dados do formulário
    $email = $mysqli->escape_string($_POST['email']);
    $senha = md5(md5($_POST['senha']));

    //$email = (isset($_POST['email'])) ? $_POST['email'] : '' ;
    //$senha = (isset($_POST['senha'])) ? $_POST['senha'] : '' ;

    //$_SESSION['email'] = $mysqli->escape_string($_POST['email']);
    //$_SESSION['senha'] = md5(md5($_POST['senha']));


 


    if (empty($email)):
    $retorno = array('codigo' => 0, 'mensagem' => 'Preencha seu e-mail!');
    echo json_encode($retorno);
    exit();
    endif;
 
    if (empty($senha)):
    $retorno = array('codigo' => 0, 'mensagem' => 'Preencha sua senha!');
    echo json_encode($retorno);
    exit();
    endif;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)):
    $retorno = array('codigo' => 0, 'mensagem' => 'Formato de e-mail inválido!');
    echo json_encode($retorno);
    exit();
    endif;

    $sql_code = "SELECT senha, codigo FROM membros WHERE email = '$email'"; //email = '$_SESSION[email]'";
    $sql_query = $mysqli->query($sql_code) or die($mysqli->error);
    $dado = $sql_query->fetch_assoc();
    $total = $sql_query->num_rows;

    
    if($total == 0){
        $erro[] = "E-mail não encontrado.";
        $_SESSION['logado'] = 'NAO';
    }else{

        if($dado['senha'] == $senha){

            $_SESSION['usuario'] = $dado['codigo']; 
            $_SESSION['logado'] = 'SIM'; /////

        }else{

            $erro[] = "Senha incorreta.";
            $_SESSION['logado'] = 'NAO';

        }

    }

    if ($_SESSION['logado'] == 'SIM'){
    $retorno = array('codigo' => 1, 'mensagem' => 'Logado com sucesso!');
    echo json_encode($retorno);
    //echo "<script>alert('Login efetuado com sucesso'); location.href='inicial.php';</script>";

    $sql_code = "INSERT INTO reg_logins (
                email, 
                data_hora)
                VALUES(
                '$email',
                NOW()
                )";
    exit();
    }else{
        $retorno = array('codigo' => '0', 'mensagem' => 'Usuário não autorizado!');
        echo json_encode($retorno);
        exit();
    }



    //if(count($erro) == 0 || !isset($erro)){   }


     
 

}

?>
<html>
	<head>
		<meta charset="utf-8">
	</head>
	<body>
    <center>
		<?php if(count($erro) > 0)
			foreach($erro as $msg){
				echo "<p>$msg</p>";
			}
		?>

        <h1> <img src="img/logofactory.jpg" alt="" /></h1>
		<form method="POST" action="" id="login-form">
			<!-- <p><input value="<?php /*echo $_SESSION['email'];*/ ?>" name="email" placeholder="E-mail" type="text" autofocus></p>-->

            <p><input name="email" placeholder="E-mail" type="text" autofocus></p>
			<p><input name="senha"  type="password" placeholder="Senha"></p>
			<p><a href="esqueceuasenha.php" target="_blank">Esqueceu sua senha?</a></p>
			<p><input value="Entrar" type="submit" id="btn-login"></p>
		</form>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
        <script src="js/custom.js"></script>
	</center>
    </body>
</html>