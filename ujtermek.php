
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kisallatbolt"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódás sikertelen: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $pet = $_POST['pet'];
    $function = $_POST['function'];
    $description = $_POST['description'];
    $expiry_date = $_POST['expiry_date'] ?? NULL;
    
    // Kép feltöltése
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["filename"]["name"]);
    move_uploaded_file($_FILES["filename"]["tmp_name"], $target_file);
    
    $image_path = $target_file;

    $sql = "INSERT INTO products (name, pet, function, description, expiry_date, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $pet, $function, $description, $expiry_date, $image_path);

    if ($stmt->execute()) {
        echo "Sikeresen hozzáadva!";
    } else {
        echo "Hiba történt: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>



<!DOCTYPE html>
<html>
    <head>
        <title>Új termék felvétel</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Új termék felvétele</h1>
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
            <div>
			<label for="myFile">Adjon meg egy képet</label>
			<input type="file" id="myFile" name="filename" required>
            </div>
            <input type="submit">
        </form>
    </body>
</html>