<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kategória szerkesztése</h2>
        <a href="/admin/categories" class="btn btn-secondary">Vissza</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Név</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($category->getName()); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Leírás</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($category->getDescription() ?? ''); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Mentés</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>