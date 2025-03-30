<?php
// adatbázis kapcsolat létrehozása

//egyedi bejelentkezési adatok bekérése az config fájlból -- ideiglenesen amíg össze nem lesz fésülve a backenddel
include_once('config.php');
include_once('header.php');
include_once('body.php');
// Kapcsolat létrehozása
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Kapcsolat ellenőrzése
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);


}
$keres = "";
/*
if(isset($_POST['keres'])){  // a fv megézni hogy a kapott érték 0-e
    echo isset($_POST['keres']);
    $keres = $_POST['keres']; 
  }else{
    $keres = "1234";
    echo $keres;
  }
    */
 // az sql lekérdezés a keresésre, brand, leírás, model, name -be keres
 /* $sql = "SELECT * FROM `product` WHERE
   name LIKE %$keres% 
   OR description LIKE %$keres% 
   OR brand LIKE %$keres% 
   OR model LIKE %$keres%;";*/
   //$sql = "SELECT * FROM `product` WHERE name LIKE \'%kutya%\' OR description LIKE \'%ma%\' OR brand LIKE \'%ma%\' OR model LIKE \'%ma%\';";
   //$sql = "SELECT * FROM `product` WHERE description LIKE \"%kutya%\";";
 //találatok kilistázása

 //$result = $conn->query($sql);
/*
if ($result->num_rows > 0) {
    // adatok kiírása while ciklussal, 
    while($row = $result->fetch_assoc()) {
      echo '<div class="card row row-cols-auto" style="width: 18rem;">';
      echo '<img src="./IMG/card1.jpg" class="card-img-top" alt="...">';
      echo '<div class="card-body">';
      echo '<h5 class="card-title">'. $row["name"]. '</h5>';
      echo '<h6 class="card-subtitle mb-2 text-muted">###</h6>';
      echo '<p class="card-text">'. $row["description"]. '</p>';
      echo '<a href="#" class="card-link">#Részletek</a></br>';
      echo '<a href="#" class="btn btn-primary">Kosárba</a>';
      echo '</div>';
      echo '</div>';
      //echo "</br> kategória id: " . $row["category_id"]. " -Termék Név: " . $row["name"]. " Leírás: " . $row["description"]. "<br>";
    }
  } else {
    echo "Nincs találat";
  }
*/
  $conn->close();  // kapcsolat lezársa
?>