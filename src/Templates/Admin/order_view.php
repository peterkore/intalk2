<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Rendelés részletei</h2>
        <a href="/admin/orders" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Vissza
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rendelés adatai</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Rendelés ID:</th>
                            <td><?= $order->getId(); ?></td>
                        </tr>
                        <tr>
                            <th>Státusz:</th>
                            <td>
                                <span class="badge bg-<?php
                                $x = $order->getStatus();
                                                        echo match ($order->getStatus()) {
                                                            'pending' => 'warning',
                                                            'processing' => 'info',
                                                            'shipped' => 'primary',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger',
                                                            default => 'secondary'
                                                        };
                                                        ?>">
                                    <?= $order->getStatus(); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Fizetési mód:</th>
                            <td><?= $order->getPaymentMethod(); ?></td>
                        </tr>
                        <tr>
                            <th>Szállítási mód:</th>
                            <td><?= $order->getShippingMethod(); ?></td>
                        </tr>
                        <tr>
                            <th>Rendelés dátuma:</th>
                            <td><?= $order->getCreatedAt()->format('Y-m-d H:i'); ?></td>
                        </tr>
                        <tr>
                            <th>Utolsó módosítás:</th>
                            <td><?= $order->getUpdatedAt()->format('Y-m-d H:i'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Rendelt termékek</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Termék</th>
                                    <th>Mennyiség</th>
                                    <th>Egységár</th>
                                    <th>Részösszeg</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order->getItems() as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item->getProduct()->getName()); ?></td>
                                        <td><?= $item->getQuantity(); ?></td>
                                        <td><?= number_format($item->getPrice(), 0, ',', ' '); ?> Ft</td>
                                        <td><?= number_format($item->getSubtotal(), 0, ',', ' '); ?> Ft</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Összesen:</th>
                                    <th><?= number_format($order->getTotalAmount(), 0, ',', ' '); ?> Ft</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Felhasználó adatai</h5>
                </div>
                <div class="card-body">
                    <h6>Név:</h6>
                    <p><?= htmlspecialchars($order->getUser()->getName()); ?></p>

                    <h6>Email:</h6>
                    <p><?= htmlspecialchars($order->getUser()->getEmail()); ?></p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Számlázási cím</h5>
                </div>
                <div class="card-body">
                    <p>
                        <?= htmlspecialchars($order->getBillingAddress()->getName()); ?><br>
                        <?= htmlspecialchars($order->getBillingAddress()->getStreet()); ?><br>
                        <?= htmlspecialchars($order->getBillingAddress()->getZipCode()); ?>
                        <?= htmlspecialchars($order->getBillingAddress()->getCity()); ?><br>
                        <?= htmlspecialchars($order->getBillingAddress()->getCountry()); ?>
                    </p>
                    <?php if ($order->getBillingAddress()->getPhone()): ?>
                        <p>Tel: <?= htmlspecialchars($order->getBillingAddress()->getPhone()); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Szállítási cím</h5>
                </div>
                <div class="card-body">
                    <p>
                        <?= htmlspecialchars($order->getShippingAddress()->getName()); ?><br>
                        <?= htmlspecialchars($order->getShippingAddress()->getStreet()); ?><br>
                        <?= htmlspecialchars($order->getShippingAddress()->getZipCode()); ?>
                        <?= htmlspecialchars($order->getShippingAddress()->getCity()); ?><br>
                        <?= htmlspecialchars($order->getShippingAddress()->getCountry()); ?>
                    </p>
                    <?php if ($order->getShippingAddress()->getPhone()): ?>
                        <p>Tel: <?= htmlspecialchars($order->getShippingAddress()->getPhone()); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>