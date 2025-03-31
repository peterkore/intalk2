<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Felhasználó szerkesztése - PetShop Admin</title>
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
            <h2>Felhasználó szerkesztése</h2>
            <a href="/admin/users" class="btn btn-secondary">Vissza</a>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="/admin/users/edit/<?php echo $user->getId(); ?>">
                    <h4 class="mb-3">Alapadatok</h4>
                    <div class="mb-3">
                        <label for="name" class="form-label">Név</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email cím</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Új jelszó (hagyd üresen, ha nem szeretnéd módosítani)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>

                    <h4 class="mb-3 mt-4">Számlázási cím</h4>
                    <?php $billingAddress = $user->getDefaultAddress('billing'); ?>
                    <div class="mb-3">
                        <label for="billing_street" class="form-label">Utca, házszám</label>
                        <input type="text" class="form-control" id="billing_street" name="billing_street" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getStreet() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="billing_city" class="form-label">Város</label>
                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getCity() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="billing_postal_code" class="form-label">Irányítószám</label>
                        <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getZipCode() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="billing_country" class="form-label">Ország</label>
                        <input type="text" class="form-control" id="billing_country" name="billing_country" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getCountry() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="billing_phone" class="form-label">Telefonszám</label>
                        <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getPhone() : ''); ?>">
                    </div>

                    <h4 class="mb-3 mt-4">Szállítási cím</h4>
                    <?php $shippingAddress = $user->getDefaultAddress('shipping'); ?>
                    <div class="mb-3">
                        <label for="shipping_street" class="form-label">Utca, házszám</label>
                        <input type="text" class="form-control" id="shipping_street" name="shipping_street" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getStreet() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_city" class="form-label">Város</label>
                        <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getCity() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_postal_code" class="form-label">Irányítószám</label>
                        <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getPostalCode() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_country" class="form-label">Ország</label>
                        <input type="text" class="form-control" id="shipping_country" name="shipping_country" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getCountry() : ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_phone" class="form-label">Telefonszám</label>
                        <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getPhone() : ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-primary">Mentés</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 