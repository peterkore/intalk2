<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategória részletek - PetShop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin">PetShop Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/products">Termékek</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/orders">Rendelések</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/categories">Kategóriák</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Felhasználók</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/logout">Kijelentkezés</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategória részletek</h2>
            <div>
                <a href="/admin/categories/edit/<?php echo $category->getId(); ?>" class="btn btn-warning">Szerkesztés</a>
                <a href="/admin/categories" class="btn btn-secondary">Vissza</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($category->getName()); ?></h5>
                <p class="card-text"><?php echo nl2br(htmlspecialchars($category->getDescription() ?? '')); ?></p>
                
                <h6 class="mt-4">Termékek (<?php echo $category->getProducts()->count(); ?>)</h6>
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
                                <td><?php echo $product->getId(); ?></td>
                                <td><?php echo htmlspecialchars($product->getName()); ?></td>
                                <td><?php echo number_format($product->getPrice(), 0, ',', ' '); ?> Ft</td>
                                <td><?php echo $product->getStock(); ?></td>
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