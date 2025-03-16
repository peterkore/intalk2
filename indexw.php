<?php

session_start(); //újmunkamenet-
if (!isset($_SESSION['kosar'])) { //isset változónak lesz e értéke 
    $_SESSION['kosar'] = [];
}

// Terméklista












$products = [
    1 => ["name" => "Kutya nyakörv", "price" => 4500],
    2 => ["name" => "Kutya ágy", "price" => 10500],
    3 => ["name" => "Kutyaetető", "price" => 1812]
];

if (isset($_GET['add']) && isset($products[$_GET['add']])) {
    $id = (int)$_GET['add'];
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['kosar'][$id] = 0;
    }
    $_SESSION['kosar'][$id]++;
    header("Location: indexw.php");
    exit();
}
?>
<?php
 $PageTitle = "KutyaWebshop" ?>
<?php 
/**
 * header beillesztése
 * 
 */

include_once('header.php') ?>

<!--!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>KutyaWebshop</title>
</head-->
<body>
    <h1>KutyaWebshop</h1>
    <h2>Termékek</h2>
    <ul>
        <?php foreach ($products as $id => $product): ?>
            <li>
                <?php echo $product['name']; ?> - <?php echo $product['price']; ?> ,Ft
                <a href="indexw.php?add=<?php echo $id; ?>">Kosárba</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="kosar.php">Kosár megtekintése</a>
</body>
</html>
