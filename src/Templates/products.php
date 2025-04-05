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

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Név</th>
                            <th>SKU</th>
                            <th>Kategória</th>
                            <th>Ár</th>
                            <th>Készlet</th>
                            <th>Státusz</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product->getId(); ?></td>
                                <td><?= htmlspecialchars($product->getName()); ?></td>
                                <td><a href="product/view/<?= $product->getId() ?>"><?= htmlspecialchars($product->getName()) ?></a></td>
                                <td><?= htmlspecialchars($product->getSku()); ?></td>
                                <td><?= htmlspecialchars($product->getCategory()?->getName() ?? 'Nincs kategória'); ?></td>
                                <td><?= number_format($product->getPrice(), 0, ',', ' '); ?> Ft</td>
                                <td><?= $product->getStock(); ?></td>
                                <td>
                                    <span class="badge bg-<?= $product->isActive() ? 'success' : 'danger'; ?>">
                                        <?= $product->isActive() ? 'Aktív' : 'Inaktív'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>
