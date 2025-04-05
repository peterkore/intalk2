<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kategóriák</h2>
        <a href="/admin/categories/create" class="btn btn-primary">Új kategória</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Név</th>
                            <th>Leírás</th>
                            <th>Termékek száma</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category->getId(); ?></td>
                                <td><?= htmlspecialchars($category->getName()); ?></td>
                                <td><?= htmlspecialchars($category->getDescription() ?? ''); ?></td>
                                <td><?= $category->getProducts()->count(); ?></td>
                                <td>
                                    <a href="/admin/categories/view/<?= $category->getId(); ?>" class="btn btn-sm btn-info">Részletek</a>
                                    <a href="/admin/categories/edit/<?= $category->getId(); ?>" class="btn btn-sm btn-warning">Szerkesztés</a>
                                    <a href="/admin/categories/delete/<?= $category->getId(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretnéd ezt a kategóriát?')">Törlés</a>
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
</body>

</html>