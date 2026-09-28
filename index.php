    <?php
    include "koneksi/koneksi.php";

    // Total Alternatif
    $totalAlternatif = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM alternatif"));

    // Total Kriteria
    $totalKriteria = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM kriteria"));

    // Ranking Pertama
    $ranking = mysqli_query($conn, "
    SELECT ranking.*, alternatif.nama_alternatif
    FROM ranking
    JOIN alternatif
    ON ranking.id_alternatif = alternatif.id_alternatif
    ORDER BY ranking ASC
    LIMIT 1
    ");

    $dataRanking = mysqli_fetch_assoc($ranking);

    $top3 = mysqli_query($conn, "
    SELECT
        r.ranking,
        r.nilai_preferensi,
        a.nama_alternatif
    FROM ranking r
    JOIN alternatif a
    ON r.id_alternatif = a.id_alternatif
    ORDER BY r.ranking ASC
    LIMIT 3
    ");

    $data = [];

    while ($row = mysqli_fetch_assoc($top3)) {
        $data[] = $row;
    }

    ?>

    <!DOCTYPE html>
    <html lang="id">

    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SPK TOPSIS | Toko Sentral Asesoris Motor</title>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Bootstrap Icon -->
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    </head>

    <body class="bg-light">

        <!-- ================= NAVBAR ================= -->

        <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">

            <div class="container">

                <a class="navbar-brand fw-bold text-primary" href="#">
                    <i class="bi bi-speedometer2"></i>
                    SPK TOPSIS
                </a>

                <button class="navbar-toggler"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbar">

                    <span class="navbar-toggler-icon"></span>

                </button>

                <div class="collapse navbar-collapse" id="navbar">

                    <ul class="navbar-nav ms-auto align-items-lg-center">

                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Beranda</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="customer/ranking.php">
                                Ranking
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="customer/about.php">
                                Tentang
                            </a>
                        </li>

                        <li class="nav-item ms-lg-3">

                            <a href="login.php"
                                class="btn btn-primary">

                                <i class="bi bi-person-lock"></i>
                                Admin

                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </nav>

        <!-- ================= HERO ================= -->

        <section class="bg-primary text-white py-5">

            <div class="container">

                <div class="row align-items-center">

                    <div class="col-lg-6">

                        <span class="badge bg-light text-primary mb-3 px-3 py-2">

                            Sistem Pendukung Keputusan

                        </span>

                        <h1 class="display-5 fw-bold mb-3">

                            Menentukan Aksesoris Motor Terbaik
                            Menggunakan Metode TOPSIS

                        </h1>

                        <p class="lead mb-4">

                            Website ini membantu pelanggan melihat rekomendasi
                            aksesoris motor terbaik berdasarkan proses perhitungan
                            menggunakan metode TOPSIS sehingga hasil lebih objektif,
                            cepat, dan akurat.

                        </p>

                        <a href="customer/ranking.php" class="btn btn-light btn-lg">

                            <i class="bi bi-trophy-fill"></i>

                            Lihat Ranking

                        </a>

                    </div>

                    <div class="col-lg-6 text-center">

                        <img src="assets/image.png"
                            class="img-fluid"
                            style="max-height:250px;"
                            alt="Motor">

                    </div>

                </div>

            </div>

        </section>

        <!-- ================= STATISTIK ================= -->

        <section class="py-5">

            <div class="container">

                <div class="row g-4">

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0">

                            <div class="card-body text-center">

                                <i class="bi bi-box text-primary fs-1"></i>

                                <h2 class="mt-3"><?= $totalAlternatif ?></h2>

                                <p class="text-muted mb-0">
                                    Total Aksesoris
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0">

                            <div class="card-body text-center">

                                <i class="bi bi-list-check text-success fs-1"></i>

                                <h2 class="mt-3"><?= $totalKriteria ?></h2>

                                <p class="text-muted mb-0">
                                    Kriteria
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0">

                            <div class="card-body text-center">

                                <i class="bi bi-award text-warning fs-1"></i>

                                <h2 class="mt-3">TOPSIS</h2>

                                <p class="text-muted mb-0">
                                    Metode
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="card shadow-sm border-0">

                            <div class="card-body text-center">

                                <i class="bi bi-trophy text-danger fs-1"></i>

                                <h2 class="mt-3">

                                    <?= $dataRanking['nama_alternatif'] ?>

                                </h2>

                                <p class="text-muted mb-0">
                                    Ranking Pertama
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <!-- ================= RANKING ================= -->

        <section class="pb-5">

            <div class="container">

                <div class="text-center mb-5">

                    <h2 class="fw-bold">

                        Top 3 Rekomendasi Terbaik

                    </h2>

                    <p class="text-muted">

                        Hasil perhitungan metode TOPSIS

                    </p>

                </div>

                <div class="row justify-content-center g-4">

                    <!-- Ranking 2 -->

                    <div class="col-lg-3 col-md-6">

                        <div class="card border-0 shadow text-center h-100">

                            <div class="card-body">

                                <div class="display-3">

                                    🥈

                                </div>

                                <h5 class="fw-bold">
                                    <?= $data[1]['nama_alternatif']; ?>
                                </h5>

                                <span class="badge bg-secondary">
                                    Nilai :
                                    <?= number_format($data[1]['nilai_preferensi'], 4); ?>
                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- Ranking 1 -->

                    <div class="col-lg-4 col-md-6">

                        <div class="card border-0 shadow-lg text-center h-100">

                            <div class="card-body">

                                <div class="display-1">

                                    🥇

                                </div>

                                <h3 class="fw-bold text-primary">
                                    <?= $data[0]['nama_alternatif']; ?>
                                </h3>

                                <span class="badge bg-success fs-6">
                                    Nilai :
                                    <?= number_format($data[0]['nilai_preferensi'], 4); ?>
                                </span>

                            </div>

                        </div>

                    </div>

                    <!-- Ranking 3 -->

                    <div class="col-lg-3 col-md-6">

                        <div class="card border-0 shadow text-center h-100">

                            <div class="card-body">

                                <div class="display-3">

                                    🥉

                                </div>

                                <h5 class="fw-bold">
                                    <?= $data[2]['nama_alternatif']; ?>
                                </h5>

                                <span class="badge bg-secondary">
                                    Nilai :
                                    <?= number_format($data[2]['nilai_preferensi'], 4); ?>
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
            <div class="text-center mt-5">

                <a href="customer/ranking.php" class="btn btn-primary btn-lg">

                    <i class="bi bi-arrow-right-circle"></i>

                    Lihat Selengkapnya

                </a>

            </div>
        </section>


        <!-- ================= FOOTER ================= -->

        <footer class="bg-dark text-white py-4">

            <div class="container text-center">

                <h5>Toko Sentral Asesoris Motor</h5>

                <p class="mb-0">

                    Sistem Pendukung Keputusan menggunakan metode TOPSIS

                </p>

            </div>

        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>