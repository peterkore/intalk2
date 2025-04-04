<?php
  require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
  //var_dump($products);
?>
<div class="container">
    <h1>Termékek#</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Név</th>
            </tr>
        </thead>
        <tbody>
            <?php  ?>
<!--a products oldalon létrehozok egy kereső mezőt-->
            <form method="GET" class="form-inline my-2 my-lg-0" action = "">
            <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-secondary my-2 my-sm-0" id="s-button" name="s-button" type="submit">Keresés</button>
            </form>
            <?php
            foreach ($products as $product): ?>
                <?php
                if (method_exists($product, 'getId')): 
                    $id = $product->getId();
                    ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><a href="product/view/<?= $id ?>"><?= $product->getName() ?></a></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>
<script>
// a script a beírt kereső stringet megfelelő url-be teszi a kereséshez
document.getElementById("s-button").addEventListener("click", mySearch);
function mySearch(){
    var searchString = document.getElementById("search").value;
    submitOK = "true";
    //lekéream az aktuális domaint a kereséshz
    var currentDomain = window.location.hostname;
    //alert (currentDomain);
    var urlString = "/product/search/";
    // a domainhez hozzáteszem  a searchUrl-t
    var a = "http://"
    
    var searchUrl = a+currentDomain + urlString + searchString;
      // alert(searchUrl);    
   try {
    //document.getElementById("s-button").innerHTML = "Ide kattintottál";
      
        const sameOriginContext = window.open(searchUrl);
   }
   catch (e){
      alert("hiba, újra!");
      consol.log(e)
   }
    
  
       

    }


</script>