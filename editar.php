<?php

error_reporting(E_ERROR | E_WARNING | E_PARSE);

	include("conexao.php");

	if(!isset($_GET['usuario']))
		echo "<script> alert('Codigo invalido.'); location.href='index.php?p=inicial'; </script>";
	else{

	$usu_codigo = intval($_GET['usuario']);


	
	if(isset($_POST['confirmar'])){

		// 1 - Registro dos dados

		if(!isset($_SESSION))
			session_start();

		foreach($_POST as $chave=>$valor)
			$_SESSION[$chave] = $mysqli->real_escape_string($valor);

		// 2 - Validação dos dados
		if(strlen($_SESSION['nome']) == 0)
			$erro[] = "Preencha o nome.";

		if(strlen($_SESSION['sobrenome']) == 0)
			$erro[] = "Preencha o sobrenome.";

		if(substr_count($_SESSION['email'], '@') != 1 || substr_count($_SESSION['email'], '.') < 1 || substr_count($_SESSION['email'], '.') > 2)
			$erro[] = "Preencha o e-mail corretamente.";

		if(strlen($_SESSION['niveldeacesso']) == 0)
			$erro[] = "Preencha o nivel de acesso.";

		if(strlen($_SESSION['senha']) < 8 || strlen($_SESSION['senha']) > 16)
			$erro[] = "Preencha a senha corretamente.";

		if(strcmp($_SESSION['senha'], $_SESSION['rsenha']) != 0)
			$erro[] = "As senhas não batem.";

		// 3 - Inserção no Banco e redirecionamento
		if(count($erro) == 0){

			$senha = md5(md5($_SESSION['senha']));

			$sql_code = "UPDATE membros SET
				nome = '$_SESSION[nome]', 
				sobrenome = '$_SESSION[sobrenome]', 
				email = '$_SESSION[email]', 
				senha = '$senha', 
				sexo = '$_SESSION[sexo]', 
				niveldeacesso = '$_SESSION[niveldeacesso]'
				WHERE codigo = '$usu_codigo'";

			$confirma = $mysqli->query($sql_code) or die($mysqli->error);

			if($confirma){

				unset($_SESSION[nome],
				$_SESSION[sobrenome],
				$_SESSION[email],
				$_SESSION[senha],
				$_SESSION[sexo],
				$_SESSION[niveldeacesso],
				$_SESSION[datadecadastro]);

				echo "<script> location.href='index.php?p=inicial'; </script>";

			}else
				$erro[] = $confirma;

		}

	}else{

		$sql_code = "SELECT * FROM membros WHERE codigo = '$usu_codigo'";
		$sql_query = $mysqli->query($sql_code) or die($mysqli->error);
		$linha = $sql_query->fetch_assoc();

		if(!isset($_SESSION))
			session_start();

		$_SESSION[nome] = $linha['nome'];
		$_SESSION[sobrenome] = $linha['sobrenome'];
		$_SESSION[email] = $linha['email'];
		$_SESSION[senha] = $linha['senha'];
		$_SESSION[sexo] = $linha['sexo'];
		$_SESSION[niveldeacesso] = $linha['niveldeacesso'];
		

	}

?>

<h1>Editar Usuário</h1>
<?php 
if(count($erro) > 0){ 
	echo "<div class='erro'>"; 
	foreach($erro as $valor) 
		echo "$valor <br>"; 

	echo "</div>"; 
}
?>
<a href="index.php?p=inicial">< Voltar</a>
<form action="index.php?p=editar&usuario=<?php echo $usu_codigo; ?>" method="POST">
	
	<label for="nome">Nome</label>
	<input name="nome" value="<?php echo $_SESSION[nome]; ?>" required type="text">
	<p class=espaco></p>

	<label for="sobrenome">Sobrenome</label>
	<input name="sobrenome" value="<?php echo $_SESSION[sobrenome]; ?>" required type="text">
	<p class=espaco></p>

	<label for="email">E-mail</label>
	<input name="email" value="<?php echo $_SESSION[email]; ?>" required type="email">
	<p class=espaco></p>

	<label for="sexo">Sexo</label>
	<select name="sexo">
		<option value="">Selecione</option>
		<option value="1" <?php if($_SESSION[sexo] == 1) echo "selected"; ?> >Masculino</option>
		<option value="2" <?php if($_SESSION[sexo] == 2) echo "selected"; ?> >Feminino</option>
	</select>
	<p class=espaco></p>

	<label for="niveldeacesso">Nível de Acesso</label>
	<select name="niveldeacesso">
		<option value="">Selecione</option>
		<option value="1" <?php if($_SESSION[niveldeacesso] == 1) echo "selected"; ?>>Basico</option>
		<option value="2" <?php if($_SESSION[niveldeacesso] == 2) echo "selected"; ?>>Admin</option>
	</select>
	<p class=espaco></p>

	<label for="senha">Senha</label>
	A senha deve ter entre 8 e 16 caracteres.
	<input name="senha" value="" required type="password">
	<p class=espaco></p>

	<label for="rsenha">Repita a Senha</label>
	<input name="rsenha" value="" required type="password">
	<p class=espaco></p>

	<input value="Salvar" name="confirmar" type="submit">

</form>

<?php } ?>