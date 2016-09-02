
<?php


// visualizar usuários
error_reporting(E_ERROR | E_WARNING | E_PARSE);

	include("conexao.php");
	include("protect.php");
	protect();	

	$sql_code = "SELECT * FROM membros";
	$sql_query = $mysqli->query($sql_code) or die($mysqli->error);
	$linha = $sql_query->fetch_assoc(); //array com os resultados

	$sexo[1] = "Masculino";
	$sexo[2] = "Feminino";

	$niveldeacesso[1] = "Básico";
	$niveldeacesso[2] = "Admin";

?>
<meta charset="utf8">
<title>Lista de Usuários</title>

<a href="logout.php">Logout</a>


<h1>Usuários cadastrados</h1>
<a href="index.php?p=cadastrar">Cadastrar um usuário</a>
<p class=espaco></p>

<table border=1 cellpadding=10>
	<tr class=titulo>
		<td>Nome</td>
		<td>Sobrenome</td>
		<td>Sexo</td>
		<td>E-mail</td>
		<td>Nível de Acesso</td>
		<td>Data de Cadastro</td>
		<td>Ação</td>
	</tr>
	<?php
	do{
	?>
	<tr>
		<td><?php echo $linha['nome']; ?></td>
		<td><?php echo $linha['sobrenome']; ?></td>
		<td><?php echo $sexo[$linha['sexo']]; ?></td>
		<td><?php echo $linha['email']; ?></td>
		<td><?php echo $niveldeacesso[$linha['niveldeacesso']]; ?></td>
		<td><?php
		$d = explode(" ", $linha['datadecadastro']); // função que separa a string qdo encontra espaço em branco, criando índices de array
		$data = explode("-", $d[0]); // função que separa a string do índice d[0] qdo encontra -
		echo "$data[2]/$data[1]/$data[0] às $d[1]"; //exibe a data e hora da maneira brasileira

		?></td>
		<td>
			<a href="index.php?p=editar&usuario=<?php echo $linha['codigo']; ?>">Editar</a> |
			<a href="javascript: if(confirm('Tem certeza que deseja deletar o usuário <?php echo $linha['nome']; ?>?'))
			location.href='index.php?p=deletar&usuario=<?php echo $linha['codigo']; ?>';">Deletar</a>
		</td>
	</tr>
	<?php } while($linha = $sql_query->fetch_assoc()); ?>

</table>