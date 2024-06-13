<?php
include('../config/database.php');
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Logika untuk menambahkan dan menghapus kelas (hanya untuk admin)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $role == 'admin') {
    if (isset($_POST['nama_kelas'])) {
        // Proses data form untuk menambahkan kelas
        $nama_kelas = $_POST['nama_kelas'];

        // Insert data kelas baru ke database
        $sql_insert = "INSERT INTO kelas (nama_kelas) VALUES ('$nama_kelas')";
        if ($conn->query($sql_insert) === TRUE) {
            echo "Kelas baru berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
    } elseif (isset($_POST['delete_kelas_id'])) {
        // Logika untuk menghapus kelas
        $kelas_id = $_POST['delete_kelas_id'];

        // Menghapus barang-barang yang terkait dengan kelas tersebut
        $sql_delete_barang = "DELETE FROM tb_barang WHERE kelas_id = $kelas_id";
        $conn->query($sql_delete_barang);

        // Menghapus kelas
        $sql_delete_kelas = "DELETE FROM kelas WHERE id = $kelas_id";
        if ($conn->query($sql_delete_kelas) === TRUE) {
            echo "Kelas berhasil dihapus!";
        } else {
            echo "Error: " . $sql_delete_kelas . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h2 class="h2">Dashboard</h2>
                </div>
                
                <!-- Form to add new class (only for admin) -->
                <?php if ($role == 'admin'): ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama_kelas">Nama Kelas</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" required>
                    </div>
                    <button type="submit" class="btn btn-primary mb-3">Tambah Kelas</button>
                </form>
                <?php endif; ?>

                <!-- Display classes and their items -->
                <?php
                $colors = [
                    'card-color-1', 'card-color-2', 'card-color-3', 'card-color-4',
                    'card-color-5', 'card-color-6', 'card-color-7', 'card-color-8'
                ];
                $sql = "SELECT * FROM kelas";
                $result = $conn->query($sql);
                $colorIndex = 0;

                while ($row = $result->fetch_assoc()) {
                    $colorClass = $colors[$colorIndex % count($colors)];
                    $colorIndex++;

                    echo "<div class='card mt-4 $colorClass'>";
                    echo "<div class='card-header d-flex justify-content-between align-items-center'>";
                    echo "<h5 class='card-title'>Kelas " . $row['nama_kelas'] . "</h5>";
                    if ($role == 'admin') {
                        echo "<form method='POST' action=''>";
                        echo "<input type='hidden' name='delete_kelas_id' value='" . $row['id'] . "'>";
                        echo "<button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus kelas ini?\");'><i class='fas fa-trash-alt'></i></button>";
                        echo "</form>";
                    }
                    echo "</div>";
                    echo "<div class='card-body'>";
                    $kelas_id = $row['id'];
                    $sql_barang = "SELECT * FROM tb_barang WHERE kelas_id=$kelas_id";
                    $result_barang = $conn->query($sql_barang);
                    echo "<ul class='list-group list-group-flush'>";
                    while ($barang = $result_barang->fetch_assoc()) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                        echo $barang['nama_barang'];
                        echo "<span class='badge badge-primary badge-pill'>Jumlah: " . $barang['jumlah'] . "</span>";
                        echo "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </main>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
