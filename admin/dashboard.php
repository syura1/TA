    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "dashboard";
    // Total Kriteria
    $qKriteria = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kriteria");
    $totalKriteria = mysqli_fetch_assoc($qKriteria)['total'];

    // Total Alternatif
    $qAlternatif = mysqli_query($conn, "SELECT COUNT(*) AS total FROM alternatif");
    $totalAlternatif = mysqli_fetch_assoc($qAlternatif)['total'];

    // Total Penilaian
    $qPenilaian = mysqli_query($conn, "SELECT COUNT(*) AS total FROM penilaian");
    $totalPenilaian = mysqli_fetch_assoc($qPenilaian)['total'];

    // Ranking Terbaik
    $qTerbaik = mysqli_query($conn, " SELECT
            a.nama_alternatif,
            r.nilai_preferensi
        FROM ranking r
        JOIN alternatif a
        ON r.id_alternatif = a.id_alternatif
        ORDER BY r.ranking ASC
        LIMIT 1
        ");

    $dataTerbaik = mysqli_fetch_assoc($qTerbaik);

    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">
                    <i class="bi bi-house-door"></i> Dashboard
                </span>

                <div class="ms-auto">

                    <span class="fw-semibold">

                        <i class="bi bi-person-circle"></i>

                        Admin

                    </span>

                </div>

            </div>

        </nav>

        <div class="container-fluid mt-4">

            <!-- Judul Halaman -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h3 class="fw-bold mb-1">
                        Dashboard
                    </h3>

                    <small class="text-muted">

                        Selamat datang di Sistem Pendukung Keputusan Menentukan Aksesoris Motor Terbaik

                    </small>

                </div>

            </div>

            <!-- Card Statistik -->
            <div class="row">

                <!-- Data Kriteria -->
                <div class="col-lg-3 col-md-6 mb-4 d-flex">

                    <div class="card shadow-sm dashboard-card flex-fill"
                        onclick="window.location='kriteria.php'">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    Data Kriteria
                                </h6>

                                <h3 class="fw-bold mb-1">
                                    <?= $totalKriteria; ?>
                                </h3>

                                <small class="text-muted">
                                    Total Kriteria
                                </small>

                            </div>

                            <i class="bi bi-list-check text-primary"
                                style="font-size:48px;"></i>

                        </div>

                    </div>

                </div>

                <!-- Data Alternatif -->
                <div class="col-lg-3 col-md-6 mb-4 d-flex">

                    <div class="card shadow-sm dashboard-card flex-fill"
                        onclick="window.location='alternatif.php'">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    Data Alternatif
                                </h6>

                                <h3 class="fw-bold mb-1">
                                    <?= $totalAlternatif; ?>
                                </h3>

                                <small class="text-muted">
                                    Total Alternatif
                                </small>

                            </div>

                            <i class="bi bi-box-seam text-success"
                                style="font-size:48px;"></i>

                        </div>

                    </div>

                </div>

                <!-- Data Penilaian -->
                <div class="col-lg-3 col-md-6 mb-4 d-flex">

                    <div class="card shadow-sm dashboard-card flex-fill"
                        onclick="window.location='penilaian.php'">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    Data Penilaian
                                </h6>

                                <h3 class="fw-bold mb-1">
                                    <?= $totalPenilaian; ?>
                                </h3>

                                <small class="text-muted">
                                    Total Penilaian
                                </small>

                            </div>

                            <i class="bi bi-star-fill text-warning"
                                style="font-size:48px;"></i>

                        </div>

                    </div>

                </div>

                <!-- Ranking -->
                <div class="col-lg-3 col-md-6 mb-4 d-flex">

                    <div class="card shadow-sm dashboard-card flex-fill"
                        onclick="window.location='ranking.php'">

                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>

                                <h6 class="text-muted mb-2">
                                    Ranking Terbaik
                                </h6>

                                <?php if ($dataTerbaik) { ?>

                                    <h5 class="fw-bold mb-1">
                                        <?= $dataTerbaik['nama_alternatif']; ?>
                                    </h5>

                                    <small class="text-muted">
                                        Nilai :
                                        <strong class="text-success">
                                            <?= number_format($dataTerbaik['nilai_preferensi'], 4); ?>
                                        </strong>
                                    </small>

                                <?php } ?>

                            </div>

                            <i class="bi bi-trophy-fill text-danger"
                                style="font-size:48px;"></i>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Tabel & Informasi -->
            <div class="row">

                <!-- Tabel -->
                <div class="col-lg-8 mb-4">

                    <div class="card">

                        <div class="card-header">
                            5 Alternatif Terbaik
                        </div>

                        <div class="card-body">

                            <div class="table-responsive">

                                <table class="table table-hover align-middle">

                                    <thead>

                                        <tr>
                                            <th width="80">Ranking</th>
                                            <th>Nama Alternatif</th>
                                            <th width="120">Nilai</th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $ranking = mysqli_query($conn, " SELECT
                                                a.nama_alternatif,
                                                r.nilai_preferensi,
                                                r.ranking
                                            FROM ranking r
                                            JOIN alternatif a
                                            ON r.id_alternatif = a.id_alternatif
                                            ORDER BY r.ranking ASC
                                            LIMIT 5
                                            ");

                                        while ($row = mysqli_fetch_assoc($ranking)) {

                                        ?>

                                            <tr>

                                                <td><?= $row['ranking']; ?></td>

                                                <td><?= $row['nama_alternatif']; ?></td>

                                                <td><?= number_format($row['nilai_preferensi'], 4); ?></td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- Informasi -->
                <div class="col-lg-4 mb-4">

                    <div class="card">

                        <div class="card-header">
                            Informasi Sistem
                        </div>

                        <div class="card-body">

                            <p>
                                Sistem Pendukung Keputusan untuk menentukan aksesoris motor terbaik menggunakan metode TOPSIS.
                            </p>

                            <hr>

                            <table class="table table-borderless mb-0">

                                <tr>
                                    <td>Metode</td>
                                    <td><b>TOPSIS</b></td>
                                </tr>

                                <tr>
                                    <td>Bahasa</td>
                                    <td><b>PHP Native</b></td>
                                </tr>

                                <tr>
                                    <td>Database</td>
                                    <td><b>MySQL</b></td>
                                </tr>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <?php include '../layout/footer.php'; ?>