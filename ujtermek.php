
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kisallatbolt";

// Kapcsolódás az adatbázishoz
$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolat sikertelen: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $pet = $_POST['pet'];
    $function = $_POST['function'];
    $description = $_POST['description'];
    $expiry_date = $_POST['expiry_date'];

    $stmt = $conn->prepare("INSERT INTO products (name, pet, function, description, expiry_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $pet, $function, $description, $expiry_date);

    if ($stmt->execute()) {
        echo "Sikeresen hozzáadva!";
    } else {
        echo "Hiba: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Új termék felvétel</title>
    </head>
    <body>
        <form method="post" target="_self">
            <input type="text" name="name" placeholder="Megnevezés" required/>
            <select name="pet" id="pet">
                <option value="dog">Kutya</option>
                <option value="cat">Cica</option>
                <option value="small">Kisemlős</option>
                <option value="bird">Madár</option>
                <option value="fish">Halak</option>
                <option value="reptile">Hüllők</option>
            </select>
            <input type="text" name="function" placeholder="Funkció" required/>
            <div>
            <textarea rows = "8" cols = "60" name = "description" required>
                Termékleírás
            </textarea>
            </div>
            <div>
            Szavatossági idő: 
            <input type="datetime-local" />
            </div>
        </form>
    </body>
</html>