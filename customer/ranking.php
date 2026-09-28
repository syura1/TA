<?php
$title = "Ranking Aksesoris";

include "../koneksi/koneksi.php";

$kategoriList = mysqli_query($conn, "
SELECT DISTINCT kategori
FROM alternatif
ORDER BY kategori ASC
");

$where = "";

if (isset($_GET['kategori']) && $_GET['kategori'] != "") {

    $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);

    $where = "WHERE a.kategori = '$kategori'";
}

$ranking = mysqli_query($conn, "
SELECT
    r.ranking,
    r.nilai_preferensi,
    a.nama_alternatif,
    a.kategori,
    a.harga
FROM ranking r
JOIN alternatif a
ON r.id_alternatif = a.id_alternatif
$where
ORDER BY r.ranking ASC
");

$data = [];

while ($row = mysqli_fetch_assoc($ranking)) {
    $data[] = $row;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body class="bg-light">

    <!-- Navbar -->

    <nav class="navbar navbar-expand-lg bg-white shadow-sm">

        <div class="container">

            <a class="navbar-brand fw-bold text-primary" href="../index.php">

                <i class="bi bi-speedometer2"></i>

                SPK TOPSIS

            </a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">

                        <a class="nav-link" href="../index.php">Beranda</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link active fw-semibold text-primary" href="ranking.php">Ranking</a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link" href="about.php">Tentang</a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <!-- Header -->

    <section class="bg-primary text-white py-5">

        <div class="container text-center">

            <h1 class="fw-bold">

                Hasil Ranking Aksesoris Motor

            </h1>

            <p>

                Rekomendasi aksesoris motor terbaik berdasarkan metode TOPSIS.

            </p>

        </div>

    </section>

    <!-- TOP 3 -->

    <div class="container my-5">

        <div class="row g-4">

            <div class="col-lg-4">

                <div class="card shadow border-0 text-center h-100">

                    <div class="card-body">

                        <h1>🥇</h1>

                        <h4 class="fw-bold">
                            <?= isset($data[0]) ? $data[0]['nama_alternatif'] : '-'; ?>
                        </h4>

                        <h2 class="text-primary">
                            <?= isset($data[0]) ? number_format($data[0]['nilai_preferensi'], 4) :   '-'; ?>
                        </h2>
                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow border-0 text-center h-100">

                    <div class="card-body">

                        <h1>🥈</h1>

                        <h4 class="fw-bold">
                            <?= isset($data[1]) ? $data[1]['nama_alternatif'] : '-'; ?>
                        </h4>

                        <h2 class="text-primary">
                            <?= isset($data[1]) ? number_format($data[1]['nilai_preferensi'], 4) : '-'; ?>
                        </h2>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow border-0 text-center h-100">

                    <div class="card-body">

                        <h1>🥉</h1>

                        <h4 class="fw-bold">
                            <?= isset($data[2]) ? $data[2]['nama_alternatif'] : '-'; ?>
                        </h4>

                        <h2 class="text-primary">
                            <?= isset($data[2]) ? number_format($data[2]['nilai_preferensi'], 4) : '-'; ?>
                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Ranking -->

    <div class="container mb-5">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white text-center">

                <h4 class="mb-0">

                    Ranking Lengkap

                </h4>

            </div>

            <div class="card-body">

                <!-- Form Cari + Filter -->
                <form method="GET" class="mb-4">

                    <div class="row">

                        <!-- Cari -->
                        <div class="col-md-8">

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="cari"
                                    placeholder="Cari aksesoris..."
                                    value="<?= isset($_GET['cari']) ? $_GET['cari'] : ''; ?>">

                            </div>

                        </div>

                        <!-- Filter -->
                        <div class="col-md-4">

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-funnel"></i>
                                </span>

                                <select
                                    class="form-select"
                                    name="kategori"
                                    onchange="this.form.submit()">

                                    <option value="">Semua Kategori</option>

                                    <?php while ($k = mysqli_fetch_assoc($kategoriList)) { ?>

                                        <option
                                            value="<?= $k['kategori']; ?>"
                                            <?= (isset($_GET['kategori']) && $_GET['kategori'] == $k['kategori']) ? 'selected' : ''; ?>>

                                            <?= $k['kategori']; ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>

                        </div>

                    </div>

                </form>

                <!-- Tabel -->
                <div class="table-responsive">

                    <table class="table table-striped table-hover align-middle text-center">

                        <thead class="table-primary">

                            <tr>

                                <th style="width:100px;">Ranking</th>
                                <th>Nama Aksesoris</th>
                                <th style="width:180px;">Harga</th>
                                <th style="width:170px;">Nilai</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach ($data as $index => $row) { ?>

                                <tr>

                                    <td>

                                        <?php

                                        $no = $index + 1;

                                        if ($no == 1) {

                                            echo "🥇";
                                        } elseif ($no == 2) {

                                            echo "🥈";
                                        } elseif ($no == 3) {

                                            echo "🥉";
                                        } else {

                                            echo $no;
                                        }

                                        ?>

                                    </td>

                                    <td class="fw-medium">
                                        <?= $row['nama_alternatif']; ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                    </td>

                                    <td class="fw-bold">
                                        <?= number_format($row['nilai_preferensi'], 4); ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="text-center mt-4">

            <button
                type="button"
                class="btn btn-success px-4"
                data-bs-toggle="modal"
                data-bs-target="#previewRanking">

                <i class="bi bi-printer-fill"></i>

                Cetak

            </button>

        </div>

    </div>

    <footer class="bg-dark text-white text-center py-4">

        <h3>

            Toko Sentral Asesoris Motor

        </h3>

        <p class="mb-0">

            Sistem Pendukung Keputusan menggunakan metode TOPSIS

        </p>

    </footer>

    <!-- Modal Preview Ranking -->
    <div class="modal fade"
        id="previewRanking"
        tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Preview Laporan Ranking
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div id="areaCetakRanking">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold">
                                LAPORAN HASIL RANKING
                            </h3>

                            <h5>
                                TOKO SENTRAL ASESORIS MOTOR
                            </h5>

                            <small>
                                Dicetak :
                                <?= date('d F Y'); ?>
                            </small>

                        </div>

                        <table class="table table-bordered text-center align-middle">

                            <thead class="table-primary">

                                <tr>

                                    <th width="80">Ranking</th>

                                    <th>Nama Aksesoris</th>

                                    <th width="180">Harga</th>

                                    <th width="180">Nilai Preferensi</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($data as $index => $row) { ?>

                                    <tr>

                                        <td><?= $index + 1; ?></td>

                                        <td>
                                            <?= $row['nama_alternatif']; ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($row['harga'], 0, ',', '.'); ?>
                                        </td>

                                        <td>
                                            <?= number_format($row['nilai_preferensi'], 4); ?>
                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                        <?php

                        date_default_timezone_set('Asia/Jakarta');

                        $hari = [
                            'Sunday' => 'Minggu',
                            'Monday' => 'Senin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Kamis',
                            'Friday' => 'Jumat',
                            'Saturday' => 'Sabtu'
                        ];

                        $bulan = [
                            'January' => 'Januari',
                            'February' => 'Februari',
                            'March' => 'Maret',
                            'April' => 'April',
                            'May' => 'Mei',
                            'June' => 'Juni',
                            'July' => 'Juli',
                            'August' => 'Agustus',
                            'September' => 'September',
                            'October' => 'Oktober',
                            'November' => 'November',
                            'December' => 'Desember'
                        ];

                        ?>

                        <div style="display:flex;justify-content:flex-end;margin-top:80px;">

                            <div style="width:250px;text-align:center;">

                                <p>

                                    Jakarta,
                                    <?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y'); ?>

                                </p>

                                <p>Mengetahui,</p>

                                <div style="height:90px;"></div>

                                <strong>Kepala</strong>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                    <button
                        class="btn btn-success"
                        onclick="printDiv('areaCetakRanking')">

                        <i class="bi bi-printer-fill"></i>

                        Cetak

                    </button>

                </div>

            </div>

        </div>

    </div>

    <?php include "../layout/footer.php"; ?>

</body>

</html>