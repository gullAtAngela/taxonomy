<?php
//Parameter for DB
$host = 'localhost';
$username = 'int_eco';
$password = 'RZ+n<lMflK4q';
$db = 'int_eco';
$table = 'taxonomy';

$connect = mysqli_connect($host,$username,$password, $db)  or die ("Verbindung mit MySQL ist fehlgeschlagen: " . mysqli_error());
mysqli_query($connect, 'SET LC_ALL = "de_DE"');   //Language Setting German
mysqli_query($connect, 'SET CHARACTER SET UTF8'); //set the charset auf UTF-8
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Google Taxonomie</title>
	<link rel="stylesheet" href="css/foundation.min.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="js/vendor/modernizr.js"></script>
</head>
<body>
<?php
$message = ''; ?>
<div class="wrapper">
	<div class="row">
		<div class="small column">
			<h3>Google Taxonomie</h3>
			<?= $message ?>
		</div>
	</div>
	<div class="row">
		<div class="small medium-5 column">
			<form action="<?php $_SERVER['SRIPT_NAME'] ?>" method="GET">
				<div class="row collapse">
					<div class="small-9 column collapse">
						<input type="text" name="searchTerm" id="keyword" value="<?= $_GET['searchTerm'] ?>">
					</div>
					<div class="small-3 column">
						<input class="button postfix" type="submit" name="search" id="search" value="Suchen">
					</div>
				</div>
			</form>
		</div>
		<div class="small medium-4 column">
			<a href="#" class="button small">Catalog Upload</a>
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
				if (empty($_GET['searchTerm']) && $_GET['all'] != 'true') {
					$query = 'SELECT google_id, description FROM taxonomy ORDER BY RAND() LIMIT 0, 40
							 ';
					$stmt = $connect->prepare($query);
					mysqli_execute($stmt);
					$stmt->bind_result($name, $description);
					while ($stmt->fetch()) { ?>
						<tr>
							<td><?= $name ?></td>
							<td><?= $description ?></td>
						</tr>
					<?php
					}
				}

				if (empty($_GET['searchTerm']) && $_GET['all'] == 'true') {
					$query = 'SELECT google_id, description FROM taxonomy
							 ';
					$stmt = $connect->prepare($query);
					mysqli_execute($stmt);
					$stmt->bind_result($name, $description);
					while ($stmt->fetch()) { ?>
						<tr>
							<td><?= $name ?></td>
							<td><?= $description ?></td>
						</tr>
					<?php
					}
				}

				if (!empty($_GET['searchTerm'])) {
					$query = 'SELECT google_id, description FROM taxonomy WHERE google_id = ?
							 ';
					$stmt = $connect->prepare($query);
					$stmt->bind_param("i", $_GET['searchTerm']); 
					mysqli_execute($stmt);
					// var_dump($_GET['searchTerm']);
					$stmt->bind_result($google_id, $description);
					$stmt->fetch();
					while ($stmt->fetch()) { ?>
					<tr>
						<td><?= $google_id ?></td>
						<td><?= $description ?></td>
					</tr>
				<?php
					}
				}
				 
				if (!empty($_GET['searchTerm'])) {
					// $query = "SELECT google_id, description FROM taxonomy WHERE description LIKE" . "'Baby%'";
					// $query = "SELECT google_id, description FROM taxonomy WHERE description LIKE" . '\'%?%\'';
					$query = "SELECT google_id, description FROM taxonomy WHERE description LIKE ('%{$_GET['searchTerm']}%')
					OR google_id = ?";
					$stmt = $connect->prepare($query);
					$stmt->bind_param("i", $_GET['searchTerm']); 
					// var_dump($_GET['searchTerm']);
					mysqli_execute($stmt);
					$stmt->bind_result($google_id, $description);
					// $stmt->fetch();
					while ($stmt->fetch()) { ?>
						<tr>
							<td><?= $google_id ?></td>
							<td><?= $description ?></td>
						</tr>
					<?php
					}
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="small medium-8 column">
		<a href="?all=true" class="button expand">Show all</a>
	</div>
</div>
<footer>
	<?php
		$stmt->close();
		$connect->close();
	?>
	<script src="js/vendor/jquery.js"></script>
	<script src="js/foundation.min.js"></script>
	<script>
		$(document).foundation();
	</script>
</footer>
	<?php require_once '../general/footer.php'; ?>
</body>
</html>
