<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategóriák - PetShop Admin</title>
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
                                <td><?php echo $category->getId(); ?></td>
                                <td><?php echo htmlspecialchars($category->getName()); ?></td>
                                <td><?php echo htmlspecialchars($category->getDescription() ?? ''); ?></td>
                                <td><?php echo $category->getProducts()->count(); ?></td>
                                <td>
                                    <a href="/admin/categories/view/<?php echo $category->getId(); ?>" class="btn btn-sm btn-info">Részletek</a>
                                    <a href="/admin/categories/edit/<?php echo $category->getId(); ?>" class="btn btn-sm btn-warning">Szerkesztés</a>
                                    <a href="/admin/categories/delete/<?php echo $category->getId(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretnéd ezt a kategóriát?')">Törlés</a>
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