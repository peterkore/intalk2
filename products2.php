<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root";
$password = "mypass";
$dbname = "webshop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Kapcsolódási hiba: " . $conn->connect_error]));
}

$sql = "SELECT * FROM product";
$result = $conn->query($sql);

$termekek = [];
while ($row = $result->fetch_assoc()) {
    $termekek[] = $row;
}

echo json_encode($termekek);
$conn->close();
?>
