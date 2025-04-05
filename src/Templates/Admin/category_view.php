<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategória részletek</h2>
            <div>
                <a href="/admin/categories/edit/<?= $category->getId(); ?>" class="btn btn-warning">Szerkesztés</a>
                <a href="/admin/categories" class="btn btn-secondary">Vissza</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($category->getName()); ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($category->getDescription() ?? '')); ?></p>
                
                <h6 class="mt-4">Termékek (<?= $category->getProducts()->count(); ?>)</h6>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Név</th>
                                <th>Ár</th>
                                <th>Készlet</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($category->getProducts() as $product): ?>
                            <tr>
                                <td><?= $product->getId(); ?></td>
                                <td><?= htmlspecialchars($product->getName()); ?></td>
                                <td><?= number_format($product->getPrice(), 0, ',', ' '); ?> Ft</td>
                                <td><?= $product->getStock(); ?></td>
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