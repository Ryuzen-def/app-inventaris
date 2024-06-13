<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Inventory</title>
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
            width: 200px;
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
            display: flex;
            align-items: center;
        }

        .nav-link .fas {
            font-size: 1.5em;
            margin-right: 10px;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .nav-link {
                text-align: center;
                font-size: 0.8em;
                justify-content: center;
            }

            .sidebar .nav-link .fas {
                font-size: 1.2em;
                margin-right: 0;
            }

            .sidebar .nav-link span {
                display: none;
            }
        }
    </style>
</head>
<body>

<nav class="col-md-2 d-none d-md-block bg-light sidebar">
    <div class="sidebar-sticky">
        <div class="text-center mb-4">
            <h2>Inventory</h2>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="daftar_barang.php">
                    <i class="fas fa-boxes"></i>
                    <span>Daftar Barang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="laporan.php">
                    <i class="fas fa-file-excel"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Content goes here -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2"></div> <!-- Spacer to account for sidebar -->
        <main role="main" class="col-md-10 ml-sm-auto px-4">
            <!-- Main content -->
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
