<?php
include('../config/database.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Ambil role dari session
$role = $_SESSION['role'];

// CRUD operations (hanya admin yang bisa)
if ($role == 'admin' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $kelas_id = $_POST['kelas_id'];

        $sql = "INSERT INTO tb_barang (nama_barang, jumlah, kelas_id) VALUES ('$nama_barang', '$jumlah', '$kelas_id')";
        $conn->query($sql);
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM tb_barang WHERE id=$id";
        $conn->query($sql);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];

        $sql = "UPDATE tb_barang SET nama_barang='$nama_barang', jumlah='$jumlah' WHERE id=$id";
        $conn->query($sql);
    }
}

// Fetch classes for the filter dropdown
$sql_kelas = "SELECT * FROM kelas";
$result_kelas = $conn->query($sql_kelas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Barang</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .nav-link {
            font-size: 1.2em;
        }

        .nav-link .fas {
            font-size: 1.5em;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .nav-link {
                text-align: center;
                font-size: 0.8em;
            }

            .sidebar .nav-link .fas {
                font-size: 1.2em;
            }

            .sidebar .nav-link span {
                display: none;
            }
        }

        .table-responsive {
            max-height: 500px; /* Adjust the height as needed */
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: white;
            z-index: 1;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
            <?php include('sidebar.php'); ?>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <h2 class="my-4">Daftar Barang</h2>
            <?php if ($role == 'admin'): ?>
                <button class="btn btn-success mb-3" data-toggle="modal" data-target="#addModal">
                    <i class="fas fa-plus"></i> Tambah Data
                </button>
            <?php endif; ?>
            
            <!-- Filter by class -->
            <form method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="kelas_filter" class="mr-2">Filter by Kelas:</label>
                    <select class="form-control" id="kelas_filter" name="kelas_filter">
                        <option value="">All</option>
                        <?php while ($kelas = $result_kelas->fetch_assoc()) { ?>
                            <option value="<?php echo $kelas['id']; ?>"><?php echo $kelas['nama_kelas']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            
            <!-- Table for displaying barang -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $kelas_filter = isset($_GET['kelas_filter']) ? $_GET['kelas_filter'] : '';

                        $sql = "SELECT tb_barang.id, tb_barang.nama_barang, tb_barang.jumlah, kelas.nama_kelas 
                                FROM tb_barang 
                                JOIN kelas ON tb_barang.kelas_id = kelas.id";

                        if ($kelas_filter != '') {
                            $sql .= " WHERE tb_barang.kelas_id = $kelas_filter";
                        }

                        $result = $conn->query($sql);

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nama_barang']}</td>
                                    <td>{$row['jumlah']}</td>
                                    <td>{$row['nama_kelas']}</td>
                                    <td>";
                            if ($role == 'admin') {
                                echo "<button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editModal{$row['id']}'><i class='fas fa-pencil-alt'></i></button>
                                        <button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#deleteModal{$row['id']}'><i class='fas fa-trash'></i></button>";
                            }
                            echo "</td>
                                </tr>";

                            // Edit Modal
                            echo "<div class='modal fade' id='editModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='editModalLabel'>Edit Barang</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                <form method='POST'>
                                                    <div class='form-group'>
                                                        <label for='nama_barang'>Nama Barang</label>
                                                        <input type='text' class='form-control' id='nama_barang' name='nama_barang' value='{$row['nama_barang']}' required>
                                                    </div>
                                                    <div class='form-group'>
                                                        <label for='jumlah'>Jumlah</label>
                                                        <input type='number' class='form-control' id='jumlah' name='jumlah' value='{$row['jumlah']}' required>
                                                    </div>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <button type='submit' class='btn btn-primary' name='update'>Update</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";

                            // Delete Modal
                            echo "<div class='modal fade' id='deleteModal{$row['id']}' tabindex='-1' role='dialog' aria-labelledby='deleteModalLabel' aria-hidden='true'>
                                    <div class='modal-dialog' role='document'>
                                        <div class='modal-content'>
                                            <div class='modal-header'>
                                                <h5 class='modal-title' id='deleteModalLabel'>Hapus Barang</h5>
                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                    <span aria-hidden='true'>&times;</span>
                                                </button>
                                            </div>
                                            <div class='modal-body'>
                                                Apakah Anda yakin ingin menghapus barang ini?
                                                <form method='POST'>
                                                    <input type='hidden' name='id' value='{$row['id']}'>
                                                    <button type='submit' class='btn btn-danger' name='delete'>Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<!-- Add Modal -->
<div class='modal fade' id='addModal' tabindex='-1' role='dialog' aria-labelledby='addModalLabel' aria-hidden='true'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='addModalLabel'>Tambah Barang</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <div class='modal-body'>
                <form method='POST'>
                    <div class='form-group'>
                        <label for='nama_barang'>Nama Barang</label>
                        <input type='text' class='form-control' id='nama_barang' name='nama_barang' required>
                    </div>
                    <div class='form-group'>
                        <label for='jumlah'>Jumlah</label>
                        <input type='number' class='form-control' id='jumlah' name='jumlah' required>
                    </div>
                    <div class='form-group'>
                        <label for='kelas_id'>Kelas</label>
                        <select class='form-control' id='kelas_id' name='kelas_id' required>
                            <?php
                            $sql_kelas = "SELECT * FROM kelas";
                            $result_kelas = $conn->query($sql_kelas);
                            while ($kelas = $result_kelas->fetch_assoc()) {
                                echo "<option value='{$kelas['id']}'>{$kelas['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type='submit' class='btn btn-success' name='add'>Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
