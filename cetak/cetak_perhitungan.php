    <?php
    include '../koneksi/koneksi.php';

    $pembagi = [];

    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria");

    while ($k = mysqli_fetch_assoc($kriteria)) {

        $jumlah = 0;

        $nilai = mysqli_query(
            $conn,
            "SELECT nilai
            FROM penilaian
            WHERE id_kriteria='$k[id_kriteria]'"
        );

        while ($n = mysqli_fetch_assoc($nilai)) {

            $jumlah += pow($n['nilai'], 2);
        }

        $pembagi[$k['id_kriteria']] = sqrt($jumlah);
    }

    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>

        <meta charset="UTF-8">

        <title>Laporan Perhitungan TOPSIS</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet">

        <style>
            body {

                padding: 20px;

                font-size: 14px;

            }

            h3,
            h5 {

                margin: 0;

            }

            table {

                font-size: 13px;

            }

            .ttd {

                width: 250px;

                margin-left: auto;

                margin-top: 80px;

                text-align: center;

            }
        </style>

    </head>

    <body>

        <div id="areaCetakPerhitungan">

            <div class="text-center mb-4">

                <h3 class="fw-bold">

                    LAPORAN PERHITUNGAN METODE TOPSIS

                </h3>

                <h5>

                    TOKO SENTRAL ASESORIS MOTOR

                </h5>

                <small>

                    Dicetak :
                    <?= date('d F Y'); ?>

                </small>

            </div>
            <h5 class="fw-bold mb-3">

                1. Matriks Keputusan (X)

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <th>Alternatif</th>

                        <?php

                        $header = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($h = mysqli_fetch_assoc($header)) {

                        ?>

                            <th><?= $h['kode_kriteria']; ?></th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $alternatif = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alternatif)) {

                    ?>

                        <tr>

                            <td><?= $a['kode_alternatif']; ?></td>

                            <?php

                            $nilai = mysqli_query(
                                $conn,
                                "SELECT nilai
                        FROM penilaian
                        WHERE id_alternatif='$a[id_alternatif]'
                        ORDER BY id_kriteria"
                            );

                            while ($n = mysqli_fetch_assoc($nilai)) {

                            ?>

                                <td><?= $n['nilai']; ?></td>

                            <?php } ?>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                2. Matriks Normalisasi (R)

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <th>Alternatif</th>

                        <?php

                        $header = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($h = mysqli_fetch_assoc($header)) {

                        ?>

                            <th><?= $h['kode_kriteria']; ?></th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $alternatif = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alternatif)) {

                    ?>

                        <tr>

                            <td><?= $a['kode_alternatif']; ?></td>

                            <?php

                            $nilai = mysqli_query(
                                $conn,
                                "SELECT *
                        FROM penilaian
                        WHERE id_alternatif='$a[id_alternatif]'
                        ORDER BY id_kriteria"
                            );

                            while ($n = mysqli_fetch_assoc($nilai)) {

                                $r = $n['nilai'] / $pembagi[$n['id_kriteria']];

                            ?>

                                <td><?= number_format($r, 4); ?></td>

                            <?php } ?>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                3. Matriks Normalisasi Terbobot (Y)

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <th>Alternatif</th>

                        <?php

                        $header = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($h = mysqli_fetch_assoc($header)) {

                        ?>

                            <th><?= $h['kode_kriteria']; ?></th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $alternatif = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alternatif)) {

                    ?>

                        <tr>

                            <td><?= $a['kode_alternatif']; ?></td>

                            <?php

                            $nilai = mysqli_query(
                                $conn,
                                "SELECT
                            p.nilai,
                            p.id_kriteria,
                            k.bobot
                        FROM penilaian p
                        JOIN kriteria k
                        ON p.id_kriteria = k.id_kriteria
                        WHERE p.id_alternatif='$a[id_alternatif]'
                        ORDER BY p.id_kriteria"
                            );

                            while ($n = mysqli_fetch_assoc($nilai)) {

                                $r = $n['nilai'] / $pembagi[$n['id_kriteria']];
                                $y = $r * $n['bobot'];

                            ?>

                                <td><?= number_format($y, 4); ?></td>

                            <?php } ?>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                4. Solusi Ideal Positif (A<sup>+</sup>)

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <?php

                        $header = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($h = mysqli_fetch_assoc($header)) {

                        ?>

                            <th><?= $h['kode_kriteria']; ?></th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <?php

                        $kriteriaIdeal = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($k = mysqli_fetch_assoc($kriteriaIdeal)) {

                            $idK = $k['id_kriteria'];
                            $atribut = $k['atribut'];
                            $bobot = $k['bobot'];

                            $maxMin = mysqli_query(
                                $conn,
                                "SELECT
                            MAX(nilai) AS max_n,
                            MIN(nilai) AS min_n
                        FROM penilaian
                        WHERE id_kriteria='$idK'"
                            );

                            $data = mysqli_fetch_assoc($maxMin);

                            $yMax = ($data['max_n'] / $pembagi[$idK]) * $bobot;
                            $yMin = ($data['min_n'] / $pembagi[$idK]) * $bobot;

                            $aPlus = ($atribut == 'Benefit')
                                ? $yMax
                                : $yMin;

                        ?>

                            <td><?= number_format($aPlus, 4); ?></td>

                        <?php } ?>

                    </tr>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                5. Solusi Ideal Negatif (A<sup>-</sup>)

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <?php

                        $header = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($h = mysqli_fetch_assoc($header)) {

                        ?>

                            <th><?= $h['kode_kriteria']; ?></th>

                        <?php } ?>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <?php

                        $kriteriaIdeal = mysqli_query(
                            $conn,
                            "SELECT * FROM kriteria ORDER BY id_kriteria"
                        );

                        while ($k = mysqli_fetch_assoc($kriteriaIdeal)) {

                            $idK     = $k['id_kriteria'];
                            $atribut = $k['atribut'];
                            $bobot   = $k['bobot'];

                            $maxMin = mysqli_query(
                                $conn,
                                "SELECT
                            MAX(nilai) AS max_n,
                            MIN(nilai) AS min_n
                        FROM penilaian
                        WHERE id_kriteria='$idK'"
                            );

                            $data = mysqli_fetch_assoc($maxMin);

                            $yMax = ($data['max_n'] / $pembagi[$idK]) * $bobot;
                            $yMin = ($data['min_n'] / $pembagi[$idK]) * $bobot;

                            // Benefit = minimum
                            // Cost = maksimum
                            $aMin = ($atribut == 'Benefit')
                                ? $yMin
                                : $yMax;

                        ?>

                            <td><?= number_format($aMin, 4); ?></td>

                        <?php } ?>

                    </tr>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                6. Jarak ke Solusi Ideal

            </h5>

            <table class="table table-bordered text-center">

                <thead class="table-primary">

                    <tr>

                        <th>Alternatif</th>

                        <th>D<sup>+</sup></th>

                        <th>D<sup>-</sup></th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    // Menyimpan Solusi Ideal Positif dan Negatif
                    $idealPos = [];
                    $idealNeg = [];

                    $kriteriaIdeal = mysqli_query(
                        $conn,
                        "SELECT * FROM kriteria ORDER BY id_kriteria"
                    );

                    while ($k = mysqli_fetch_assoc($kriteriaIdeal)) {

                        $idK     = $k['id_kriteria'];
                        $atribut = $k['atribut'];
                        $bobot   = $k['bobot'];

                        $maxMin = mysqli_query(
                            $conn,
                            "SELECT
                        MAX(nilai) AS max_n,
                        MIN(nilai) AS min_n
                    FROM penilaian
                    WHERE id_kriteria='$idK'"
                        );

                        $data = mysqli_fetch_assoc($maxMin);

                        $yMax = ($data['max_n'] / $pembagi[$idK]) * $bobot;
                        $yMin = ($data['min_n'] / $pembagi[$idK]) * $bobot;

                        $idealPos[$idK] = ($atribut == 'Benefit') ? $yMax : $yMin;
                        $idealNeg[$idK] = ($atribut == 'Benefit') ? $yMin : $yMax;
                    }

                    $alternatif = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alternatif)) {

                        $sumPlus = 0;
                        $sumMin  = 0;

                        $nilai = mysqli_query(
                            $conn,
                            "SELECT
                        p.nilai,
                        p.id_kriteria,
                        k.bobot
                    FROM penilaian p
                    JOIN kriteria k
                    ON p.id_kriteria = k.id_kriteria
                    WHERE p.id_alternatif='$a[id_alternatif]'
                    ORDER BY p.id_kriteria"
                        );

                        while ($n = mysqli_fetch_assoc($nilai)) {

                            $idK = $n['id_kriteria'];

                            $r = $n['nilai'] / $pembagi[$idK];
                            $y = $r * $n['bobot'];

                            $sumPlus += pow($y - $idealPos[$idK], 2);
                            $sumMin  += pow($y - $idealNeg[$idK], 2);
                        }

                        $dPlus = sqrt($sumPlus);
                        $dMin  = sqrt($sumMin);

                    ?>

                        <tr>

                            <td><?= $a['kode_alternatif']; ?></td>

                            <td><?= number_format($dPlus, 4); ?></td>

                            <td><?= number_format($dMin, 4); ?></td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <h5 class="fw-bold mt-5 mb-3">

                7. Nilai Preferensi (V)

            </h5>

            <table class="table table-bordered text-center">

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

                    // Hitung kembali Solusi Ideal
                    $idealPos = [];
                    $idealNeg = [];

                    $kriteriaIdeal = mysqli_query(
                        $conn,
                        "SELECT * FROM kriteria ORDER BY id_kriteria"
                    );

                    while ($k = mysqli_fetch_assoc($kriteriaIdeal)) {

                        $idK     = $k['id_kriteria'];
                        $atribut = $k['atribut'];
                        $bobot   = $k['bobot'];

                        $maxMin = mysqli_query(
                            $conn,
                            "SELECT
                        MAX(nilai) AS max_n,
                        MIN(nilai) AS min_n
                    FROM penilaian
                    WHERE id_kriteria='$idK'"
                        );

                        $data = mysqli_fetch_assoc($maxMin);

                        $yMax = ($data['max_n'] / $pembagi[$idK]) * $bobot;
                        $yMin = ($data['min_n'] / $pembagi[$idK]) * $bobot;

                        $idealPos[$idK] = ($atribut == 'Benefit') ? $yMax : $yMin;
                        $idealNeg[$idK] = ($atribut == 'Benefit') ? $yMin : $yMax;
                    }

                    $alternatif = mysqli_query(
                        $conn,
                        "SELECT * FROM alternatif ORDER BY id_alternatif"
                    );

                    while ($a = mysqli_fetch_assoc($alternatif)) {

                        $sumPlus = 0;
                        $sumMin  = 0;

                        $nilai = mysqli_query(
                            $conn,
                            "SELECT
                        p.nilai,
                        p.id_kriteria,
                        k.bobot
                    FROM penilaian p
                    JOIN kriteria k
                    ON p.id_kriteria = k.id_kriteria
                    WHERE p.id_alternatif='$a[id_alternatif]'
                    ORDER BY p.id_kriteria"
                        );

                        while ($n = mysqli_fetch_assoc($nilai)) {

                            $idK = $n['id_kriteria'];

                            $r = $n['nilai'] / $pembagi[$idK];
                            $y = $r * $n['bobot'];

                            $sumPlus += pow($y - $idealPos[$idK], 2);
                            $sumMin  += pow($y - $idealNeg[$idK], 2);
                        }

                        $dPlus = sqrt($sumPlus);
                        $dMin  = sqrt($sumMin);

                        $penyebut = $dPlus + $dMin;

                        $preferensi = ($penyebut != 0)
                            ? $dMin / $penyebut
                            : 0;

                    ?>

                        <tr>

                            <td><?= $a['kode_alternatif']; ?></td>

                            <td><?= number_format($dPlus, 4); ?></td>

                            <td><?= number_format($dMin, 4); ?></td>

                            <td>

                                <strong>

                                    <?= number_format($preferensi, 4); ?>

                                </strong>

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

            <div class="ttd">

                <p class="text-center">

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

    </body>

    </html>