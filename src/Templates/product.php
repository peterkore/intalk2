<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Főoldal</a></li>
            <li class="breadcrumb-item"><a href="/category">Kategóriák</a></li>
            <li class="breadcrumb-item"><a href="/category/show/<?php echo $product->getCategory()->getId(); ?>"><?php echo htmlspecialchars($product->getCategory()->getName()); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product->getName()); ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6">
            <div class="product-image">
                <img src="/assets/images/no-image.jpg" alt="<?php echo htmlspecialchars($product->getName()); ?>" class="img-fluid">
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="product-title"><?php echo htmlspecialchars($product->getName()); ?></h1>
            <div class="product-price">
                <span class="price"><?php echo number_format($product->getPrice(), 0, ',', ' '); ?> Ft</span>
            </div>
            <div class="product-stock mb-3">
                <?php if ($product->getStock() == 0): ?>
                    <span class="badge bg-danger">Elfogyott</span>
                <?php elseif ($product->getStock() <= 5): ?>
                    <span class="badge bg-warning"><?php echo $product->getStock(); ?> db</span>
                <?php else: ?>
                    <span class="badge bg-success"><?php echo $product->getStock(); ?> db</span>
                <?php endif; ?>
            </div>
            <div class="product-description mb-4">
                <?php echo nl2br(htmlspecialchars($product->getDescription())); ?>
            </div>
            <div class="product-actions">
                <?php if ($product->getStock() > 0): ?>
                    <div class="input-group mb-3" style="max-width: 200px;">
                        <button class="btn btn-outline-secondary" type="button" id="decreaseQuantity">-</button>
                        <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="<?php echo $product->getStock(); ?>">
                        <button class="btn btn-outline-secondary" type="button" id="increaseQuantity">+</button>
                    </div>
                    <button class="btn btn-primary btn-lg add-to-cart" data-product-id="<?php echo $product->getId(); ?>">
                        <i class="fas fa-shopping-cart"></i> Kosárba
                    </button>
                <?php else: ?>
                    <button class="btn btn-secondary btn-lg" disabled>
                        <i class="fas fa-ban"></i> Elfogyott
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($relatedProducts)): ?>
        <div class="related-products mt-5">
            <h2>Kapcsolódó termékek</h2>
            <div class="row">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                    <div class="col-md-3">
                        <div class="card h-100">
                            <img src="/assets/images/no-image.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($relatedProduct->getName()); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($relatedProduct->getName()); ?></h5>
                                <p class="card-text"><?php echo number_format($relatedProduct->getPrice(), 0, ',', ' '); ?> Ft</p>
                                <a href="/product/<?php echo $relatedProduct->getId(); ?>" class="btn btn-primary">Részletek</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.breadcrumb {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 2rem;
}

.product-image {
    margin-bottom: 2rem;
}

.product-image img {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.product-title {
    font-size: 2rem;
    margin-bottom: 1rem;
}

.product-price {
    font-size: 1.5rem;
    color: #dc3545;
    margin-bottom: 1rem;
}

.product-stock {
    font-size: 1.1rem;
}

.product-description {
    font-size: 1.1rem;
    line-height: 1.6;
    color: #666;
}

.product-actions {
    margin-top: 2rem;
}

.input-group {
    margin-bottom: 1rem;
}

.input-group input {
    text-align: center;
}

.card {
    transition: transform 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}

.badge {
    font-size: 1rem;
    padding: 0.5rem 1rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    const addToCartBtn = document.querySelector('.add-to-cart');

    // Mennyiség növelése/csökkentése
    decreaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });

    increaseBtn.addEventListener('click', function() {
        const currentValue = parseInt(quantityInput.value);
        const maxValue = parseInt(quantityInput.max);
        if (currentValue < maxValue) {
            quantityInput.value = currentValue + 1;
        }
    });

    // Kosárba helyezés
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const quantity = parseInt(quantityInput.value);
            
            fetch(`/cart/addToCart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `quantity=${quantity}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Hiba:', error);
                alert('Hiba történt a művelet során');
            });
        });
    }
});
</script>

<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?>