<?php
use Webshop\Model\Order;
require __DIR__ . '/../Partials/header.php'; ?>

<div class="container">
    <h1>Rendelés részletei</h1>
    
    <div class="order-info">
        <div class="row">
            <div class="col-md-6">
                <h3>Rendelés adatai</h3>
                <table class="table">
                    <tr>
                        <th>Rendelés azonosító:</th>
                        <td>#<?php echo $order->getId(); ?></td>
                    </tr>
                    <tr>
                        <th>Rendelés dátuma:</th>
                        <td><?php echo $order->getCreatedAt()->format('Y.m.d H:i'); ?></td>
                    </tr>
                    <tr>
                        <th>Státusz:</th>
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
                    </tr>
                    <tr>
                        <th>Fizetési mód:</th>
                        <td><?php echo $order->getPaymentMethod(); ?></td>
                    </tr>
                    <tr>
                        <th>Szállítási mód:</th>
                        <td><?php echo $order->getShippingMethod(); ?></td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <h3>Számlázási adatok</h3>
                <div class="address">
                    <p>
                        <?php echo htmlspecialchars($order->getBillingAddress()->getName()); ?><br>
                        <?php echo htmlspecialchars($order->getBillingAddress()->getStreet()); ?><br>
                        <?php echo htmlspecialchars($order->getBillingAddress()->getCity()); ?><br>
                        <?php echo htmlspecialchars($order->getBillingAddress()->getPostalCode()); ?><br>
                        <?php echo htmlspecialchars($order->getBillingAddress()->getCountry()); ?>
                    </p>
                </div>
                
                <h3>Szállítási adatok</h3>
                <div class="address">
                    <p>
                        <?php echo htmlspecialchars($order->getShippingAddress()->getName()); ?><br>
                        <?php echo htmlspecialchars($order->getShippingAddress()->getStreet()); ?><br>
                        <?php echo htmlspecialchars($order->getShippingAddress()->getCity()); ?><br>
                        <?php echo htmlspecialchars($order->getShippingAddress()->getPostalCode()); ?><br>
                        <?php echo htmlspecialchars($order->getShippingAddress()->getCountry()); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="order-items">
        <h3>Rendelt termékek</h3>
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
                            <td><?php echo $item->getQuantity(); ?> db</td>
                            <td><?php echo number_format($item->getPrice(), 0, ',', ' '); ?> Ft</td>
                            <td><?php echo number_format($item->getSubtotal(), 0, ',', ' '); ?> Ft</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Összesen:</strong></td>
                        <td><strong><?php echo number_format($order->getTotalAmount(), 0, ',', ' '); ?> Ft</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.order-info {
    margin-bottom: 2rem;
}

.address {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}

.address p {
    margin: 0;
}

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

.text-end {
    text-align: right;
}
</style>

<?php require __DIR__ . '/../Partials/footer.php'; ?> 