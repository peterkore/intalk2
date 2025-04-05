 <?php


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



  ?>