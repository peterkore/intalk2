<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Felhasználó részletei</h2>
        <div>
            <a href="/admin/users/edit/<?= $user->getId(); ?>" class="btn btn-warning">Szerkesztés</a>
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
                    <p><strong>Név:</strong> <?= htmlspecialchars($user->getName()); ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()); ?></p>
                    <p><strong>Regisztráció dátuma:</strong> <?= $user->getCreatedAt()->format('Y-m-d H:i'); ?></p>
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
                        <p><strong>Utca, házszám:</strong> <?= htmlspecialchars($billingAddress->getStreet()); ?></p>
                        <p><strong>Város:</strong> <?= htmlspecialchars($billingAddress->getCity()); ?></p>
                        <p><strong>Irányítószám:</strong> <?= htmlspecialchars($billingAddress->getZipCode()); ?></p>
                        <p><strong>Ország:</strong> <?= htmlspecialchars($billingAddress->getCountry()); ?></p>
                        <?php if ($billingAddress->getPhone()): ?>
                            <p><strong>Telefonszám:</strong> <?= htmlspecialchars($billingAddress->getPhone()); ?></p>
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
                        <p><strong>Utca, házszám:</strong> <?= htmlspecialchars($shippingAddress->getStreet()); ?></p>
                        <p><strong>Város:</strong> <?= htmlspecialchars($shippingAddress->getCity()); ?></p>
                        <p><strong>Irányítószám:</strong> <?= htmlspecialchars($shippingAddress->getZipCode()); ?></p>
                        <p><strong>Ország:</strong> <?= htmlspecialchars($shippingAddress->getCountry()); ?></p>
                        <?php if ($shippingAddress->getPhone()): ?>
                            <p><strong>Telefonszám:</strong> <?= htmlspecialchars($shippingAddress->getPhone()); ?></p>
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