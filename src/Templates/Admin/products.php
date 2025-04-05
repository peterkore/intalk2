<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Termékek kezelése</h2>
        <a href="/admin/product/new" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Új termék
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
    <?php endif; ?>

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
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= $product->getId(); ?></td>
                                <td><?= htmlspecialchars($product->getName()); ?></td>
                                <td><?= htmlspecialchars($product->getSku()); ?></td>
                                <td><?= htmlspecialchars($product->getCategory()?->getName() ?? 'Nincs kategória'); ?></td>
                                <td><?= number_format($product->getPrice(), 0, ',', ' '); ?> Ft</td>
                                <td><?= $product->getStock(); ?></td>
                                <td>
                                    <span class="badge bg-<?= $product->isActive() ? 'success' : 'danger'; ?>">
                                        <?= $product->isActive() ? 'Aktív' : 'Inaktív'; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/product/edit/<?= $product->getId(); ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete(<?= $product->getId(); ?>)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(productId) {
        if (confirm('Biztosan törölni szeretnéd ezt a terméket?')) {
            window.location.href = '/admin/product/delete/' + productId;
        }
    }
</script>
</body>

</html>