<?php
use Webshop\Model\Order;
require __DIR__ . '/../Partials/header.php'; ?>

<div class="container">
    <h1>Rendeléseim</h1>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            Még nincs rendelésed.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Rendelés azonosító</th>
                        <th>Dátum</th>
                        <th>Összeg</th>
                        <th>Státusz</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order->getId(); ?></td>
                            <td><?php echo $order->getCreatedAt()->format('Y.m.d H:i'); ?></td>
                            <td><?php echo number_format($order->getTotalAmount(), 0, ',', ' '); ?> Ft</td>
                            <td>
                                <?php
                                $statusClass = match($order->getStatus()) {
                                    Order::STATUS_PENDING => 'warning',
                                    Order::STATUS_PROCESSING => 'info',
                                    Order::STATUS_SHIPPED => 'primary',
                                    Order::STATUS_DELIVERED => 'success',
                                    Order::STATUS_CANCELLED => 'danger',
                                    default => 'secondary'
                                };
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>">
                                    <?php echo $order->getStatus(); ?>
                                </span>
                            </td>
                            <td>
                                <a href="/order/show/<?php echo $order->getId(); ?>" class="btn btn-sm btn-primary">
                                    Részletek
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
.table {
    width: 100%;
    margin-bottom: 1rem;
    background-color: transparent;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
}

.table tbody tr:hover {
    background-color: rgba(0,0,0,.075);
}

.badge {
    display: inline-block;
    padding: 0.25em 0.4em;
    font-size: 75%;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.bg-warning {
    background-color: #ffc107;
    color: #000;
}

.bg-info {
    background-color: #17a2b8;
    color: #fff;
}

.bg-primary {
    background-color: #007bff;
    color: #fff;
}

.bg-success {
    background-color: #28a745;
    color: #fff;
}

.bg-danger {
    background-color: #dc3545;
    color: #fff;
}

.bg-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}
</style>

<?php require __DIR__ . '/../Partials/footer.php'; ?> 