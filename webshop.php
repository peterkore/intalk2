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
    $_SESSION['kosar'][] = ['id' => $termek_id, 'nev' => $termek_nev, 'ara' => $termek_ara, 'keszlet' =>$keszlet ];
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
    <h1> <center> - Kutya webshop - </center> </h1>
</head>
<body>
 <center>Minőségi kutyadivat. Divatos kutyaruházat, kabátok, esőkabát, kutyasál, kendő. Ez az igazi kutyabarát webshop! </center>

<h2>Kutyadivat minden kutyának</h2>

<justify>A kutya divat csúcsa a kedvencednek! ERedeti spanyol kutyaruházat és kiegészítők. Magyar kutyaöltözék. 
Elegáns, vagány viselet, nálunk mindent megtalálsz.Divatos háromszög <b>kutyakendők</b>, téli, meleg kutya körsál,
póló, trikó, <b>kifordítható kutyaruhák</b> és emleg téli <b>kutyakabát, vízálló esőkabát</b> mind a kutyawebshopban! 
</justify>
<br>
<br>

Ha meg szeretnéd kedvencedet lepni és igazán trendivé varázsolni, vagy csak ajándék ötlete keresel a kutyásoknak, 
akkor a <b>kutya hajdísz </b> ajándékdobozunkkal nem fogsz mellélőni. Kutyaruha kiegészítője egy divatos nyakörv és 
póráz, és a legújabb trend: a <b>kutyaékszer</b>, vagy más néven a kutya <b>póráz dísz.</b>
Elegáns, divatos és trendi. A kutyawebshopban találsz még <b>kutya fürdőlepedőket</b>, puha <b>kutyatakarókat</b>
és <b>kutyafekhelyeket</b> újrahasznosított anyagokból. 
<hr>
    <h3><u>Legújabb termékek:</u></h3>
<table >

        <?php while ($row = $result->fetch_assoc()): ?> 
	    <tr>
                <td><b><?= $row['termek_nev'] ?></b></td>
		<td><?= $row['termek_ara'].' -Ft' ?></td>
		<td><?= $row['keszlet'].'   db' ?></td>

                <form method="post" >
                    <input type="hidden" name="termek_id" value="<?= $row['id'] ?>"> 
                    <input type="hidden" name="termek_nev" value="<?= htmlspecialchars($row['termek_nev']) ?>">
		    <input type="hidden" name="termek_ara" value="<?= htmlspecialchars($row['termek_ara']) ?>">
		    
                    <td><button type="submit" name="kosarba">Kosárba</button></td>

                </form>
		
        
        <?php endwhile; ?>
    
</tr>
</table>
    <hr>

    <h2>Kosár:</h2>
    <ul>

        <?php foreach ($_SESSION['kosar'] as $index => $termek): ?>
            
            <li>
		<?= $termek['nev'] ?>
		<?= $termek['ara'] ?>
		
                <form method="post" >
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" name="torles">Törlés</button>
                </form>
            </li>

        <?php endforeach; ?>

    </ul>
<a href="kosarw.php">Tovább a megrendelésre </a>
</body>
</html>
