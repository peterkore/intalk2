<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendelések kezelése - PetShop Admin</title>
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
        <h2 class="mb-4">Rendelések kezelése</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Rendelés ID</th>
                                <th>Felhasználó</th>
                                <th>Összeg</th>
                                <th>Státusz</th>
                                <th>Fizetési mód</th>
                                <th>Szállítási mód</th>
                                <th>Dátum</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo $order->getId(); ?></td>
                                <td><?php echo htmlspecialchars($order->getUser()->getName()); ?></td>
                                <td><?php echo number_format($order->getTotalAmount(), 0, ',', ' '); ?> Ft</td>
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
                                <td><?php echo $order->getPaymentMethod(); ?></td>
                                <td><?php echo $order->getShippingMethod(); ?></td>
                                <td><?php echo $order->getCreatedAt()->format('Y-m-d H:i'); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <a href="/admin/orders/view/<?php echo $order->getId(); ?>" 
                                           class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-primary"
                                                onclick="updateStatus(<?php echo $order->getId(); ?>)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Státusz módosítás modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rendelés státuszának módosítása</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Új státusz</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Függőben</option>
                                <option value="processing">Feldolgozás alatt</option>
                                <option value="shipped">Kiszállítva</option>
                                <option value="delivered">Kézbesítve</option>
                                <option value="cancelled">Törölve</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mégse</button>
                        <button type="submit" class="btn btn-primary">Mentés</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');

        function updateStatus(orderId) {
            statusForm.action = `/admin/orders/update-status/${orderId}`;
            statusModal.show();
        }
    </script>
</body>
</html> 