<?php
include 'db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));

    $sql  = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':name' => $name, ':email' => $email]);

    header('Location: index.php?toast=added');
    exit;
}

$sql   = "SELECT * FROM users";
$users = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

// Toast config
$toastMessages = [
    'added'   => ['msg' => 'User added successfully.',   'icon' => 'bi-person-plus-fill',  'color' => 'bg-success'],
    'updated' => ['msg' => 'User updated successfully.', 'icon' => 'bi-pencil-square',      'color' => 'bg-primary'],
    'deleted' => ['msg' => 'User deleted successfully.', 'icon' => 'bi-trash-fill',         'color' => 'bg-danger'],
];

$toast = isset($_GET['toast']) && isset($toastMessages[$_GET['toast']])
    ? $toastMessages[$_GET['toast']]
    : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD APP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
        <div id="liveToast" class="toast align-items-center text-white border-0 <?= $toast ? $toast['color'] : '' ?>"
            role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <?php if ($toast): ?>
                    <i class="bi <?= $toast['icon'] ?>"></i>
                    <?= $toast['msg'] ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4 text-center">PHP CRUD APP</h1>

        <div class="row g-4">

            <!-- Form (Left) -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Add User</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="index.php">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="Enter email" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table (Right) -->
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this user?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($toast): ?>
    <script>
    const toastEl = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastEl, {
        delay: 3000
    });
    toast.show();
    </script>
    <?php endif; ?>
</body>

</html>