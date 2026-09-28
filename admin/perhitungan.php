    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "perhitungan";
    $title = "Perhitungan TOPSIS";

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");
    $alternatif = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id_alternatif");


    $pembagi = [];
    $hasilRanking = [];

    $kriteria = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

    while ($k = mysqli_fetch_assoc($kriteria)) {

        $jumlah = 0;

        $nilai = mysqli_query($conn, " SELECT nilai FROM penilaian WHERE id_kriteria='$k[id_kriteria]' ");

        while ($n = mysqli_fetch_assoc($nilai)) {

            $jumlah += pow($n['nilai'], 2);
        }

        $pembagi[$k['id_kriteria']] = sqrt($jumlah);
    }

    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-calculator"></i>
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

                        Menampilkan seluruh tahapan perhitungan metode TOPSIS

                    </small>

                </div>

                <button
                    type="button"
                    class="btn btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#previewPerhitungan">

                    <i class="bi bi-printer-fill"></i>
                    Cetak

                </button>

            </div>

            <!-- ===================== -->
            <!-- Matriks Keputusan -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Matriks Keputusan (X)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>Alternatif</th>

                                    <?php

                                    $header = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

                                    while ($h = mysqli_fetch_assoc($header)) {
                                    ?>

                                        <th><?= $h['kode_kriteria']; ?></th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $no = 1;

                                while ($a = mysqli_fetch_assoc($alternatif)) {
                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $a['kode_alternatif']; ?></td>

                                        <?php
                                        $nilai = mysqli_query($conn, " SELECT nilai FROM penilaian WHERE id_alternatif='$a[id_alternatif]' ORDER BY id_kriteria ");

                                        while ($n = mysqli_fetch_assoc($nilai)) {
                                        ?>

                                            <td><?= $n['nilai']; ?></td>

                                        <?php } ?>

                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>

                            </tbody>

                        </table>
                        <div class="text-center mt-3">

                            <button
                                class="btn btn-outline-primary btnLihat"
                                data-open="false">

                                <i class="bi bi-chevron-down"></i>
                                Lihat Selengkapnya

                            </button>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Matriks Normalisasi -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Matriks Normalisasi (R)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>Alternatif</th>

                                    <?php

                                    $header = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");

                                    while ($h = mysqli_fetch_assoc($header)) {
                                    ?>

                                        <th><?= $h['kode_kriteria']; ?></th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");

                                $no = 1;

                                while ($a = mysqli_fetch_assoc($alternatif)) {

                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $a['kode_alternatif']; ?></td>

                                        <?php

                                        $nilai = mysqli_query($conn, " SELECT * FROM penilaian WHERE id_alternatif='$a[id_alternatif]' ORDER BY id_kriteria ");

                                        while ($n = mysqli_fetch_assoc($nilai)) {

                                            $r = $n['nilai'] / $pembagi[$n['id_kriteria']];

                                        ?>

                                            <td><?= number_format($r, 4); ?></td>

                                        <?php } ?>

                                    </tr>

                                <?php

                                    $no++;
                                }

                                ?>

                            </tbody>

                        </table>

                        <div class="text-center mt-3">

                            <button
                                class="btn btn-outline-primary btnLihat">

                                <i class="bi bi-chevron-down"></i>
                                Lihat Selengkapnya

                            </button>

                        </div>

                    </div>

                </div>

            </div>
            <!-- ===================== -->
            <!-- Matriks Normalisasi Terbobot -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Matriks Normalisasi Terbobot (Y)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>Alternatif</th>

                                    <?php
                                    $header = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                    while ($h = mysqli_fetch_assoc($header)) {
                                    ?>

                                        <th><?= $h['kode_kriteria']; ?></th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");
                                $no = 1;
                                while ($a = mysqli_fetch_assoc($alternatif)) {
                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $a['kode_alternatif']; ?></td>

                                        <?php
                                        // Gunakan JOIN untuk mengambil 'nilai' dari penilaian dan 'bobot' dari kriteria sekaligus
                                        $nilai = mysqli_query($conn, " SELECT p.nilai, p.id_kriteria, k.bobot FROM penilaian p 
                                            JOIN kriteria k ON p.id_kriteria = k.id_kriteria 
                                            WHERE p.id_alternatif='$a[id_alternatif]' 
                                            ORDER BY p.id_kriteria 
                                        ");

                                        while ($n = mysqli_fetch_assoc($nilai)) {
                                            // Normalisasi (R)
                                            $r = $n['nilai'] / $pembagi[$n['id_kriteria']];
                                            // Terbobot (Y) = R * Bobot
                                            $y = $r * $n['bobot'];
                                        ?>

                                            <td><?= number_format($y, 4); ?></td>

                                        <?php } ?>

                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>

                            </tbody>

                        </table>

                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary btnLihat">
                                <i class="bi bi-chevron-down"></i>
                                Lihat Selengkapnya
                            </button>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Solusi Ideal Positif -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Solusi Ideal Positif (A<sup>+</sup>)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <?php
                                    $header = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                    while ($h = mysqli_fetch_assoc($header)) {
                                    ?>

                                        <th><?= $h['kode_kriteria']; ?></th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <?php
                                    $kriteria_ideal = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                    while ($k = mysqli_fetch_assoc($kriteria_ideal)) {
                                        $id_k = $k['id_kriteria'];
                                        $atribut = $k['atribut'];
                                        $bobot = $k['bobot'];

                                        // Cari nilai Max dan Min dari tabel penilaian berdasarkan kriteria
                                        $q_maxmin = mysqli_query($conn, "SELECT MAX(nilai) as max_n, MIN(nilai) as min_n FROM penilaian WHERE id_kriteria='$id_k'");
                                        $d_maxmin = mysqli_fetch_assoc($q_maxmin);

                                        // Hitung nilai Y maksimum dan minimum terbobot
                                        $y_max = ($d_maxmin['max_n'] / $pembagi[$id_k]) * $bobot;
                                        $y_min = ($d_maxmin['min_n'] / $pembagi[$id_k]) * $bobot;

                                        // Tentukan A+ (Benefit = Max, Cost = Min)
                                        $a_plus = ($atribut == 'Benefit') ? $y_max : $y_min;
                                    ?>

                                        <td><?= number_format($a_plus, 4); ?></td>

                                    <?php } ?>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            <!-- ===================== -->
            <!-- Solusi Ideal Negatif -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Solusi Ideal Negatif (A<sup>-</sup>)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <?php
                                    $header = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                    while ($h = mysqli_fetch_assoc($header)) {
                                    ?>

                                        <th><?= $h['kode_kriteria']; ?></th>

                                    <?php } ?>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>

                                    <?php
                                    $kriteria_ideal = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                    while ($k = mysqli_fetch_assoc($kriteria_ideal)) {
                                        $id_k = $k['id_kriteria'];
                                        $atribut = $k['atribut'];
                                        $bobot = $k['bobot'];

                                        // Cari nilai Max dan Min dari tabel penilaian berdasarkan kriteria
                                        $q_maxmin = mysqli_query($conn, "SELECT MAX(nilai) as max_n, MIN(nilai) as min_n FROM penilaian WHERE id_kriteria='$id_k'");
                                        $d_maxmin = mysqli_fetch_assoc($q_maxmin);

                                        // Hitung nilai Y maksimum dan minimum terbobot
                                        $y_max = ($d_maxmin['max_n'] / $pembagi[$id_k]) * $bobot;
                                        $y_min = ($d_maxmin['min_n'] / $pembagi[$id_k]) * $bobot;

                                        // Tentukan A- (Benefit = Min, Cost = Max)
                                        $a_min = ($atribut == 'Benefit') ? $y_min : $y_max;
                                    ?>

                                        <td><?= number_format($a_min, 4); ?></td>

                                    <?php } ?>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
            <!-- ===================== -->
            <!-- Jarak Solusi Ideal -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Jarak ke Solusi Ideal

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>Alternatif</th>

                                    <th>D<sup>+</sup></th>

                                    <th>D<sup>-</sup></th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                // 1. Hitung dulu Solusi Ideal Positif (A+) & Negatif (A-) untuk semua kriteria
                                $ideal_pos = [];
                                $ideal_neg = [];

                                $kriteria_ideal = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                while ($k = mysqli_fetch_assoc($kriteria_ideal)) {
                                    $id_k = $k['id_kriteria'];
                                    $atribut = $k['atribut'];
                                    $bobot = $k['bobot'];

                                    $q_maxmin = mysqli_query($conn, "SELECT MAX(nilai) as max_n, MIN(nilai) as min_n FROM penilaian WHERE id_kriteria='$id_k'");
                                    $d_maxmin = mysqli_fetch_assoc($q_maxmin);

                                    $y_max = ($d_maxmin['max_n'] / $pembagi[$id_k]) * $bobot;
                                    $y_min = ($d_maxmin['min_n'] / $pembagi[$id_k]) * $bobot;

                                    $ideal_pos[$id_k] = ($atribut == 'Benefit') ? $y_max : $y_min;
                                    $ideal_neg[$id_k] = ($atribut == 'Benefit') ? $y_min : $y_max;
                                }

                                // 2. Looping Alternatif untuk menghitung Jarak D+ dan D-
                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");
                                $no = 1;
                                while ($a = mysqli_fetch_assoc($alternatif)) {
                                    $id_a = $a['id_alternatif'];

                                    $sum_plus = 0;
                                    $sum_min = 0;

                                    // Ambil nilai penilaian + bobot kriteria per alternatif
                                    $nilai = mysqli_query($conn, " 
                                        SELECT p.nilai, p.id_kriteria, k.bobot 
                                        FROM penilaian p 
                                        JOIN kriteria k ON p.id_kriteria = k.id_kriteria 
                                        WHERE p.id_alternatif='$id_a' 
                                        ORDER BY p.id_kriteria 
                                    ");

                                    while ($n = mysqli_fetch_assoc($nilai)) {
                                        $id_k = $n['id_kriteria'];
                                        // Hitung Nilai Terbobot (Y)
                                        $r = $n['nilai'] / $pembagi[$id_k];
                                        $y = $r * $n['bobot'];

                                        // Akumulasi kuadrat selisih Euclidean distance
                                        $sum_plus += pow($y - $ideal_pos[$id_k], 2);
                                        $sum_min += pow($y - $ideal_neg[$id_k], 2);
                                    }

                                    $d_plus = sqrt($sum_plus);
                                    $d_min = sqrt($sum_min);
                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $a['kode_alternatif']; ?></td>

                                        <td><?= number_format($d_plus, 4); ?></td>

                                        <td><?= number_format($d_min, 4); ?></td>

                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>

                            </tbody>

                        </table>

                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary btnLihat">
                                <i class="bi bi-chevron-down"></i>
                                Lihat Selengkapnya
                            </button>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ===================== -->
            <!-- Nilai Preferensi -->
            <!-- ===================== -->

            <div class="card mb-4">

                <div class="card-header fw-semibold">

                    Nilai Preferensi (V)

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center">

                            <thead class="table-primary">

                                <tr>

                                    <th>Alternatif</th>

                                    <th>D<sup>+</sup></th>

                                    <th>D<sup>-</sup></th>

                                    <th>Nilai Preferensi (V)</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php
                                // 1. Hitung ulang Solusi Ideal Positif (A+) & Negatif (A-)
                                $ideal_pos = [];
                                $ideal_neg = [];

                                $kriteria_ideal = mysqli_query($conn, " SELECT * FROM kriteria ORDER BY id_kriteria ");
                                while ($k = mysqli_fetch_assoc($kriteria_ideal)) {
                                    $id_k = $k['id_kriteria'];
                                    $atribut = $k['atribut'];
                                    $bobot = $k['bobot'];

                                    $q_maxmin = mysqli_query($conn, "SELECT MAX(nilai) as max_n, MIN(nilai) as min_n FROM penilaian WHERE id_kriteria='$id_k'");
                                    $d_maxmin = mysqli_fetch_assoc($q_maxmin);

                                    $y_max = ($d_maxmin['max_n'] / $pembagi[$id_k]) * $bobot;
                                    $y_min = ($d_maxmin['min_n'] / $pembagi[$id_k]) * $bobot;

                                    $ideal_pos[$id_k] = ($atribut == 'Benefit') ? $y_max : $y_min;
                                    $ideal_neg[$id_k] = ($atribut == 'Benefit') ? $y_min : $y_max;
                                }

                                // 2. Looping Alternatif untuk menghitung D+, D-, dan Nilai Preferensi (V)
                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");
                                $no = 1;
                                while ($a = mysqli_fetch_assoc($alternatif)) {
                                    $id_a = $a['id_alternatif'];

                                    $sum_plus = 0;
                                    $sum_min = 0;

                                    $nilai = mysqli_query($conn, " 
                                        SELECT p.nilai, p.id_kriteria, k.bobot 
                                        FROM penilaian p 
                                        JOIN kriteria k ON p.id_kriteria = k.id_kriteria 
                                        WHERE p.id_alternatif='$id_a' 
                                        ORDER BY p.id_kriteria 
                                    ");

                                    while ($n = mysqli_fetch_assoc($nilai)) {
                                        $id_k = $n['id_kriteria'];
                                        $r = $n['nilai'] / $pembagi[$id_k];
                                        $y = $r * $n['bobot'];

                                        $sum_plus += pow($y - $ideal_pos[$id_k], 2);
                                        $sum_min += pow($y - $ideal_neg[$id_k], 2);
                                    }

                                    $d_plus = sqrt($sum_plus);
                                    $d_min = sqrt($sum_min);

                                    // Hitung Nilai Preferensi (V)
                                    $penyebut = $d_min + $d_plus;
                                    $preferensi = ($penyebut != 0) ? $d_min / $penyebut : 0;
                                    $hasilRanking[] = [

                                        'id_alternatif' => $id_a,
                                        'preferensi'    => $preferensi

                                    ];
                                ?>

                                    <tr class="<?= ($no > 10) ? 'baris-lain d-none' : ''; ?>">

                                        <td><?= $a['kode_alternatif']; ?></td>

                                        <td><?= number_format($d_plus, 4); ?></td>

                                        <td><?= number_format($d_min, 4); ?></td>

                                        <td><strong><?= number_format($preferensi, 4); ?></strong></td>

                                    </tr>

                                <?php
                                    $no++;
                                }
                                ?>

                            </tbody>

                        </table>

                        <div class="text-center mt-3">
                            <button class="btn btn-outline-primary btnLihat">
                                <i class="bi bi-chevron-down"></i>
                                Lihat Selengkapnya
                            </button>
                        </div>

                    </div>

                </div>

            </div>
            <?php

            if (!empty($hasilRanking)) {

                usort($hasilRanking, function ($a, $b) {

                    return $b['preferensi'] <=> $a['preferensi'];
                });

                mysqli_query($conn, "DELETE FROM ranking");

                $urutan = 1;

                foreach ($hasilRanking as $r) {

                    $nilai = number_format($r['preferensi'], 6, '.', '');

                    mysqli_query($conn, "
            INSERT INTO ranking
            (
                id_alternatif,
                nilai_preferensi,
                ranking,
                tanggal
            )
            VALUES
            (
                '$r[id_alternatif]',
                '$nilai',
                '$urutan',
                NOW()
            )
        ");

                    $urutan++;
                }
            }

            ?>
            <div class="d-flex justify-content-end mb-4">

                <a href="ranking.php" class="btn btn-primary">

                    <i class="bi bi-trophy-fill"></i>

                    Lihat Hasil Ranking

                </a>

            </div>
        </div>

    </div>

    <!-- Modal Preview Perhitungan -->
    <div class="modal fade" id="previewPerhitungan" tabindex="-1">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Preview Laporan Perhitungan
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <iframe
                        src="../cetak/cetak_perhitungan.php"
                        width="100%"
                        height="650"
                        style="border:none;">
                    </iframe>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Tutup

                    </button>

                    <button
                        class="btn btn-success"
                        onclick="document.querySelector('#previewPerhitungan iframe').contentWindow.print();">

                        <i class="bi bi-printer-fill"></i>
                        Cetak

                    </button>

                </div>

            </div>

        </div>

    </div>

    <?php include '../layout/footer.php'; ?>