<?php
// varieties.php - Seed Varieties Management
require_once 'config.php';
requireLogin();
include 'includes/header.php';
?>
<div class="row">
    <?php include 'includes/sidebar.php'; ?>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Maize Varieties</h1>
        </div>
        <div class="alert alert-info mb-4">Only maize varieties are allowed in this system.</div>
        <div class="card">
            <div class="card-header">
                <h5>Maize Varieties List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Moisture Min (%)</th>
                                <th>Moisture Max (%)</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>H513</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H6213</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H517</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H6210</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>DH04</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H624</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H629</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>DH02</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>PH1</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>PH4</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H516</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H520</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H628</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H6218</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                            <tr><td>H614D</td><td>12.0</td><td>13.5</td><td>Maize hybrid variety</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
