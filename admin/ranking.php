    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "ranking";
    $title = "Hasil Ranking";

    $pembagi = [];

    $kriteria = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

    while ($k = mysqli_fetch_assoc($kriteria)) {

        $jumlah = 0;

        $nilai = mysqli_query($conn, " SELECT nilai FROM penilaian WHERE id_kriteria='$k[id_kriteria]' ");

        while ($n = mysqli_fetch_assoc($nilai)) {

            $jumlah += pow($n['nilai'], 2);
        }

        $pembagi[$k['id_kriteria']] = sqrt($jumlah);
    }

    //solusi ideal 

    $aPlus = [];
    $aMin = [];

    $qKriteria = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

    while ($k = mysqli_fetch_assoc($qKriteria)) {

        $id = $k['id_kriteria'];

        $q = mysqli_query($conn, " SELECT
            MAX(nilai) max_n,
            MIN(nilai) min_n
            FROM penilaian
            WHERE id_kriteria='$id'
            ");

        $d = mysqli_fetch_assoc($q);

        $yMax = ($d['max_n'] / $pembagi[$id]) * $k['bobot'];
        $yMin = ($d['min_n'] / $pembagi[$id]) * $k['bobot'];

        if (strtolower($k['atribut']) == "benefit") {

            $aPlus[$id] = $yMax;
            $aMin[$id] = $yMin;
        } else {

            $aPlus[$id] = $yMin;
            $aMin[$id] = $yMax;
        }
    }

    //ranking
    $ranking = [];

    $qAlternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");

    while ($a = mysqli_fetch_assoc($qAlternatif)) {

        $dPlus = 0;
        $dMin = 0;

        $nilai = mysqli_query($conn, " SELECT p.nilai, p.id_kriteria, k.bobot
            FROM penilaian p
            JOIN kriteria k
            ON p.id_kriteria=k.id_kriteria
            WHERE p.id_alternatif='$a[id_alternatif]'
            ORDER BY p.id_kriteria
            ");

        while ($n = mysqli_fetch_assoc($nilai)) {

            $r = $n['nilai'] / $pembagi[$n['id_kriteria']];
            $y = $r * $n['bobot'];

            $dPlus += pow($y - $aPlus[$n['id_kriteria']], 2);
            $dMin += pow($y - $aMin[$n['id_kriteria']], 2);
        }

        $dPlus = sqrt($dPlus);
        $dMin  = sqrt($dMin);

        $v = ($dPlus + $dMin) != 0
            ? $dMin / ($dPlus + $dMin)
            : 0;

        $ranking[] = [
            'kode' => $a['kode_alternatif'],
            'nama' => $a['nama_alternatif'],
            'nilai' => $v
        ];
    }

    usort($ranking, function ($a, $b) {

        return $b['nilai'] <=> $a['nilai'];
    });

    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-bar-chart-fill"></i>
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

                        Menampilkan hasil akhir perankingan metode TOPSIS

                    </small>

                </div>

                <button
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#previewRanking">

                    <i class="bi bi-printer-fill"></i>

                    Cetak Ranking

                </button>

            </div>
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body text-center">

                    <i class="bi bi-trophy-fill text-warning"
                        style="font-size:70px;"></i>

                    <h4 class="mt-3 fw-bold">

                        Ranking Terbaik

                    </h4>

                    <h2 class="text-primary">

                        <?= $ranking[0]['nama']; ?>

                    </h2>

                    <p class="text-muted mb-0">

                        Nilai Preferensi : <strong><?= number_format($ranking[0]['nilai'], 4); ?></strong>

                    </p>

                </div>

            </div>
            <div class="card">

                <div class="card-header fw-semibold">

                    Data Ranking

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover table-bordered align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th width="90">Ranking</th>

                                    <th>Kode</th>

                                    <th>Nama Alternatif</th>

                                    <th>Nilai Preferensi</th>

                                    <th>Status</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                foreach ($ranking as $r) {

                                ?>

                                    <tr class="<?= ($no == 1) ? 'table-warning' : ''; ?>">

                                        <td>

                                            <?php

                                            if ($no == 1) {

                                                echo '<span class="badge bg-warning text-dark">🥇 1</span>';
                                            } elseif ($no == 2) {

                                                echo '<span class="badge bg-secondary">🥈 2</span>';
                                            } elseif ($no == 3) {

                                                echo '<span class="badge bg-secondary">🥉 3</span>';
                                            } else {

                                                echo $no;
                                            }

                                            ?>

                                        </td>

                                        <td><?= $r['kode']; ?></td>

                                        <td><?= $r['nama']; ?></td>

                                        <td><strong><?= number_format($r['nilai'], 4); ?></strong></td>

                                        <td>

                                            <?= ($no == 1)
                                                ? '<span class="badge bg-success">Terbaik</span>'
                                                : '-'; ?>

                                        </td>

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
        </div>

    </div>

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

                    <div id="areaCetak">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold">

                                HASIL RANKING METODE TOPSIS

                            </h3>

                            <h5>

                                TOKO SENTRAL ASESORIS MOTOR

                            </h5>

                            <small>

                                Dicetak :
                                <?= date('d F Y'); ?>

                            </small>

                        </div>

                        <table class="table table-bordered text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>No</th>

                                    <th>Kode</th>

                                    <th>Nama Alternatif</th>

                                    <th>Nilai Preferensi</th>

                                    <th>Ranking</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                foreach ($ranking as $r) {

                                ?>

                                    <tr>

                                        <td><?= $no ?></td>

                                        <td><?= $r['kode'] ?></td>

                                        <td><?= $r['nama'] ?></td>

                                        <td><?= number_format($r['nilai'], 4) ?></td>

                                        <td><?= $no ?></td>

                                    </tr>

                                <?php

                                    $no++;
                                }

                                ?>

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
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Tutup

                        </button>

                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="printDiv('areaCetak')">

                            <i class="bi bi-printer-fill"></i>

                            Cetak

                        </button>

                    </div>

                </div>

            </div>

        </div>

        <?php include '../layout/footer.php'; ?>