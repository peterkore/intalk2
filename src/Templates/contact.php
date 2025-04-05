<?php require __DIR__ . '/Partials/header.php'; ?>

<div class="container mt-4">
    <h2>Vegye fel velünk a kapcsolatot!</h2>

    <div class="shop-info">
        <h3>Üzletünk elérhetõsége</h3>
        <!-- Google Map lokacio hozzaadase az oldalhoz -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24510.990406141696!2d19.018552636972082!3d47.4989952386635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dea0b4b80e77%3A0x5337cfc08ebfad0f!2sMammut!5e1!3m2!1sen!2sno!4v1743663225885!5m2!1sen!2sno"
            width="100%"
            height="450"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>

        <p><strong>Cím: </strong>Budapest, Lövőház u. 2-6, 1024 Magyarország</p>
    </div>

    <!-- Kapcsolatfelvetel űrlap -->
    <h3>Vegye fel velünk a kapcsolatot:</h3>

    <!-- Ha kaptunk üzenetet, megjelenítjük -->
    <?php if (isset($message)): ?>
        <!-- A siker függvényében piros vagy zöld színnel jelenik meg az üzenet -->
        <div class="alert alert-<?php if ($success): ?>success<?php else: ?>danger<?php endif; ?>" role="alert">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="/contact/submit" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Az Ön teljes neve: *</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <div class="invalid-feedback">
                Kérjük, adja meg a teljes nevét!
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email cím: *</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <div class="invalid-feedback">
                Kérjük, adja meg az email címét!
            </div>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Üzenet: *</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            <div class="invalid-feedback">
                Kérjük, írja meg az üzenetét!
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Küldés</button>
    </form>
</div>

<?php require __DIR__ . '/Partials/footer.php'; ?>

<style>
    .shop-info {
        margin-bottom: 20px;
    }

    .shop-info h3 {
        margin-bottom: 10px;
    }

    .shop-info p {
        margin-bottom: 0;
    }
    .shop-info iframe {
        margin-bottom: 20px;
    }
    .shop-info .alert {
        margin-bottom: 20px;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-size: 16px;
    }
    .shop-info .alert-success {
    background-color: #d4edda;
    color: #155724;
    border-color: #c3e6cb;
    }
    .shop-info .alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border-color: #f5c6cb;
    }
    .shop-info .form-label {
        font-weight: bold;
    }
    .shop-info .form-control {
        margin-bottom: 10px;
    }
    .shop-info .btn {
        margin-top: 10px;
    }
    .shop-info .invalid-feedback {
        display: none;
    }
    .shop-info .form-control:invalid ~ .invalid-feedback {
        display: block;
    }
    .shop-info .form-control:valid ~ .invalid-feedback {
        display: none;
    }
    .shop-info .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .shop-info .form-control:focus:invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220,53,69,.25);
    }
    .shop-info .form-control:focus:valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);
    }
    .shop-info .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .shop-info .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .shop-info .btn-primary:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.5);
    }
    .shop-info .btn-primary:active {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .shop-info .btn-primary:active:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.5);
    }
    .shop-info .btn-primary:disabled {
        background-color: #007bff;
        border-color: #007bff;
        opacity: 0.65;
    }
    .shop-info .btn-primary:disabled:hover {
        background-color: #007bff;
        border-color: #007bff;
    }
    .shop-info .btn-primary:disabled:focus {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.5);
    }
    .shop-info .btn-primary:disabled:active {
        background-color: #007bff;
        border-color: #007bff;
    } 
</style>