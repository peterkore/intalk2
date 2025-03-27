<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
?>
<div class="container">
    <h1>Kosár</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Név</th>
                <th>db</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($products as $product): ?>
                <?php
                if (method_exists($product, 'getId')): 
                $id = $product->getId();
                ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><a href="product/view/<?= $id ?>"><?= $product->getName() ?></a></td>
                        <td><?= $cart[$id] ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>