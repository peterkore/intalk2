<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'header.php';
?>

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Főoldal</a></li>
            <li class="breadcrumb-item active" aria-current="page">Keresés: <?php echo htmlspecialchars($search); ?></li>
        </ol>
    </nav>

    <h1 class="mb-4">Keresési találatok: "<?php echo htmlspecialchars($search); ?>"</h1>

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            Nincsenek találatok a keresésre: "<?php echo htmlspecialchars($search); ?>"
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <?php if ($product->getImage()): ?>
                            <img src="<?php echo htmlspecialchars($product->getImage()); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product->getName()); ?>">
                        <?php else: ?>
                            <img src="/assets/images/no-image.jpg" class="card-img-top" alt="Nincs kép">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product->getName()); ?></h5>
                            <p class="card-text"><?php echo number_format($product->getPrice(), 0, ',', ' '); ?> Ft</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="/product/<?php echo $product->getId(); ?>" class="btn btn-primary">Részletek</a>
                                <?php if ($product->getStock() > 0): ?>
                                    <button class="btn btn-success add-to-cart" data-product-id="<?php echo $product->getId(); ?>">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

.card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.card-text {
    font-size: 1.2rem;
    color: #dc3545;
    font-weight: bold;
}

.btn {
    padding: 0.5rem 1rem;
}

.alert {
    padding: 1rem;
    border-radius: 0.25rem;
    margin-bottom: 2rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch(`/cart/addToCart/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'quantity=1'
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
    });
});
</script>

<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Partials' . DIRECTORY_SEPARATOR . 'footer.php';
?> 