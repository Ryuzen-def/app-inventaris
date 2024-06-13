<?php
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = "Laporan_Inventaris_" . date('Ymd') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Nama Barang', 'Jumlah', 'Kelas'));

    $sql = "SELECT tb_barang.id, tb_barang.nama_barang, tb_barang.jumlah, kelas.nama_kelas FROM tb_barang JOIN kelas ON tb_barang.kelas_id = kelas.id";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include('sidebar.php'); ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
                <h2 class="mt-4">Laporan</h2>
                <p>Export data ke dalam CSV</p>
                <form method="POST" class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-csv"></i> Cetak ke CSV
                    </button>
                </form>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
