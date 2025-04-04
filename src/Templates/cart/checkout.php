<?php require __DIR__ . '/../../Partials/header.php'; ?>

<div class="container">
    <h1>Rendelés véglegesítése</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Rendelési adatok</h3>
                </div>
                <div class="card-body">
                    <form action="/cart/placeOrder" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Teljes név</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user->getName()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail cím</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->getEmail()); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefonszám</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Szállítási cím</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Fizetési mód</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Válasszon fizetési módot</option>
                                <option value="cash">Utánvét</option>
                                <option value="bank_transfer">Banki átutalás</option>
                                <option value="card">Bankkártya</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_method" class="form-label">Szállítási mód</label>
                            <select class="form-select" id="shipping_method" name="shipping_method" required>
                                <option value="">Válasszon szállítási módot</option>
                                <option value="standard">Standard szállítás</option>
                                <option value="express">Expressz szállítás</option>
                            </select>
                        </div>
                        <?php if (strpos($user->getEmail(), 'guest_') === 0): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Vendég felhasználóként rendel. A rendelés után regisztrálhatsz a teljes funkcionalitásért.
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="btn btn-primary">Rendelés véglegesítése</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Rendelés összesítése</h3>
                </div>
                <div class="card-body">
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $productId => $quantity):
                        $product = $this->entityManager->find(Product::class, $productId);
                        if ($product):
                            $subtotal = $product->getPrice() * $quantity;
                            $total += $subtotal;
                    ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><?php echo htmlspecialchars($product->getName()); ?> x <?php echo $quantity; ?></span>
                            <span><?php echo number_format($subtotal, 0, ',', ' '); ?> Ft</span>
                        </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Összesen:</strong>
                        <strong><?php echo number_format($total, 0, ',', ' '); ?> Ft</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    margin-bottom: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0,0,0,0.125);
}

.form-label {
    font-weight: 500;
}

.form-control:focus,
.form-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

.btn-primary {
    width: 100%;
    padding: 0.75rem;
    font-size: 1.1rem;
}

.alert {
    margin-bottom: 1rem;
    padding: 0.75rem 1.25rem;
    border-radius: 0.25rem;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.alert i {
    margin-right: 0.5rem;
}
</style>

<?php require __DIR__ . '/../../Partials/footer.php'; ?> 