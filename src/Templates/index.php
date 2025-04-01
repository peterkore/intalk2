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
                    <div class="table-responsive">
                        <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Termék neve</th>
                                    <th>Ár</th>
                                    <th>Elérhető darabszám</th>
                                    <th>Műveletek</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($product->getName()); ?></td>
                                        <td><?php echo number_format($product->getPrice(), 0, ',', ' '); ?> Ft</td>
                                        <td>
                                            <?php if ($product->getStock() == 0): ?>
                                                <span class="badge bg-danger">Elfogyott</span>
                                            <?php elseif ($product->getStock() <= 5): ?>
                                                <span class="badge bg-warning"><?php echo $product->getStock(); ?> db</span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><?php echo $product->getStock(); ?> db</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="/product/view/<?php echo $product->getId(); ?>" class="btn btn-sm btn-primary">Részletek</a>
                                                <?php if ($product->getStock() > 0): ?>
                                                    <button class="btn btn-sm btn-success add-to-cart" data-product-id="<?php echo $product->getId(); ?>">
                                                        Kosárba
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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

.table-responsive {
    margin-top: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
}

.product-table th,
.product-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.product-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.product-table tr:hover {
    background: #f8f9fa;
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

.action-buttons {
    display: flex;
    gap: 0.5rem;
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

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
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

@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-sm {
        width: 100%;
    }
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.category-header h2 {
    margin: 0;
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