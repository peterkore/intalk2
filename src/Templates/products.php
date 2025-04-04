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

            <?php 
            // kereső mező hozzádása
                 require __DIR__ . '/Partials/query.php';  
          
           ?>
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
