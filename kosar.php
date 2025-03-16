<?php

session_start();
if (!isset($_SESSION['kosar'])) {
    $_SESSION['kosar'] = [];
}

// Terméklista
$products = [
    1 => ["name" => "Kutya nyakörv", "price" => 4500],
    2 => ["name" => "Kutya ágy", "price" => 10500],
    3 => ["name" => "Kutyaetető", "price" => 1812]
];

// Termék törlés a kosárból
if (isset($_GET['remove']) && isset($_SESSION['kosar'][$_GET['remove']])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['kosar'][$id]);
    header("Location: kosar.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    
    <title>Kosár</title>
</head>
<body>
    <h1>Kosár</h1>
    <ul>
        <?php $total = 0; ?>
        <?php foreach ($_SESSION['kosar'] as $id => $quantity): ?>
            <li>
                <?php echo $products[$id]['name']; ?> - <?php echo $quantity; ?> db - <?php echo $products[$id]['price'] * $quantity; ?>, Ft
                <a href="kosar.php?remove=<?php echo $id; ?>">Törlés</a>
            </li>
            <?php $total += $products[$id]['price'] * $quantity; ?>
        <?php endforeach; ?>
    </ul>
    <p><strong>Összesen: <?php echo $total; ?>$</strong></p>
    <a href="indexw.php">Vissza a főoldalra</a>
</body>
</html>
