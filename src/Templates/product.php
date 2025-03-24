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
            </tr>
        </thead>
        <tbody>
            <?php
            if (method_exists($product, 'getId')): ?>
                <tr>
                    <td><?= $product->getId() ?></td>
                    <td><?= $product->getName() ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>