<?php require __DIR__ . '/Partials/header.php'; ?>

<div class="banner">
    <div class="container">
        <h1>Üdvözöljük az Állatwebshopban!</h1>
        <p>Fedezze fel kiváló termékeinket kedvenc háziállatához</p>
    </div>
</div>

<div class="category-nav">
    <div class="container">
        <nav class="category-scroll">
            <?php if (empty($categories)): ?>
                <p>Nincsenek kategóriák</p>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <a href="#category-<?php echo $category->getId(); ?>" class="category-link">
                        <?php echo htmlspecialchars($category->getName()); ?>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </nav>
    </div>
</div>

<main class="container">
    <?php if (empty($categories)): ?>
        <div class="alert alert-info">
            Nincsenek elérhető kategóriák.
        </div>
    <?php else: ?>
        <?php foreach ($categories as $category): ?>
            <section id="category-<?php echo $category->getId(); ?>" class="category-section">
                <div class="category-header">
                    <h2><?php echo htmlspecialchars($category->getName()); ?></h2>
                    <a href="/category/show/<?php echo $category->getId(); ?>" class="btn btn-primary">Több</a>
                </div>
                <?php 
                $products = $productsByCategory[$category->getId()] ?? [];
                if (empty($products)): 
                ?>
                    <div class="alert alert-info">
                        Nincsenek termékek ebben a kategóriában.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 product-card">
                                    <div class="product-image">
                                        <img src="/<?= htmlspecialchars($product->getThumbnailPath() ?: 'images/no-image-thumb.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($product->getName()) ?>">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product->getName()) ?></h5>
                                        <p class="card-text price"><?= number_format($product->getPrice(), 0, ',', ' ') ?> Ft</p>
                                        <div class="stock-info mb-2">
                                            <?php if ($product->getStock() == 0): ?>
                                                <span class="badge bg-danger">Elfogyott</span>
                                            <?php elseif ($product->getStock() <= 5): ?>
                                                <span class="badge bg-warning"><?= $product->getStock() ?> db</span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?= $product->getStock() ?> db</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <a href="/product/view/<?= $product->getId() ?>" class="btn btn-primary">Részletek</a>
                                            <?php if ($product->getStock() > 0): ?>
                                                <button class="btn btn-success add-to-cart" data-product-id="<?= $product->getId() ?>">
                                                    Kosárba
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<style>
.banner {
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('/assets/images/banner.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0;
    text-align: center;
    margin-bottom: 2rem;
}

.category-nav {
    background: #f8f9fa;
    padding: 1rem 0;
    position: sticky;
    top: 0;
    z-index: 100;
}

.category-scroll {
    display: flex;
    overflow-x: auto;
    gap: 1rem;
    padding: 0.5rem 0;
}

.category-link {
    white-space: nowrap;
    padding: 0.5rem 1rem;
    background: white;
    border-radius: 20px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
}

.category-link:hover {
    background: #007bff;
    color: white;
}

.category-section {
    margin: 3rem 0;
    scroll-margin-top: 100px;
}

.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.product-image {
    height: 200px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.card-title {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    height: 2.4rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #dc3545;
    margin-bottom: 0.5rem;
}

.stock-info {
    margin-bottom: 1rem;
}

.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 4px;
    font-weight: 500;
}

.bg-success {
    background: #28a745;
    color: white;
}

.bg-warning {
    background: #ffc107;
    color: #000;
}

.bg-danger {
    background: #dc3545;
    color: white;
}

.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn:hover {
    opacity: 0.9;
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.category-header h2 {
    margin: 0;
    font-size: 1.8rem;
    color: #333;
}

@media (max-width: 768px) {
    .col-md-3 {
        margin-bottom: 1.5rem;
    }
    
    .product-card {
        margin-bottom: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kosárba helyezés kezelése
    document.querySelectorAll('.add-to-cart').forEach(button => {
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
                    // Sikeres hozzáadás - modal megjelenítése
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

<?php require __DIR__ . '/Partials/footer.php'; ?>