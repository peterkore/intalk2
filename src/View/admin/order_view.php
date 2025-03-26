<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelés részletei - PetShop Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
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
                        <a class="nav-link active" href="/admin/orders">Rendelések</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/categories">Kategóriák</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/users">Felhasználók</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Kijelentkezés</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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
                                <td><?php echo $order->getId(); ?></td>
                            </tr>
                            <tr>
                                <th>Státusz:</th>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo match($order->getStatus()) {
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>">
                                        <?php echo $order->getStatus(); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Fizetési mód:</th>
                                <td><?php echo $order->getPaymentMethod(); ?></td>
                            </tr>
                            <tr>
                                <th>Szállítási mód:</th>
                                <td><?php echo $order->getShippingMethod(); ?></td>
                            </tr>
                            <tr>
                                <th>Rendelés dátuma:</th>
                                <td><?php echo $order->getCreatedAt()->format('Y-m-d H:i'); ?></td>
                            </tr>
                            <tr>
                                <th>Utolsó módosítás:</th>
                                <td><?php echo $order->getUpdatedAt()->format('Y-m-d H:i'); ?></td>
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
                                        <td><?php echo htmlspecialchars($item->getProduct()->getName()); ?></td>
                                        <td><?php echo $item->getQuantity(); ?></td>
                                        <td><?php echo number_format($item->getPrice(), 0, ',', ' '); ?> Ft</td>
                                        <td><?php echo number_format($item->getSubtotal(), 0, ',', ' '); ?> Ft</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Összesen:</th>
                                        <th><?php echo number_format($order->getTotalAmount(), 0, ',', ' '); ?> Ft</th>
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
                        <p><?php echo htmlspecialchars($order->getUser()->getName()); ?></p>
                        
                        <h6>Email:</h6>
                        <p><?php echo htmlspecialchars($order->getUser()->getEmail()); ?></p>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Számlázási cím</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <?php echo htmlspecialchars($order->getBillingAddress()->getName()); ?><br>
                            <?php echo htmlspecialchars($order->getBillingAddress()->getStreet()); ?><br>
                            <?php echo htmlspecialchars($order->getBillingAddress()->getZipCode()); ?> 
                            <?php echo htmlspecialchars($order->getBillingAddress()->getCity()); ?><br>
                            <?php echo htmlspecialchars($order->getBillingAddress()->getCountry()); ?>
                        </p>
                        <?php if ($order->getBillingAddress()->getPhone()): ?>
                            <p>Tel: <?php echo htmlspecialchars($order->getBillingAddress()->getPhone()); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Szállítási cím</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <?php echo htmlspecialchars($order->getShippingAddress()->getName()); ?><br>
                            <?php echo htmlspecialchars($order->getShippingAddress()->getStreet()); ?><br>
                            <?php echo htmlspecialchars($order->getShippingAddress()->getZipCode()); ?> 
                            <?php echo htmlspecialchars($order->getShippingAddress()->getCity()); ?><br>
                            <?php echo htmlspecialchars($order->getShippingAddress()->getCountry()); ?>
                        </p>
                        <?php if ($order->getShippingAddress()->getPhone()): ?>
                            <p>Tel: <?php echo htmlspecialchars($order->getShippingAddress()->getPhone()); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 