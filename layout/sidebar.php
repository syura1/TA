<?php

if (!isset($menu)) {
    $menu = "";
}

?>

<div class="sidebar" id="sidebar">

    <div class="logo">

        <h4>

            <i class="bi bi-bicycle"></i>

            SPK TOPSIS

        </h4>

        <small>Aksesoris Motor</small>

    </div>

    <div class="sidebar-menu">

        <a href="../admin/dashboard.php" class="<?= $menu == 'dashboard' ? 'active' : ''; ?>">
            <i class="bi bi-house-door-fill"></i>
            Dashboard
        </a>

        <a href="../admin/kriteria.php" class="<?= $menu == 'kriteria' ? 'active' : ''; ?>">
            <i class="bi bi-list-check"></i>
            Data Kriteria
        </a>

        <a href="../admin/alternatif.php" class="<?= $menu == 'alternatif' ? 'active' : ''; ?>">
            <i class="bi bi-box-seam"></i>
            Data Alternatif
        </a>

        <a href="../admin/penilaian.php" class="<?= $menu == 'penilaian' ? 'active' : ''; ?>">
            <i class="bi bi-ui-checks-grid"></i>
            Penilaian
        </a>

        <a href="../admin/perhitungan.php" class="<?= $menu == 'perhitungan' ? 'active' : ''; ?>">
            <i class="bi bi-calculator"></i>
            Perhitungan TOPSIS
        </a>

        <a href="../admin/ranking.php" class="<?= $menu == 'ranking' ? 'active' : ''; ?>">
            <i class="bi bi-bar-chart-fill"></i>
            Hasil Ranking
        </a>

        <a href="../admin/report.php" class="<?= $menu == 'report' ? 'active' : ''; ?>">
            <i class="bi bi-file-earmark-text"></i>
            Report
        </a>

        <a href="../logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Logout
        </a>

    </div>

</div>