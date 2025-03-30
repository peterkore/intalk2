<?php

session_start();
print_r($_POST);
// Adatbáziskapcsolat
include_once 'dbConnection.php';

/*$servername = "localhost";
$username = "root";
$password = "mypass";
$dbname = "adatok";*/

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

/* Kosár inicializálása (munkamenet) ha még nincs
if (!isset($_SESSION['kosar'])) {
    $_SESSION['kosar'] = [];
}


 Termék hozzáadása a kosárhoz
if (isset($_POST['kosarba'])) {
    $termek_id = $_POST['termek_id'];
    $termek_nev = $_POST['termek_nev'];
    $termek_ara = $_POST['termek_ara'];
    $keszlet= $_POST['keszlet'];
    $_SESSION['kosar'][] = ['id' => $termek_id, 'nev' => $termek_nev, 'ara' => $termek_ara, 'keszlet' => $keszlet ];
}
*/

$sql= "SELECT id,termek_nev,termek_ara,keszlet FROM webshop";
$result =$conn->query($sql);

//megrendelés leadása
if (isset($_POST['checkout'])) {
	foreach($_SESSION['kosar'] as $index =>$termek ) {
	$conn->query("UPDATE webshop set keszlet=keszlet-1 where id='$id'");
/*        $stmt =$conn->prepare("UPDATE webshop SET keszlet=keszlet-1 WHERE id=$termek['id']");
	foreach ($_SESSION['kosar'] as $index => $termek) {
	    $stmt->bind_param("i", $termek['id']);
	    $stmt->execute();
	    }*/

}
$_SESSION['kosar']=[];
}
$result =$conn->query("SELECT * from webshop");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    
    <h1>Megrendelés</h1>
</head>
<body>
	
        <?php foreach ($_SESSION['kosar'] as $index => $termek): ?>
          <li>
		<?= $termek['nev'] .'.' ?>
		<?= $termek['ara'] ?>
		<?= $termek['id'] ?>
                <form method="post" >
                    <input type="hidden" name="index" value="<?= $index ?>">
		    <?php $conn->query("UPDATE webshop SET keszlet=keszlet-1 WHERE id=$termek[id]"); ?>
		    <?php array_splice($_SESSION['kosar'],$index,1); ?>                
                </form>
            </li>
		<?php $total += $termek['ara'] ?>

        <?php endforeach; ?>
    


<a href="kosarw.php"> Vissza a kosárra</a>
<a href="webshop.php"> Vissza a főoldalra </a>
</body>
</html>