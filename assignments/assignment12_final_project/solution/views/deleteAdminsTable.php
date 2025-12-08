<?php
// ==============================
// file: solution/views/deleteAdminsTable.php
// ==============================
declare(strict_types=1);

require_once __DIR__ . '/../includes/security.php';
require_login();

require_once __DIR__ . '/../classes/Db_conn.php';
require_once __DIR__ . '/../controllers/addAdminProc.php'; // we reuse list_admins()

// Handle delete POST – quietly, no success/fail banner
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && !empty($_POST['ids'])) {
    $pdo = (new Db_conn())->dbOpen();
    $ids = array_map('intval', $_POST['ids']);

    if ($ids) {
        $in  = implode(',', array_fill(0, count($ids), '?'));
        $stm = $pdo->prepare("DELETE FROM admins WHERE id IN ($in)");
        // We don't care about the return value here – just execute
        $stm->execute($ids);
    }
}

render_page('Delete Admin(s)', function () {
    // Always fetch the fresh list after any delete
    $rows = list_admins(); ?>
    
    <h1 class="h3 mb-3">Delete Admin(s)</h1>

    <?php if (!$rows): ?>
        <div class="alert alert-secondary">There are no records to display</div>
    <?php else: ?>
        <form method="post">
            <div class="mb-2">
                <button class="btn btn-danger btn-sm" name="delete" value="1">Delete</button>
            </div>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['fname'] . ' ' . $r['lname']) ?></td>
                                <td><?= htmlspecialchars($r['email']) ?></td>
                                <td><?= htmlspecialchars($r['status']) ?></td>
                                <td class="text-center">
                                    <input type="checkbox" name="ids[]" value="<?= (int)$r['id'] ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </form>
    <?php endif; ?>
<?php });
