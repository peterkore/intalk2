<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';;
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><?php echo $product ? 'Termék szerkesztése' : 'Új termék'; ?></h2>
        <a href="/admin/products" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Vissza
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo $product ? '/admin/products/edit/' . $product->getId() : '/admin/products/new'; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Név</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?php echo htmlspecialchars($product ? $product->getName() : ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control" id="sku" name="sku"
                                value="<?php echo htmlspecialchars($product ? $product->getSku() : ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Ár</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="<?php echo $product ? $product->getPrice() : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="stock" class="form-label">Készlet</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                value="<?php echo $product ? $product->getStock() : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Kategória</label>
                            <select class="form-select" id="category" name="category_id">
                                <option value="">Válassz kategóriát</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->getId(); ?>"
                                        <?php echo $product && $product->getCategory() && $product->getCategory()->getId() === $category->getId() ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->getName()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="brand" class="form-label">Márka</label>
                            <input type="text" class="form-control" id="brand" name="brand"
                                value="<?php echo htmlspecialchars($product ? $product->getBrand() : ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="model" class="form-label">Modell</label>
                            <input type="text" class="form-control" id="model" name="model"
                                value="<?php echo htmlspecialchars($product ? $product->getModel() : ''); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Leírás</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($product ? $product->getDescription() : ''); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="isActive" name="is_active"
                                    <?php echo $product && $product->isActive() ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="isActive">Aktív</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h4>Termék attribútumok</h4>
                        <div id="attributes">
                            <?php if ($product): ?>
                                <?php foreach ($product->getAttributes() as $attribute): ?>
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="attribute_names[]"
                                                value="<?php echo htmlspecialchars($attribute->getName()); ?>" placeholder="Attribútum neve">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="attribute_values[]"
                                                value="<?php echo htmlspecialchars($attribute->getValue()); ?>" placeholder="Érték">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-secondary" onclick="addAttribute()">
                            <i class="bi bi-plus-lg"></i> Attribútum hozzáadása
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function addAttribute() {
        const attributesDiv = document.getElementById('attributes');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2';
        newRow.innerHTML = `
                <div class="col-md-5">
                    <input type="text" class="form-control" name="attribute_names[]" placeholder="Attribútum neve">
                </div>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="attribute_values[]" placeholder="Érték">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;
        const button = newRow.querySelector('.btn-danger');
        button.addEventListener('click', function() {
            removeAttribute(button);
        });


        attributesDiv.appendChild(newRow);
    }

    function removeAttribute(button) {
        console.log('xxxx', button.closest('.row'))
        button.closest('.row').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const attributesDiv = document.getElementById('attributes');
        const buttons = attributesDiv.querySelectorAll('.btn-danger');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                removeAttribute(button);
            });
        })
    })
</script>
</body>

</html>