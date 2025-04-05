<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'admin_header.php';
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Felhasználók</h2>
        <a href="/admin/users/new" class="btn btn-primary">Új felhasználó</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Név</th>
                            <th>Email</th>
                            <th>Regisztráció dátuma</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user->getId(); ?></td>
                                <td><?= htmlspecialchars($user->getName()); ?></td>
                                <td><?= htmlspecialchars($user->getEmail()); ?></td>
                                <td><?= $user->getCreatedAt()->format('Y-m-d H:i'); ?></td>
                                <td>
                                    <a href="/admin/users/view/<?= $user->getId(); ?>" class="btn btn-sm btn-info">Részletek</a>
                                    <a href="/admin/users/edit/<?= $user->getId(); ?>" class="btn btn-sm btn-warning">Szerkesztés</a>
                                    <a href="/admin/users/delete/<?= $user->getId(); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Biztosan törölni szeretnéd ezt a felhasználót?')">Törlés</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>