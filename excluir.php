<?php require '/home/ubuntu/vendor/autoload.php'; // include Composer's autoloader ?>
<?php include 'includes/functions.php'; ?>
<?php register_globals(); ?>

<?php

if ($botao=="Excluir"){

	$servername = "localhost";
	$username = "dbuser";
	$password = "dbuser2019";
	$dbname = "senac";

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Falha na conexão: " . mysqli_connect_error());
	}
	
	$sql = "select cpf from cabecalho where cpf=".$cpf;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		
		$sql = "delete from cabecalho where cpf=".$cpf;
		$result = mysqli_query($conn, $sql);
		
		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		} else {
			
			//MongoDB Find
	
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection = $client->senac->conteudo;

			$result = $collection->deleteOne( [ '_id' => $cpf ] );

			echo "Registro excluído";
			
			$cpf = "";
			
		}
	
	} else {
		
		echo "Registro não encontrado";
				
	}

	mysqli_close($conn);

	
}


?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>SENAC</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
				<script src="includes/jquery.min.js"></script>
		<script src="includes/jquery.mask.min.js"></script>
		<script>

		
		$(document).ready(function () {
		  //called when key is pressed in textbox
		  $("#cpf").keypress(function (e) {
			 //if the letter is not digit then display error and don't type anything
			 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				return false;
			}
		   });
		});
						
		</script>

	</head>
	<body>

		<!-- Header -->
			<header id="header">
				<div class="inner">
				  <nav id="nav"><a href="consultar.php">CONSULTAR</a> <a href="cadastrar.php">CADASTRAR</a> <a href="alterar.php">ALTERAR</a> <a href="excluir.php">EXCLUIR</a></nav>
				</div>
			</header>
			<a href="#menu" class="navPanelToggle"><span class="fa fa-bars"></span></a>



		<!-- Footer -->
			<section id="footer">
				<div class="inner">
					<header>
					  <h2>INFORMAÇÕES</h2>
					</header>
					<form method="post" action="excluir.php">
						<div class="field">
						  <label for="cpf">CPF</label>
						  <input type="text" name="cpf" id="cpf" maxlength="11" size="13" value="<?=$cpf?>" />
						</div>
												
						<ul class="actions">
							<li><input name="botao" type="submit" value="Excluir" class="alt" /></li>
						</ul>
                             <br><br><br><br> <br><br> <br>          
					</form>
				</div>
			</section>

			<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>
