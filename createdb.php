<?php
//Parameter for DB
$host = 'localhost';
$username = 'int_eco';
$password = 'RZ+n<lMflK4q';
$db = 'int_eco';
$table = 'taxonomy';

$connect = mysqli_connect($host,$username,$password, $db)  or die ("Verbindung mit MySQL ist fehlgeschlagen: " . mysqli_error());
// $sel_db = mysqli_select_db($db) or die ("Datenbank nicht gefunden oder fehlerhaft");
mysqli_query($connect, 'SET LC_ALL = "de_DE"');   //Language Setting German
mysqli_query($connect, 'SET CHARACTER SET UTF8'); //set the charset auf UTF-8

// $sql='CREATE TABLE taxonomy(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, google_id INT,description VARCHAR(256))';
 
// Execute query
// if (mysqli_query($connect,$sql)) {
//   echo 'Table taxonomy created successfully';
// } else {
//   echo 'Error creating table: ' . mysqli_error($connect);
// }

 ?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Foundation | Welcome</title>
	<link rel="stylesheet" href="css/foundation.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="js/vendor/modernizr.js"></script>
</head>
<body>
<?php
$message = '';
$file = file('tax_full.txt'); ?>
<div class="wrapper">
	<div class="row">
		<div class="small column">
			<?= $message ?>
		</div>
	</div>
	<div class="row">
		<div class="small column">
			<form action="<?php $_SERVER['SRIPT_NAME'] ?>" method="GET">
				<div class="row">
					<div class="small-10 column collapse">
						<input type="text" name="searchTerm" id="keyword" value="<?= $_GET['searchTerm'] ?>">
					</div>
					<div class="small-2 column">
						<input class="button postfix" type="submit" name="search" id="search">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="large column">
			<table>
				<thead>
					<tr>
						<th width="60" class="taxId">ID</th>
						<th>Bezeichnung</th>
					</tr>
				</thead>
				<tbody>
					<?php
					?>

				<?php
				$output = array();
				foreach($file as $ausgabe) {
					$lines = explode(" - ", $ausgabe); 
					$output[$lines[0]] = $lines[0];
					$output[$lines[1]] = $lines[1];

					$query = 'INSERT INTO taxonomy (google_id, description)
							 VALUES (?,?)';
					$stmt = $connect->prepare($query);
					$stmt->bind_param("is", $output[$lines[0]], $output[$lines[1]]);
					mysqli_execute($stmt);
				}

				?>
				<?php
				// echo "<pre>";
				// print_r($output);
				// echo "</pre>";
				$stmt->close();
				$conn->close();
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<footer>
	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();
	</script>
</footer>
</body>
</html>
