<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <h2>Dashboard</h2>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Termékek</h5>
                    <p class="card-text display-4"><?php echo $totalProducts; ?></p>
                    <a href="/admin/products" class="btn btn-light">Termékek kezelése</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Rendelések</h5>
                    <p class="card-text display-4"><?php echo $totalOrders; ?></p>
                    <a href="/admin/orders" class="btn btn-light">Rendelések kezelése</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Felhasználók</h5>
                    <p class="card-text display-4"><?php echo $totalUsers; ?></p>
                    <a href="/admin/users" class="btn btn-light">Felhasználók kezelése</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Kategóriák</h5>
                    <p class="card-text display-4"><?php echo $totalCategories; ?></p>
                    <a href="/admin/categories" class="btn btn-light">Kategóriák kezelése</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>