<?php
if (isset($csrfToken)) {
    header('X-CSRF-Token: ' . $csrfToken);
}

if (isset($statusCode)) {
    http_response_code($statusCode);
}

?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Állatwebshop'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html {
            background-color: #212529;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            min-width: 18px;
            text-align: center;
        }
        .cart-icon {
            position: relative;
        }
    </style>
</head>

<body>
    <header class="bg-dark text-white">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="/">Állatwebshop</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Kezdőlap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/products">Termékek</a>
                        </li>
                        <li class="nav-item">
                            <?php if (isset($_SESSION['user']['loggedin_id'])): ?>
                                <a class="nav-link" href="/order" >Rendeléseim</a>
                            <?php endif; ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about">Rólunk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact">Kapcsolat</a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <a href="/cart" class="btn btn-outline-light me-2 cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <?php
                            $cartCount = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $quantity) {
                                    $cartCount += $quantity;
                                }
                            }
                            if ($cartCount > 0):
                            ?>
                                <span class="cart-count"><?= $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <?php if (isset($_SESSION['user']['loggedin_id'])): ?>
                            <a href="/profile" class="btn btn-outline-light me-2">
                                <i class="fas fa-user"></i> Profil
                            </a>
                            <a href="/logout" class="btn btn-outline-light">Kijelentkezés</a>
                        <?php else: ?>
                            <a href="/login" class="btn btn-outline-light">Bejelentkezés</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Sikeres kosárba helyezés modal -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sikeres művelet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="successMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezárás</button>
                    <a href="/cart" class="btn btn-primary">Kosár megtekintése</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Modal kezelése
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        
        // Globális függvény a sikeres kosárba helyezéshez
        function showSuccessMessage(message) {
            document.getElementById('successMessage').textContent = message;
            successModal.show();
        }
    </script>
</body>
</html>