<?php
require __DIR__ . '/../Partials/header.php'; ?>

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


                        <h4 class="mb-3 mt-4">Számlázási cím</h4>
                        <?php $billingAddress = $user ? $user->getDefaultAddress('billing') : ''; ?>
                        <div class="mb-3">
                            <label for="billing_street" class="form-label">Utca, házszám</label>
                            <input type="text" class="form-control" id="billing_street" name="billing_street" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getStreet() : ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="billing_city" class="form-label">Város</label>
                            <input type="text" class="form-control" id="billing_city" name="billing_city" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getCity() : ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="billing_zip_code" class="form-label">Irányítószám</label>
                            <input type="text" class="form-control" id="billing_zip_code" name="billing_zip_code" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getZipCode() : ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="billing_country" class="form-label">Ország</label>
                            <input type="text" class="form-control" id="billing_country" name="billing_country" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getCountry() : ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="billing_phone" class="form-label">Telefonszám</label>
                            <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="<?php echo htmlspecialchars($billingAddress ? $billingAddress->getPhone() : ''); ?>">
                        </div>

                        <div class="mb-3">
                            <h4 class="mb-3 mt-4">Szállítási cím</h4>
                            <?php $shippingAddress = $user ? $user->getDefaultAddress('shipping') : ''; ?>
                            <div class="mb-3">
                                <label for="shipping_street" class="form-label">Utca, házszám</label>
                                <input type="text" class="form-control" id="shipping_street" name="shipping_street" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getStreet() : ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_city" class="form-label">Város</label>
                                <input type="text" class="form-control" id="shipping_city" name="shipping_city" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getCity() : ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_zip_code" class="form-label">Irányítószám</label>
                                <input type="text" class="form-control" id="shipping_zip_code" name="shipping_zip_code" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getZipCode() : ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_country" class="form-label">Ország</label>
                                <input type="text" class="form-control" id="shipping_country" name="shipping_country" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getCountry() : ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="shipping_phone" class="form-label">Telefonszám</label>
                                <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone" value="<?php echo htmlspecialchars($shippingAddress ? $shippingAddress->getPhone() : ''); ?>">
                            </div>
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
                    foreach ($products as $product): ?>
                        <?php
                        if (method_exists($product, 'getId')):
                            $id = $product->getId();
                            $subtotal = $product->getPrice() * $cart[$id];
                            $total += $subtotal;
                        ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?php echo htmlspecialchars($product->getName()); ?> x <?php echo $quantity; ?></span>
                                <span><?php echo number_format($subtotal, 0, ',', ' '); ?> Ft</span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Összesen:</strong>
                        <strong><?= number_format($total, 0, ',', ' '); ?> Ft</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }

    .form-label {
        font-weight: 500;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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

<?php require __DIR__ . '/../Partials/footer.php'; ?>