<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
?>
<div class="container">
    <h1>Termékek</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Név</th>
                <th>Készlet</th>
                <th class="col-2">Kosárhoz adás</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (method_exists($product, 'getId')): 
                $id = $product->getId();
                ?>
                <tr>
                    <td><?= $id ?></td>
                    <td><?= $product->getName() ?></td>
                    <td id="prodcuct-quantity"><?= $product->getStock() ?></td>
                    <td>
                        <button class="btn btn-success btn-sm add-to-cart-btn" data-id="<?= $id ?>" data-quantity="1">Kosárba</button>
                        <button class="btn btn-success btn-sm add-to-cart-btn" data-id="<?= $id ?>" data-quantity="-1">Törlés</button>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>