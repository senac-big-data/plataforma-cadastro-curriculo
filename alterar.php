<?php require '/home/ubuntu/vendor/autoload.php'; // include Composer's autoloader ?>
<?php include 'includes/functions.php'; ?>
<?php register_globals(); ?>

<?php

$servername = "localhost";
$username = "dbuser";
$password = "dbuser2019";
$dbname = "senac";

$button_status  = "disabled" ;

if ($botao=="Consultar"){

	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Falha na conexão: " . mysqli_connect_error());
	}
	
	$sql = "SELECT cpf, nome_completo, data_nascimento, endereco, telefone, estado_civil FROM cabecalho where cpf=".$cpf;
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
		
		while($row = mysqli_fetch_assoc($result)) {
			$cpf                = $row["cpf"];
			$nome_completo      = $row["nome_completo"];
            $data_nascimento    = fromDataMySQL($row["data_nascimento"]);
			$endereco           = $row["endereco"];
			$telefone           = $row["telefone"];
			$estado_civil       = $row["estado_civil"];
		}	

		//MongoDB Find
	
		$client = new MongoDB\Client("mongodb://localhost:27017");
		$collection = $client->senac->conteudo;

		
		$result = $collection->find( [ '_id' => $cpf ] );

		foreach ($result as $entry) {
			$experiencia_profissional = $entry['experiencia_profissional'];
			$formacao_academia        = $entry['formacao_academia'];
			$idioma                   = $entry['idioma'];
			$cursos                   = $entry['cursos'];
			$certificacoes            = $entry['certificacoes'];
						
		}
		

		
	} else {
		
		echo "Registro não encontrado";
		
			$nome_completo            = "";
            $data_nascimento          = "";
			$endereco                 = "";
			$telefone                 = "";
			$estado_civil             = "";
			$experiencia_profissional = "";
			$formacao_academia        = "";
			$idioma                   = "";
			$cursos                   = "";
			$certificacoes            = "";

		
	}

	mysqli_close($conn);
	
	$button_status = 'enabled';

	
}

if ($botao=="Alterar"){
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	// Check connection
	if (!$conn) {
		die("Falha na conexão: " . mysqli_connect_error());
	}
	
	$sql = "SELECT cpf, nome_completo, data_nascimento, endereco, telefone, estado_civil FROM cabecalho where cpf=".$cpf;
	$result = mysqli_query($conn, $sql);
	
	if (mysqli_num_rows($result) > 0) {
		//MySQL Update
		$data_nascimento = toDataMySQL($data_nascimento);
		$telefone        = toTelefoneMySQL($telefone);
		
		$sql = "update cabecalho 
		           set nome_completo            = '$nome_completo',
                       data_nascimento          = '$data_nascimento',
                       endereco                 = '$endereco',
                       telefone                 = $telefone,
                       estado_civil             = '$estado_civil'
                 where cpf = $cpf ";

		if ($conn->query($sql) === FALSE) {
			echo "Error: " . $sql . "<br>" . $conn->error;
		} else {
			
			//MongoDB Update
			
			$client = new MongoDB\Client("mongodb://localhost:27017");
			$collection = $client->senac->conteudo;
						
			$result = $collection->replaceOne( [ '_id' => $cpf ], ['experiencia_profissional' => $experiencia_profissional, 'formacao_academia' => $formacao_academia, 'idioma' => $idioma, 'cursos' => $cursos, 'certificacoes' => $certificacoes ] );

			echo "Registro alterado com sucesso";
			
			$data_nascimento = fromDataMySQL($data_nascimento);
			
		}
		
		mysqli_close($conn);
		
		$button_status = 'enabled';
		
	} else {
		
		echo "Informe um CPF";
		
	}

	

	
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
		jQuery(function($){
		$("#data_nascimento").mask("99/99/9999");
		$("#telefone").mask("(11) 99999-9999");
		});
		
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
		
		$(document).ready(function () {
		  //called when key is pressed in textbox
		  $("#telefone").keypress(function (e) {
			 //if the letter is not digit then display error and don't type anything
			 if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				//display error message
				return false;
			}
		   });
		});
		
		$(document).ready(function () {
		  //called when key is pressed in textbox
		  $("#data_nascimento").keypress(function (e) {
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
					<form method="post" action="alterar.php">
						<div class="field">
						  <label for="cpf">CPF</label>
						  <input type="text" name="cpf" id="cpf" maxlength="11" size="13" value="<?=$cpf?>" />
								<br>
								<ul class="actions">
									<li><input name="botao" type="submit" value="Consultar" class="alt" /></li>
								</ul>
						</div>
						
						<div class="field">
						  <label for="nome_completo">NOME COMPLETO</label>
						  <input type="text" name="nome_completo" id="nome_completo" maxlength="150" size="50" value="<?=$nome_completo?>" />
						</div>
						
						<div class="field">
						  <label for="data_nascimento">DATA NASCIMENTO</label>
						  <input type="text" name="data_nascimento" id="data_nascimento" maxlength="10" size="14" value="<?=$data_nascimento?>" />
						</div>
						
						<div class="field">
						  <label for="endereco">ENDEREÇO</label>
						  <input type="text" name="endereco" id="endereco" maxlength="150" size="50" value="<?=$endereco?>" />
						</div>
						
						<div class="field">
						  <label for="telefone">TELEFONE</label>
						  <input type="text" name="telefone" id="telefone" maxlength="11" size="16" value="<?=$telefone?>" />
						</div>
						
						<div class="field">
						  <label for="estado_civil">ESTADO CIVIL</label>
						  <input type="text" name="estado_civil" id="estado_civil" maxlength="15" size="17" value="<?=$estado_civil?>"/>
						</div>
						
						<div class="field">
						  <label for="message2">EXPERIÊNCIA PROFISSIONAL</label>
						  <textarea name="experiencia_profissional" id="experiencia_profissional" rows="6" cols="100"><?=$experiencia_profissional?></textarea>
						</div>
						
						<div class="field">
						  <label for="message2">FORMAÇÃO ACADÊMICA</label>
						  <textarea name="formacao_academia" id="formacao_academia" rows="6" cols="100"><?=$formacao_academia?></textarea>
						</div>
						
						<div class="field">
						  <label for="message2">IDIOMA</label>
						  <textarea name="idioma" id="idioma" rows="6" cols="100"><?=$idioma?></textarea>
						</div>
						
						<div class="field">
						  <label for="message2">CURSOS</label>
						  <textarea name="cursos" id="cursos" rows="6" cols="100"><?=$cursos?></textarea>
						</div>
						
						<div class="field">
						  <label for="message2">CERTIFICAÇÕES</label>
						  <textarea name="certificacoes" id="certificacoes" rows="6" cols="100"><?=$certificacoes?></textarea>
						</div>
						
						<ul class="actions">
							<li><input name="botao" type="submit" value="Alterar" class="alt" <?=$button_status?> /></li>
						</ul>
                                               
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
