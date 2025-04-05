<?php
  require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
?>
<!-- Termékek listázása csempe elrendezéssel -->
<div class="container">
    <h1>Termékek</h1>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <!-- 
                        Termék miniatűr képének megjelenítése.
                        - A miniatűr képet használjuk a gyorsabb betöltés érdekében
                        - Ha nincs miniatűr, az alapértelmezett miniatűr képet jeleníti meg
                        - Az alt attribútum a termék nevével van feltöltve
                        - A product-image div fix magasságot biztosít az egységes megjelenésért
                    -->
                    <div class="product-image">
                        <img src="/<?= htmlspecialchars($product->thumbnailPath ?: 'images/no-image-thumb.png') ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($product->getName()) ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product->getName()) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($product->getDescription()) ?></p>
                        <p class="card-text"><strong>Ár:</strong> <?= number_format($product->getPrice(), 0, ',', ' ') ?> Ft</p>
                        <a href="/product/<?= $product->getId() ?>" class="btn btn-primary">Részletek</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- 
    CSS stílusok a termék képekhez:
    - Fix magasság biztosítása az egységes megjelenésért
    - object-fit: cover biztosítja, hogy a kép kitöltse a rendelkezésre álló területet
    - Központosítás és arányok megtartása
-->
<style>
.product-image {
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card:hover .product-image img {
    transform: scale(1.1);
}
</style>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>