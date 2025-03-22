<?php

session_start();

// Adatbáziskapcsolat
$servername = "localhost";
$username = "root";
$password = "mypass";
$dbname = "adatok";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Kosár inicializálása (munkamenet) ha még nincs
if (!isset($_SESSION['kosar'])) {
    $_SESSION['kosar'] = [];
}

// Termékek lekérdezése 
$sql = "SELECT id, termek_nev, termek_ara,keszlet FROM webshop";
$result = $conn->query($sql);

// Termék hozzáadása a kosárhoz
if (isset($_POST['kosarba'])) {
    $termek_id = $_POST['termek_id'];
    $termek_nev = $_POST['termek_nev'];
    $termek_ara = $_POST['termek_ara'];
    $_SESSION['kosar'][] = ['id' => $termek_id, 'nev' => $termek_nev, 'ara' => $termek_ara, 'keszlet' => $keszlet ];
}

// Termék eltávolítása a kosárból
if (isset($_POST['torles'])) {
    $index = $_POST['index'];
    array_splice($_SESSION['kosar'], $index, 1); //a tömb kiválasztott része
}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    
    <h1>Kosár</h1>
</head>
<body>
	<?php $total= 0;?>
        <?php foreach ($_SESSION['kosar'] as $index => $termek): ?>
          <li>
		<?= $termek['nev'] ?>
		<?= $termek['ara'] ?>
	
                <form method="post" >
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" name="torles">Törlés</button>
                </form>
            </li>
		<?php $total += $termek['ara'] ?>

        <?php endforeach; ?>
    <p><strong>Összesen: <?php echo $total;?></strong></p>


<a href="megrendw.php"> Megrendelem</a>
<a href="webshop.php"> Vissza a főoldalra </a>
</body>
</html>