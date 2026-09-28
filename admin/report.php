    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "report";
    $title = "Laporan";

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");
    $totalKriteria = mysqli_num_rows($kriteria);

    $alternatif = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif");
    $totalAlternatif = mysqli_num_rows($alternatif);

    $penilaian = mysqli_query($conn, " SELECT
            a.id_alternatif,
            a.kode_alternatif,
            a.nama_alternatif,

            MAX(CASE WHEN p.id_kriteria=1 THEN p.nilai END) AS C1,
            MAX(CASE WHEN p.id_kriteria=2 THEN p.nilai END) AS C2,
            MAX(CASE WHEN p.id_kriteria=3 THEN p.nilai END) AS C3,
            MAX(CASE WHEN p.id_kriteria=4 THEN p.nilai END) AS C4,
            MAX(CASE WHEN p.id_kriteria=5 THEN p.nilai END) AS C5,
            MAX(CASE WHEN p.id_kriteria=6 THEN p.nilai END) AS C6,
            MAX(CASE WHEN p.id_kriteria=7 THEN p.nilai END) AS C7,
            MAX(CASE WHEN p.id_kriteria=8 THEN p.nilai END) AS C8

        FROM alternatif a

        LEFT JOIN penilaian p
        ON a.id_alternatif = p.id_alternatif

        GROUP BY
        a.id_alternatif,
        a.kode_alternatif,
        a.nama_alternatif

        ORDER BY
        a.id_alternatif

        ");

    $totalPenilaian = mysqli_num_rows($penilaian);

    $ranking = mysqli_query($conn, " SELECT
            r.ranking,
            a.kode_alternatif,
            a.nama_alternatif,
            r.nilai_preferensi
        FROM ranking r
        JOIN alternatif a
            ON r.id_alternatif = a.id_alternatif
        ORDER BY r.ranking ASC
    ");

    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-file-earmark-text"></i>
                    <?= $title ?>

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

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h3 class="fw-bold mb-1">

                        <?= $title ?>

                    </h3>

                    <small class="text-muted">

                        Laporan Sistem Pendukung Keputusan Metode TOPSIS

                    </small>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Laporan Data Kriteria -->
            <!-- ===================== -->

            <div class="card mb-4 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span class="fw-semibold">

                        Laporan Data Kriteria

                    </span>

                    <div class="d-flex gap-2">

                        <?php if ($totalKriteria > 10) { ?>

                            <button
                                class="btn btn-outline-primary btnLihat"
                                data-open="false">

                                <i class="bi bi-chevron-down"></i>

                                Lihat Selengkapnya

                            </button>

                        <?php } ?>

                        <button
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#previewKriteria">

                            <i class="bi bi-printer-fill"></i>

                            Cetak

                        </button>

                    </div>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-primary text-center">

                                <tr>

                                    <th width="60">No</th>
                                    <th width="90">Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th width="120">Jenis</th>
                                    <th width="90">Bobot</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($kriteria)) {

                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td class="text-center"><?= $no; ?></td>

                                        <td><?= $row['kode_kriteria']; ?></td>

                                        <td><?= $row['nama_kriteria']; ?></td>

                                        <td class="text-center">

                                            <?php if ($row['atribut'] == "Benefit") { ?>

                                                <span class="badge bg-success">Benefit</span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">Cost</span>

                                            <?php } ?>

                                        </td>

                                        <td class="text-center"><?= $row['bobot']; ?></td>

                                    </tr>

                                <?php

                                    $no++;
                                }

                                ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Laporan Data Alternatif -->
            <!-- ===================== -->

            <div class="card mb-4 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span class="fw-semibold">

                        Laporan Data Alternatif

                    </span>

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#previewAlternatif">

                        <i class="bi bi-printer-fill"></i>

                        Cetak

                    </button>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle mb-0">

                            <thead class="table-primary text-center">

                                <tr>

                                    <th style="width:60px;">No</th>

                                    <th style="width:90px;">Kode</th>

                                    <th>Nama Alternatif</th>

                                    <th style="width:140px;">Kategori</th>

                                    <th style="width:170px;">Merek</th>

                                    <th style="width:150px;">Harga</th>

                                    <th style="width:120px;">Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($alternatif)) {

                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td class="text-center"><?= $no; ?></td>

                                        <td class="text-center"><?= $row['kode_alternatif']; ?></td>

                                        <td><?= $row['nama_alternatif']; ?></td>

                                        <td class="text-center"><?= $row['kategori']; ?></td>

                                        <td><?= $row['merek']; ?></td>

                                        <td class="text-end">

                                            Rp<?= number_format((int)$row['harga'], 0, ',', '.'); ?>

                                        </td>

                                        <td class="text-center">

                                            <?php if ($row['status'] == "Tersedia") { ?>

                                                <span class="badge bg-success">

                                                    Tersedia

                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">

                                                    Tidak Tersedia

                                                </span>

                                            <?php } ?>

                                        </td>

                                    </tr>

                                <?php

                                    $no++;
                                }

                                ?>

                            </tbody>

                        </table>

                        <?php if ($totalAlternatif > 10) { ?>

                            <div class="text-center mt-4">

                                <button
                                    class="btn btn-outline-primary btnLihat"
                                    data-open="false">

                                    <i class="bi bi-chevron-down"></i>

                                    Lihat Selengkapnya

                                </button>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Laporan Data Penilaian -->
            <!-- ===================== -->

            <div class="card mb-4 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span class="fw-semibold">

                        Laporan Data Penilaian

                    </span>

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#previewPenilaian">

                        <i class="bi bi-printer-fill"></i>
                        Cetak

                    </button>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center mb-0">

                            <thead class="table-primary">

                                <tr>

                                    <th width="60">No</th>
                                    <th width="80">Kode</th>
                                    <th style="min-width:220px;">Nama Alternatif</th>

                                    <th width="60">C1</th>
                                    <th width="60">C2</th>
                                    <th width="60">C3</th>
                                    <th width="60">C4</th>
                                    <th width="60">C5</th>
                                    <th width="60">C6</th>
                                    <th width="60">C7</th>
                                    <th width="60">C8</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($penilaian)) {

                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $no; ?></td>
                                        <td><?= $row['kode_alternatif']; ?></td>

                                        <td class="text-start">
                                            <?= $row['nama_alternatif']; ?>
                                        </td>

                                        <td><?= $row['C1']; ?></td>
                                        <td><?= $row['C2']; ?></td>
                                        <td><?= $row['C3']; ?></td>
                                        <td><?= $row['C4']; ?></td>
                                        <td><?= $row['C5']; ?></td>
                                        <td><?= $row['C6']; ?></td>
                                        <td><?= $row['C7']; ?></td>
                                        <td><?= $row['C8']; ?></td>

                                    </tr>

                                <?php

                                    $no++;
                                }

                                ?>

                            </tbody>

                        </table>

                        <?php if ($totalPenilaian > 10) { ?>

                            <div class="text-center mt-4">

                                <button
                                    class="btn btn-outline-primary btnLihat"
                                    data-open="false">

                                    <i class="bi bi-chevron-down"></i>

                                    Lihat Selengkapnya

                                </button>

                            </div>

                        <?php } ?>

                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Laporan Hasil Ranking -->
            <!-- ===================== -->

            <div class="card mb-4 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span class="fw-semibold">

                        Laporan Hasil Ranking TOPSIS

                    </span>

                    <button
                        type="button"
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#previewRanking">

                        <i class="bi bi-printer-fill"></i>

                        Cetak

                    </button>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center mb-0">

                            <thead class="table-primary">

                                <tr>

                                    <th style="width:90px;">Ranking</th>
                                    <th style="width:90px;">Kode</th>
                                    <th>Nama Alternatif</th>
                                    <th style="width:180px;">Nilai Preferensi</th>
                                    <th style="width:190px;">Keterangan</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php while ($r = mysqli_fetch_assoc($ranking)) { ?>

                                    <tr class="<?= ($r['ranking'] == 1) ? 'table-warning' : ''; ?>">

                                        <td>

                                            <?php

                                            if ($r['ranking'] == 1) {

                                                echo '<span class="badge bg-warning text-dark">🥇 1</span>';
                                            } elseif ($r['ranking'] == 2) {

                                                echo '<span class="badge bg-secondary">🥈 2</span>';
                                            } elseif ($r['ranking'] == 3) {

                                                echo '<span class="badge bg-secondary">🥉 3</span>';
                                            } else {

                                                echo $r['ranking'];
                                            }

                                            ?>

                                        </td>

                                        <td><?= $r['kode_alternatif']; ?></td>

                                        <td class="text-start">

                                            <?= $r['nama_alternatif']; ?>

                                        </td>

                                        <td>

                                            <strong><?= number_format($r['nilai_preferensi'], 4); ?></strong>

                                        </td>

                                        <td>

                                            <?php if ($r['ranking'] == 1) { ?>

                                                <span class="badge bg-success">

                                                    Rekomendasi Terbaik

                                                </span>

                                            <?php } else { ?>

                                                -

                                            <?php } ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            <!--modal Kriteria-->
            <div class="modal fade"
                id="previewKriteria"
                tabindex="-1">

                <div class="modal-dialog modal-xl modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Preview Laporan Data Kriteria

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div id="areaCetakKriteria">

                                <div class="text-center mb-4">

                                    <h3 class="fw-bold">

                                        LAPORAN DATA KRITERIA

                                    </h3>

                                    <h5>

                                        TOKO SENTRAL ASESORIS MOTOR

                                    </h5>

                                    <small>

                                        Dicetak :
                                        <?= date('d F Y'); ?>

                                    </small>

                                </div>

                                <table class="table table-bordered table-hover align-middle text-center">

                                    <thead class="table-primary">

                                        <tr>

                                            <th width="70">No</th>

                                            <th width="100">Kode</th>

                                            <th>Nama Kriteria</th>

                                            <th width="120">Bobot</th>

                                            <th width="120">Atribut</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $data = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

                                        while ($d = mysqli_fetch_assoc($data)) {

                                        ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td><?= $d['kode_kriteria']; ?></td>

                                                <td class="text-start">

                                                    <?= $d['nama_kriteria']; ?>

                                                </td>

                                                <td><?= $d['bobot']; ?></td>

                                                <td>

                                                    <span class="badge <?= ($d['atribut'] == 'Benefit') ? 'bg-success' : 'bg-danger'; ?>">

                                                        <?= $d['atribut']; ?>

                                                    </span>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                                <?php

                                date_default_timezone_set('Asia/Jakarta');

                                $hari = [
                                    'Sunday'    => 'Minggu',
                                    'Monday'    => 'Senin',
                                    'Tuesday'   => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday'  => 'Kamis',
                                    'Friday'    => 'Jumat',
                                    'Saturday'  => 'Sabtu'
                                ];

                                $bulan = [
                                    'January'   => 'Januari',
                                    'February'  => 'Februari',
                                    'March'     => 'Maret',
                                    'April'     => 'April',
                                    'May'       => 'Mei',
                                    'June'      => 'Juni',
                                    'July'      => 'Juli',
                                    'August'    => 'Agustus',
                                    'September' => 'September',
                                    'October'   => 'Oktober',
                                    'November'  => 'November',
                                    'December'  => 'Desember'
                                ];

                                ?>

                                <div style="display:flex; justify-content:flex-end; margin-top:80px;">

                                    <div style="width:250px; text-align:center;">

                                        <p>
                                            Jakarta,
                                            <?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y'); ?>
                                        </p>

                                        <p class="text-center">

                                            Mengetahui,

                                        </p>

                                        <div style="height:90px;"></div>

                                        <p class="text-center">

                                            <strong>Kepala</strong>

                                        </p>

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
                                type="button"
                                class="btn btn-success"
                                onclick="printDiv('areaCetakKriteria')">

                                <i class="bi bi-printer-fill"></i>

                                Cetak

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Modal Alternatif -->
            <div
                class="modal fade"
                id="previewAlternatif"
                tabindex="-1">

                <div class="modal-dialog modal-xl modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Preview Laporan Data Alternatif

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div id="areaCetakAlternatif">

                                <div class="text-center mb-4">

                                    <h3 class="fw-bold">

                                        LAPORAN DATA ALTERNATIF

                                    </h3>

                                    <h5>

                                        TOKO SENTRAL ASESORIS MOTOR

                                    </h5>

                                    <small>

                                        Dicetak :
                                        <?= date('d F Y'); ?>

                                    </small>

                                </div>

                                <table class="table table-bordered table-hover align-middle text-center">

                                    <thead class="table-primary">
                                        <tr>
                                            <th width="70">No</th>
                                            <th width="100">Kode</th>
                                            <th>Nama Alternatif</th>
                                            <th width="170">Harga</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");

                                        while ($a = mysqli_fetch_assoc($alternatif)) {

                                        ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td><?= $a['kode_alternatif']; ?></td>

                                                <td class="text-start">
                                                    <?= $a['nama_alternatif']; ?>
                                                </td>

                                                <td class="text-end">
                                                    Rp <?= number_format($a['harga'], 0, ',', '.'); ?>
                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                                <?php

                                date_default_timezone_set('Asia/Jakarta');

                                $hari = [
                                    'Sunday'    => 'Minggu',
                                    'Monday'    => 'Senin',
                                    'Tuesday'   => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday'  => 'Kamis',
                                    'Friday'    => 'Jumat',
                                    'Saturday'  => 'Sabtu'
                                ];

                                $bulan = [
                                    'January'   => 'Januari',
                                    'February'  => 'Februari',
                                    'March'     => 'Maret',
                                    'April'     => 'April',
                                    'May'       => 'Mei',
                                    'June'      => 'Juni',
                                    'July'      => 'Juli',
                                    'August'    => 'Agustus',
                                    'September' => 'September',
                                    'October'   => 'Oktober',
                                    'November'  => 'November',
                                    'December'  => 'Desember'
                                ];

                                ?>

                                <div style="display:flex; justify-content:flex-end; margin-top:80px;">

                                    <div style="width:250px; text-align:center;">

                                        <p>
                                            Jakarta,
                                            <?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y'); ?>
                                        </p>

                                        <p class="text-center">

                                            Mengetahui,

                                        </p>

                                        <div style="height:90px;"></div>

                                        <p class="text-center">

                                            <strong>Kepala</strong>

                                        </p>

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
                                    type="button"
                                    class="btn btn-success"
                                    onclick="printDiv('areaCetakAlternatif')">

                                    <i class="bi bi-printer-fill"></i>

                                    Cetak

                                </button>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <!-- Modal Penilaian -->
            <div class="modal fade"
                id="previewPenilaian"
                tabindex="-1">

                <div class="modal-dialog modal-xl modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Preview Laporan Data Penilaian

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <div class="modal-body">

                            <div id="areaCetakPenilaian">

                                <div class="text-center mb-4">

                                    <h3 class="fw-bold">

                                        LAPORAN DATA PENILAIAN

                                    </h3>

                                    <h5>

                                        TOKO SENTRAL ASESORIS MOTOR

                                    </h5>

                                    <small>

                                        Dicetak :
                                        <?= date('d F Y'); ?>

                                    </small>

                                </div>

                                <table class="table table-bordered table-hover align-middle text-center">

                                    <thead class="table-primary">

                                        <tr>

                                            <th>No</th>
                                            <th>Kode</th>
                                            <th>Nama Alternatif</th>
                                            <th>C1</th>
                                            <th>C2</th>
                                            <th>C3</th>
                                            <th>C4</th>
                                            <th>C5</th>
                                            <th>C6</th>
                                            <th>C7</th>
                                            <th>C8</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $no = 1;

                                        $query = mysqli_query($conn, "

                                SELECT

                                    a.id_alternatif,
                                    a.kode_alternatif,
                                    a.nama_alternatif,

                                    MAX(CASE WHEN p.id_kriteria=1 THEN p.nilai END) AS C1,
                                    MAX(CASE WHEN p.id_kriteria=2 THEN p.nilai END) AS C2,
                                    MAX(CASE WHEN p.id_kriteria=3 THEN p.nilai END) AS C3,
                                    MAX(CASE WHEN p.id_kriteria=4 THEN p.nilai END) AS C4,
                                    MAX(CASE WHEN p.id_kriteria=5 THEN p.nilai END) AS C5,
                                    MAX(CASE WHEN p.id_kriteria=6 THEN p.nilai END) AS C6,
                                    MAX(CASE WHEN p.id_kriteria=7 THEN p.nilai END) AS C7,
                                    MAX(CASE WHEN p.id_kriteria=8 THEN p.nilai END) AS C8

                                FROM alternatif a

                                LEFT JOIN penilaian p
                                ON a.id_alternatif = p.id_alternatif

                                GROUP BY
                                    a.id_alternatif,
                                    a.kode_alternatif,
                                    a.nama_alternatif

                                ORDER BY a.id_alternatif

                            ");

                                        while ($d = mysqli_fetch_assoc($query)) {

                                        ?>

                                            <tr>

                                                <td><?= $no++; ?></td>

                                                <td><?= $d['kode_alternatif']; ?></td>

                                                <td class="text-start">

                                                    <?= $d['nama_alternatif']; ?>

                                                </td>

                                                <td><?= $d['C1']; ?></td>
                                                <td><?= $d['C2']; ?></td>
                                                <td><?= $d['C3']; ?></td>
                                                <td><?= $d['C4']; ?></td>
                                                <td><?= $d['C5']; ?></td>
                                                <td><?= $d['C6']; ?></td>
                                                <td><?= $d['C7']; ?></td>
                                                <td><?= $d['C8']; ?></td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                                <?php

                                date_default_timezone_set('Asia/Jakarta');

                                $hari = [
                                    'Sunday'    => 'Minggu',
                                    'Monday'    => 'Senin',
                                    'Tuesday'   => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday'  => 'Kamis',
                                    'Friday'    => 'Jumat',
                                    'Saturday'  => 'Sabtu'
                                ];

                                $bulan = [
                                    'January'   => 'Januari',
                                    'February'  => 'Februari',
                                    'March'     => 'Maret',
                                    'April'     => 'April',
                                    'May'       => 'Mei',
                                    'June'      => 'Juni',
                                    'July'      => 'Juli',
                                    'August'    => 'Agustus',
                                    'September' => 'September',
                                    'October'   => 'Oktober',
                                    'November'  => 'November',
                                    'December'  => 'Desember'
                                ];

                                ?>

                                <div style="display:flex; justify-content:flex-end; margin-top:80px;">

                                    <div style="width:250px; text-align:center;">

                                        <p>

                                            Jakarta,
                                            <?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y'); ?>

                                        </p>

                                        <p>

                                            Mengetahui,

                                        </p>

                                        <div style="height:90px;"></div>

                                        <p>

                                            <strong>Kepala</strong>

                                        </p>

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
                                type="button"
                                class="btn btn-success"
                                onclick="printDiv('areaCetakPenilaian')">

                                <i class="bi bi-printer-fill"></i>

                                Cetak

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Modal Ranking -->
            <div class="modal fade"
                id="previewRanking"
                tabindex="-1">

                <div class="modal-dialog modal-xl modal-dialog-scrollable">

                    <div class="modal-content">

                        <div class="modal-header">

                            <h5 class="modal-title">

                                Preview Laporan Hasil Ranking

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

                                        LAPORAN HASIL RANKING TOPSIS

                                    </h3>

                                    <h5>

                                        TOKO SENTRAL ASESORIS MOTOR

                                    </h5>

                                    <small>

                                        Dicetak :
                                        <?= date('d F Y'); ?>

                                    </small>

                                </div>

                                <table class="table table-bordered table-hover align-middle text-center">

                                    <thead class="table-primary">

                                        <tr>

                                            <th width="90">Ranking</th>
                                            <th width="90">Kode</th>
                                            <th>Nama Alternatif</th>
                                            <th width="180">Nilai Preferensi</th>
                                            <th width="180">Keterangan</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                        $ranking = mysqli_query($conn, "
                                SELECT
                                    r.ranking,
                                    a.kode_alternatif,
                                    a.nama_alternatif,
                                    r.nilai_preferensi
                                FROM ranking r
                                JOIN alternatif a
                                    ON r.id_alternatif = a.id_alternatif
                                ORDER BY r.ranking ASC
                            ");

                                        while ($r = mysqli_fetch_assoc($ranking)) {

                                        ?>

                                            <tr class="<?= ($r['ranking'] == 1) ? 'table-warning' : ''; ?>">

                                                <td>

                                                    <?php

                                                    if ($r['ranking'] == 1) {

                                                        echo '<span class="badge bg-warning text-dark">🥇 1</span>';
                                                    } elseif ($r['ranking'] == 2) {

                                                        echo '<span class="badge bg-secondary">🥈 2</span>';
                                                    } elseif ($r['ranking'] == 3) {

                                                        echo '<span class="badge bg-secondary">🥉 3</span>';
                                                    } else {

                                                        echo $r['ranking'];
                                                    }

                                                    ?>

                                                </td>

                                                <td><?= $r['kode_alternatif']; ?></td>

                                                <td class="text-start">

                                                    <?= $r['nama_alternatif']; ?>

                                                </td>

                                                <td>

                                                    <strong><?= number_format($r['nilai_preferensi'], 4); ?></strong>

                                                </td>

                                                <td>

                                                    <?php if ($r['ranking'] == 1) { ?>

                                                        <span class="badge bg-success">

                                                            Rekomendasi Terbaik

                                                        </span>

                                                    <?php } else { ?>

                                                        -

                                                    <?php } ?>

                                                </td>

                                            </tr>

                                        <?php } ?>

                                    </tbody>

                                </table>

                                <?php

                                date_default_timezone_set('Asia/Jakarta');

                                $hari = [
                                    'Sunday'    => 'Minggu',
                                    'Monday'    => 'Senin',
                                    'Tuesday'   => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday'  => 'Kamis',
                                    'Friday'    => 'Jumat',
                                    'Saturday'  => 'Sabtu'
                                ];

                                $bulan = [
                                    'January'   => 'Januari',
                                    'February'  => 'Februari',
                                    'March'     => 'Maret',
                                    'April'     => 'April',
                                    'May'       => 'Mei',
                                    'June'      => 'Juni',
                                    'July'      => 'Juli',
                                    'August'    => 'Agustus',
                                    'September' => 'September',
                                    'October'   => 'Oktober',
                                    'November'  => 'November',
                                    'December'  => 'Desember'
                                ];

                                ?>

                                <div style="display:flex; justify-content:flex-end; margin-top:80px;">

                                    <div style="width:250px; text-align:center;">

                                        <p>

                                            Jakarta,
                                            <?= $hari[date('l')] . ', ' . date('d') . ' ' . $bulan[date('F')] . ' ' . date('Y'); ?>

                                        </p>

                                        <p>

                                            Mengetahui,

                                        </p>

                                        <div style="height:90px;"></div>

                                        <p>

                                            <strong>Kepala</strong>

                                        </p>

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
                                type="button"
                                class="btn btn-success"
                                onclick="printDiv('areaCetakRanking')">

                                <i class="bi bi-printer-fill"></i>

                                Cetak

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <?php include '../layout/footer.php'; ?>