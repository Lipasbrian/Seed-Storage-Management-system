<?php
// includes/header.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenya Seed - Seed Storage Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
</head>
<body class="dashboard-page">
<div class="dashboard-watermark">🍀</div>
<nav class="navbar navbar-dark navbar-branded sticky-top">
    <div class="container-fluid">
        <button class="navbar-toggler me-3" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand me-auto" href="dashboard.php">
            <i class="bi bi-sheaf"></i> Kenya Seed Storage
        </a>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white d-none d-md-inline">
                <i class="bi bi-person-circle"></i> <?php echo isset($_SESSION['full_name']) ? htmlspecialchars($_SESSION['full_name']) : 'User'; ?>
            </span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="d-flex" style="min-height: calc(100vh - 56px);">
    <!-- Sidebar -->
    <nav class="sidebar bg-ks-light-green" id="sidebar">
        <div class="p-3">
            <h5 class="text-white mb-3">
                <i class="bi bi-list"></i> Menu
            </h5>
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboard.php">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="farmers.php">
                        <i class="bi bi-people"></i> Farmers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="permits.php">
                        <i class="bi bi-file-text"></i> Permits
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="deliveries.php">
                        <i class="bi bi-truck"></i> Deliveries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="bins.php">
                        <i class="bi bi-box"></i> Bins
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="varieties.php">
                        <i class="bi bi-flower1"></i> Varieties
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="users.php">
                        <i class="bi bi-gear"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="reports_daily.php">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1 dashboard-container p-4">
