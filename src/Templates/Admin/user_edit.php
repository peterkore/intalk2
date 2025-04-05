<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?= $user ? 'Felhasználó szerkesztése' : 'Új felhasználó'; ?></h2>
        <a href="/admin/users" class="btn btn-secondary">Vissza</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?= $user ? '/admin/users/edit/' . $user->getId() : '/admin/users/new'; ?>">
                <h4 class="mb-3">Alapadatok</h4>
                <div class="mb-3">
                    <label for="name" class="form-label">Név</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user ? $user->getName() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email cím</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user ? $user->getEmail() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label"><?= $user ? 'Új jelszó (hagyd üresen, ha nem szeretnéd módosítani)' : 'Új jelszó' ?></label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                <h4 class="mb-3 mt-4">Számlázási cím</h4>
                <?php $billingAddress = $user ? $user->getDefaultAddress('billing') : ''; ?>
                <div class="mb-3">
                    <label for="billing_street" class="form-label">Utca, házszám</label>
                    <input type="text" class="form-control" id="billing_street" name="billing_street" value="<?= htmlspecialchars($billingAddress ? $billingAddress->getStreet() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="billing_city" class="form-label">Város</label>
                    <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?= htmlspecialchars($billingAddress ? $billingAddress->getCity() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="billing_postal_code" class="form-label">Irányítószám</label>
                    <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" value="<?= htmlspecialchars($billingAddress ? $billingAddress->getZipCode() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="billing_country" class="form-label">Ország</label>
                    <input type="text" class="form-control" id="billing_country" name="billing_country" value="<?= htmlspecialchars($billingAddress ? $billingAddress->getCountry() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="billing_phone" class="form-label">Telefonszám</label>
                    <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?= htmlspecialchars($billingAddress ? $billingAddress->getPhone() : ''); ?>">
                </div>

                <h4 class="mb-3 mt-4">Szállítási cím</h4>
                <?php $shippingAddress = $user ? $user->getDefaultAddress('shipping') : ''; ?>
                <div class="mb-3">
                    <label for="shipping_street" class="form-label">Utca, házszám</label>
                    <input type="text" class="form-control" id="shipping_street" name="shipping_street" value="<?= htmlspecialchars($shippingAddress ? $shippingAddress->getStreet() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipping_city" class="form-label">Város</label>
                    <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="<?= htmlspecialchars($shippingAddress ? $shippingAddress->getCity() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipping_postal_code" class="form-label">Irányítószám</label>
                    <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code" value="<?= htmlspecialchars($shippingAddress ? $shippingAddress->getZipCode() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipping_country" class="form-label">Ország</label>
                    <input type="text" class="form-control" id="shipping_country" name="shipping_country" value="<?= htmlspecialchars($shippingAddress ? $shippingAddress->getCountry() : ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="shipping_phone" class="form-label">Telefonszám</label>
                    <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone" value="<?= htmlspecialchars($shippingAddress ? $shippingAddress->getPhone() : ''); ?>">
                </div>

                <button type="submit" class="btn btn-primary">Mentés</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>