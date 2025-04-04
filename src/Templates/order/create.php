<?php
use Webshop\Model\Order;
use Webshop\Model\Product;
require __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h1>Rendelés létrehozása</h1>
    
    <div class="row">
        <div class="col-md-8">
            <form method="post" action="/order/create">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Számlázási adatok</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <div class="alert alert-warning">
                                Nincs mentett címed. Kérjük, <a href="/address/create">adj hozzá egy címet</a> a rendeléshez.
                            </div>
                        <?php else: ?>
                            <?php foreach ($addresses as $address): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="billing_address_id" 
                                           id="billing_address_<?php echo $address->getId(); ?>" 
                                           value="<?php echo $address->getId(); ?>" required>
                                    <label class="form-check-label" for="billing_address_<?php echo $address->getId(); ?>">
                                        <strong><?php echo htmlspecialchars($address->getName()); ?></strong><br>
                                        <?php echo htmlspecialchars($address->getStreet()); ?><br>
                                        <?php echo htmlspecialchars($address->getCity()); ?><br>
                                        <?php echo htmlspecialchars($address->getPostalCode()); ?><br>
                                        <?php echo htmlspecialchars($address->getCountry()); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Szállítási adatok</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($addresses)): ?>
                            <div class="alert alert-warning">
                                Nincs mentett címed. Kérjük, <a href="/address/create">adj hozzá egy címet</a> a rendeléshez.
                            </div>
                        <?php else: ?>
                            <?php foreach ($addresses as $address): ?>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="shipping_address_id" 
                                           id="shipping_address_<?php echo $address->getId(); ?>" 
                                           value="<?php echo $address->getId(); ?>" required>
                                    <label class="form-check-label" for="shipping_address_<?php echo $address->getId(); ?>">
                                        <strong><?php echo htmlspecialchars($address->getName()); ?></strong><br>
                                        <?php echo htmlspecialchars($address->getStreet()); ?><br>
                                        <?php echo htmlspecialchars($address->getCity()); ?><br>
                                        <?php echo htmlspecialchars($address->getPostalCode()); ?><br>
                                        <?php echo htmlspecialchars($address->getCountry()); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Fizetési és szállítási mód</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Fizetési mód</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Válassz fizetési módot</option>
                                <option value="<?php echo Order::PAYMENT_METHOD_CASH; ?>">Utánvét</option>
                                <option value="<?php echo Order::PAYMENT_METHOD_BANK_TRANSFER; ?>">Banki átutalás</option>
                                <option value="<?php echo Order::PAYMENT_METHOD_CREDIT_CARD; ?>">Bankkártya</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="shipping_method" class="form-label">Szállítási mód</label>
                            <select class="form-select" id="shipping_method" name="shipping_method" required>
                                <option value="">Válassz szállítási módot</option>
                                <option value="<?php echo Order::SHIPPING_METHOD_STANDARD; ?>">Standard szállítás</option>
                                <option value="<?php echo Order::SHIPPING_METHOD_EXPRESS; ?>">Expressz szállítás</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary" <?php echo empty($addresses) ? 'disabled' : ''; ?>>
                        Rendelés véglegesítése
                    </button>
                    <a href="/cart" class="btn btn-secondary">Vissza a kosárhoz</a>
                </div>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Rendelés összesítése</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <?php foreach ($_SESSION['cart'] as $productId => $quantity): 
                                    $product = $this->entityManager->getRepository(Product::class)->find($productId);
                                    if ($product):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->getName()); ?></td>
                                        <td><?php echo $quantity; ?> db</td>
                                        <td><?php echo number_format($product->getPrice() * $quantity, 0, ',', ' '); ?> Ft</td>
                                    </tr>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                                <tr>
                                    <td colspan="2" class="text-end"><strong>Összesen:</strong></td>
                                    <td>
                                        <strong>
                                            <?php
                                            $total = 0;
                                            foreach ($_SESSION['cart'] as $productId => $quantity) {
                                                $product = $this->entityManager->getRepository(Product::class)->find($productId);
                                                if ($product) {
                                                    $total += $product->getPrice() * $quantity;
                                                }
                                            }
                                            echo number_format($total, 0, ',', ' ') . ' Ft';
                                            ?>
                                        </strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0,0,0,.125);
    border-radius: 0.25rem;
}

.card-header {
    padding: 0.5rem 1rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: 1px solid rgba(0,0,0,.125);
}

.card-body {
    flex: 1 1 auto;
    padding: 1rem;
}

.form-check {
    position: relative;
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5rem;
}

.form-check-input {
    position: absolute;
    margin-top: 0.3rem;
    margin-left: -1.5rem;
}

.form-check-label {
    margin-bottom: 0;
}

.form-label {
    margin-bottom: 0.5rem;
}

.form-select {
    display: block;
    width: 100%;
    padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    appearance: none;
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
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

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}

.btn:disabled {
    opacity: 0.65;
    pointer-events: none;
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

.text-end {
    text-align: right;
}
</style>

<?php require __DIR__ . '/../partials/footer.php'; ?> 