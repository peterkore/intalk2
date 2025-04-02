<!-- checkout index - view -->

<h2>Checkout</h2>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="/checkout/process" method="post">
    <label for="name">Teljes nev *</label>
    <input type="text" name="name" required>

    <label for="delivery_address">Kiszallitasi cim *</label>
    <textarea name="delivery_address" required></textarea>

    <label for="billing_address">Szamlazasi cim (ha kolunbozik a kiszallitasi cimtol)</label>
    <textarea name="billing_address"></textarea>

    <label for="phone">Telefonszam *</label>
    <input type="tel" name="phone" required>

    <label for="email">Email cim *</label>
    <input type="email" name="email" required>

    <h3>Kosar</h3>
    <?php foreach ($cart as $item): ?>
        <div class="cart-item">
            <?= htmlspecialchars($item['name']) ?> x <?= $item['quantity'] ?> — $<?= $item['price'] ?>
        </div>
    <?php endforeach; ?>

    <button type="submit">Rendeles</button>
</form>
