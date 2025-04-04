<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználó részletei - PetShop Admin</title>
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
                        <a class="nav-link" href="/admin/categories">Kategóriák</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/admin/users">Felhasználók</a>
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
            <h2>Felhasználó részletei</h2>
            <div>
                <a href="/admin/users/edit/<?php echo $user->getId(); ?>" class="btn btn-warning">Szerkesztés</a>
                <a href="/admin/users" class="btn btn-secondary">Vissza</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Alapadatok</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Név:</strong> <?php echo htmlspecialchars($user->getName()); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user->getEmail()); ?></p>
                        <p><strong>Regisztráció dátuma:</strong> <?php echo $user->getCreatedAt()->format('Y-m-d H:i'); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Számlázási cím</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($billingAddress): ?>
                            <p><strong>Utca, házszám:</strong> <?php echo htmlspecialchars($billingAddress->getStreet()); ?></p>
                            <p><strong>Város:</strong> <?php echo htmlspecialchars($billingAddress->getCity()); ?></p>
                            <p><strong>Irányítószám:</strong> <?php echo htmlspecialchars($billingAddress->getZipCode()); ?></p>
                            <p><strong>Ország:</strong> <?php echo htmlspecialchars($billingAddress->getCountry()); ?></p>
                            <?php if ($billingAddress->getPhone()): ?>
                                <p><strong>Telefonszám:</strong> <?php echo htmlspecialchars($billingAddress->getPhone()); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Nincs megadva számlázási cím</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Szállítási cím</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($shippingAddress): ?>
                            <p><strong>Utca, házszám:</strong> <?php echo htmlspecialchars($shippingAddress->getStreet()); ?></p>
                            <p><strong>Város:</strong> <?php echo htmlspecialchars($shippingAddress->getCity()); ?></p>
                            <p><strong>Irányítószám:</strong> <?php echo htmlspecialchars($shippingAddress->getZipCode()); ?></p>
                            <p><strong>Ország:</strong> <?php echo htmlspecialchars($shippingAddress->getCountry()); ?></p>
                            <?php if ($shippingAddress->getPhone()): ?>
                                <p><strong>Telefonszám:</strong> <?php echo htmlspecialchars($shippingAddress->getPhone()); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted">Nincs megadva szállítási cím</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 