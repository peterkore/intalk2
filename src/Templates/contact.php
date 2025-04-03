<h2>Vegye fel velünk a kapcsolatot!</h2>

<div class="shop-info">
    <h3>Üzletünk elérhetõsége</h3>
    <!-- Google Map lokacio hozzaadase az oldalhoz -->
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d24510.990406141696!2d19.018552636972082!3d47.4989952386635!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4741dea0b4b80e77%3A0x5337cfc08ebfad0f!2sMammut!5e1!3m2!1sen!2sno!4v1743663225885!5m2!1sen!2sno" 
        width="600"
        height="450"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>

    <p><strong>Cím:</strong>Budapest, Lövőház u. 2-6, 1024 Magyarország</p>
</div>

<!-- Kapcsolatfelvetel formula -->
<h3>Vegye fel velünk a kapcsolatot:</h3>

<?php if (!empty($_SESSION['contact_success'])): ?>
    <div class="success"><?= $_SESSION['contact_success'] ?></div>
    <?php unset($_SESSION['contact_success']); ?>
<?php endif; ?>

<form action="/contact/submit" method="post">
    <label for="name">Az Ön teljes neve: *</label>
    <input type="text" name="name" required>

    <label for="email">Email cím: *</label>
    <input type="email" name="email" required>

    <label for="message">Üzenet: *</label>
    <textarea name="message" rows="5" required></textarea>

    <button type="submit">Küldés</button>
</form>
<style>
    .success {
        color: green;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .error {
        color: red;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .shop-info {
        margin-bottom: 20px;
    }
    .shop-info h3 {
        margin-bottom: 10px;
    }
    .shop-info p {
        margin: 5px 0;
    }
    form {
        display: flex;
        flex-direction: column;
        max-width: 600px;
    }
    form label {
        margin-bottom: 5px;
    }
    form input, form textarea {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    form button {
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    form button:hover {
        background-color: #0056b3;
    }
    .map-container {
        margin-bottom: 20px;
    }
    .map-container iframe {
        width: 100%;
        height: 300px;
        border: 0;
    }
    .map-container p {
        margin: 5px 0;
    }
    .map-container h3 {
        margin-bottom: 10px;
    }
    .btn {
        display: inline-block;
        font-weight: 400;
        color: #212529;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }