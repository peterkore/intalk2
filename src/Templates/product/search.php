<?php
//require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
// keresett szöveget úgy tudom elérni, input gomb -> javascript -> redirct url /url/search /input box értéke
// index.2 php-t linkekkel
//http://localhost/product/search/s  így keres 
require __DIR__ . '/../Partials/header.php'; 
 //var_dump($products);
?>
<div class="container-fluid px-4">
<div class="row gx-5">
    <h1 class="mb-4"  >Keresési találatok: <span id="search-string"> </span> </h1>
   
            <?php  ?>
<!--a products oldalon létrehozok egy kereső mezőt-->
            <!--form method="GET" class="form-inline my-2 my-lg-0" action = "">
            <input class="form-control mr-sm-2" type="search" name="search" id="search" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-secondary my-2 my-sm-0" id="s-button" name="s-button" type="submit">Keresés</button>
            </form-->
            <?php
            foreach ($products as $product): ?>
                <?php
                if (method_exists($product, 'getId')): 
                    $id = $product->getId();
                    ?>   <div class="card row col-6 col-sm-3 px-5" style="width: 18rem;">
                          <img src="/assets/images/no-image.jpg" class="card-img-top" alt="Nincs kép">
                        <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product->getName()); ?></h5>
                            <p class="card-text"><?php echo number_format($product->getPrice(), 0, ',', ' '); ?> Ft</p>
                            <p class="text-dark"><?php echo htmlspecialchars($product->getDescription()); ?> </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/product/<?php echo $product->getId(); ?>" class="btn btn-primary">Részletek</a>
                                <?php if ($product->getStock() > 0): ?>
                                    <button class="btn btn-success add-to-cart" data-product-id="<?php echo htmlspecialchars ($product->getId()) ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                                </div>
                        </div>
                  
                <?php endif; ?>
            <?php endforeach; ?>
      

    

<style>
.breadcrumb {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 2rem;
}

.card {
    transition: transform 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.card-text {
    font-size: 1.2rem;
    color: #dc3545;
    font-weight: bold;
}

.btn {
    padding: 0.5rem 1rem;
}

.alert {
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 2rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch(`/cart/addToCart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'quantity=1'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Hiba:', error);
                alert('Hiba történt a művelet során');
            });
        });
    });
 // a script a beírt kereső stringet megfelelő url-be teszi a kereséshez
 document.getElementById("search-string").innerHTML = searchString;
});
</script>


<?php
require __DIR__ . '/../Partials/footer.php'; 
?> 