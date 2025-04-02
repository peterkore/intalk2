<?php
  require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
 // require_once __DIR__ . DIRECTORY SEPARATOR . 'product' . DIRECTORY_SEPARATOR . 
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
            <form class="form-inline my-2 my-lg-0" action = "<?php echo $search() ?>">
            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Keresés" aria-label="Keresés">
            <button class="btn btn-secondary my-2 my-sm-0" type="submit">Keresés</button>
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