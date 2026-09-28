    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "penilaian";
    $title = "Data Penilaian";

    $query = mysqli_query($conn, " SELECT a.id_alternatif, a.kode_alternatif, a.nama_alternatif,

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
    ON a.id_alternatif=p.id_alternatif

    GROUP BY
    a.id_alternatif,
    a.kode_alternatif,
    a.nama_alternatif

    ORDER BY a.id_alternatif
    ");

    // Tambah Penilaian
    if (isset($_POST['simpan'])) {

        $id_alternatif = $_POST['id_alternatif'];

        // Cek apakah alternatif sudah pernah dinilai
        $cek = mysqli_query($conn, " SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif' ");

        if (mysqli_num_rows($cek) > 0) {

            echo "<script>
            alert('Alternatif ini sudah memiliki penilaian.');
            location='penilaian.php';
        </script>";

            exit;
        }

        for ($i = 1; $i <= 8; $i++) {

            $nilai = $_POST["c$i"];

            mysqli_query($conn, "
            INSERT INTO penilaian
            (
                id_alternatif,
                id_kriteria,
                nilai
            )
            VALUES
            (
                '$id_alternatif',
                '$i',
                '$nilai'
            )
        ");
        }

        echo "<script>
        alert('Data berhasil disimpan');
        location='penilaian.php';
        </script>";
    }

    // Update Penilaian
    if (isset($_POST['update'])) {

        $id = $_POST['id_alternatif'];

        for ($i = 1; $i <= 8; $i++) {

            $nilai = $_POST["c$i"];

            mysqli_query($conn, " UPDATE penilaian SET nilai='$nilai' WHERE id_alternatif='$id' AND id_kriteria='$i' ");
        }

        echo "<script>
            alert('Data berhasil diubah');
            location='penilaian.php';
        </script>";
    }

    // Hapus Penilaian
    if (isset($_POST['hapus'])) {

        $id = $_POST['id_alternatif'];

        mysqli_query($conn, " DELETE FROM penilaian WHERE id_alternatif='$id' ");

        echo "<script>
            alert('Data berhasil dihapus');
            location='penilaian.php';
        </script>";
    }


    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-ui-checks-grid"></i>
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

                        Kelola data penilaian alternatif berdasarkan setiap kriteria

                    </small>

                </div>

                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">

                    <i class="bi bi-plus-circle-fill"></i>

                    Tambah Penilaian

                </button>

            </div>
            <!-- ===================== -->
            <!-- Data Penilaian -->
            <!-- ===================== -->

            <div class="card shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span class="fw-semibold">

                        Data Penilaian Alternatif

                    </span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle text-center mb-0">

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
                                    <th>Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($data = mysqli_fetch_assoc($query)):

                                ?>

                                    <tr>

                                        <td><?= $no++ ?></td>

                                        <td><?= $data['kode_alternatif'] ?></td>

                                        <td class="text-start">
                                            <?= $data['nama_alternatif'] ?>
                                        </td>

                                        <td><?= $data['C1'] ?></td>
                                        <td><?= $data['C2'] ?></td>
                                        <td><?= $data['C3'] ?></td>
                                        <td><?= $data['C4'] ?></td>
                                        <td><?= $data['C5'] ?></td>
                                        <td><?= $data['C6'] ?></td>
                                        <td><?= $data['C7'] ?></td>
                                        <td><?= $data['C8'] ?></td>

                                        <td>

                                            <button
                                                class="btn btn-warning btn-sm btn-edit"

                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit"

                                                data-id="<?= $data['id_alternatif']; ?>"

                                                data-c1="<?= $data['C1']; ?>"
                                                data-c2="<?= $data['C2']; ?>"
                                                data-c3="<?= $data['C3']; ?>"
                                                data-c4="<?= $data['C4']; ?>"
                                                data-c5="<?= $data['C5']; ?>"
                                                data-c6="<?= $data['C6']; ?>"
                                                data-c7="<?= $data['C7']; ?>"
                                                data-c8="<?= $data['C8']; ?>">

                                                <i class="bi bi-pencil-square"></i>

                                            </button>

                                            <button
                                                class="btn btn-danger btn-sm btn-hapus"

                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapus"

                                                data-id="<?= $data['id_alternatif']; ?>"
                                                data-nama="<?= $data['nama_alternatif']; ?>">

                                                <i class="bi bi-trash-fill"></i>

                                            </button>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- MODAL TAMBAH -->

    <div class="modal fade" id="modalTambah" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">

                        Tambah Penilaian

                    </h5>

                    <button
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <form action="" method="POST">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Alternatif

                            </label>

                            <select
                                name="id_alternatif"
                                class="form-select"
                                required>

                                <?php

                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ASC ");

                                while ($a = mysqli_fetch_assoc($alternatif)):

                                ?>

                                    <option
                                        value="<?= $a['id_alternatif'] ?>">

                                        <?= $a['kode_alternatif'] ?>

                                        -

                                        <?= $a['nama_alternatif'] ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label>C1</label>
                                <input
                                    type="number"
                                    name="c1"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C2</label>
                                <input
                                    type="number"
                                    name="c2"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C3</label>
                                <input
                                    type="number"
                                    name="c3"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C4</label>
                                <input
                                    type="number"
                                    name="c4"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C5</label>
                                <input
                                    type="number"
                                    name="c5"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C6</label>
                                <input
                                    type="number"
                                    name="c6"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C7</label>
                                <input
                                    type="number"
                                    name="c7"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label>C8</label>
                                <input
                                    type="number"
                                    name="c8"
                                    class="form-control"
                                    required>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            type="button">

                            Batal

                        </button>

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="modalEdit" tabindex="-1">

        <div class="modal-dialog modal-lg">

            <div class="modal-content">

                <div class="modal-header bg-warning">

                    <h5 class="modal-title">

                        Edit Penilaian

                    </h5>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <form action="" method="POST">

                    <input
                        type="hidden"
                        name="id_alternatif"
                        id="edit_id">

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Alternatif

                            </label>

                            <select
                                id="edit_alternatif"
                                class="form-select"
                                disabled>

                                <?php

                                $alternatif = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ");

                                while ($a = mysqli_fetch_assoc($alternatif)):

                                ?>

                                    <option
                                        value="<?= $a['id_alternatif'] ?>">

                                        <?= $a['kode_alternatif'] ?>

                                        -

                                        <?= $a['nama_alternatif'] ?>

                                    </option>

                                <?php endwhile; ?>

                            </select>

                        </div>

                        <div class="row">

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C1</label>

                                <input
                                    type="number"
                                    name="c1"
                                    id="edit_c1"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C2</label>

                                <input
                                    type="number"
                                    name="c2"
                                    id="edit_c2"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C3</label>

                                <input
                                    type="number"
                                    name="c3"
                                    id="edit_c3"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C4</label>

                                <input
                                    type="number"
                                    name="c4"
                                    id="edit_c4"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C5</label>

                                <input
                                    type="number"
                                    name="c5"
                                    id="edit_c5"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C6</label>

                                <input
                                    type="number"
                                    name="c6"
                                    id="edit_c6"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C7</label>

                                <input
                                    type="number"
                                    name="c7"
                                    id="edit_c7"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                            <div class="col-md-3 mb-3">

                                <label class="form-label">C8</label>

                                <input
                                    type="number"
                                    name="c8"
                                    id="edit_c8"
                                    class="form-control"
                                    min="1"
                                    max="5"
                                    required>

                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            type="button">

                            Batal

                        </button>

                        <button

                            type="submit"

                            name="update"

                            class="btn btn-warning">

                            Simpan Perubahan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- MODAL HAPUS -->
    <div class="modal fade" id="modalHapus" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header bg-danger text-white">

                    <h5 class="modal-title">
                        Hapus Penilaian
                    </h5>

                    <button
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <form action="" method="POST">

                    <input
                        type="hidden"
                        name="id_alternatif"
                        id="hapus_id">

                    <div class="modal-body text-center">

                        <i class="bi bi-trash-fill text-danger"
                            style="font-size:70px;"></i>

                        <h5 class="mt-3">
                            Apakah Anda yakin?
                        </h5>

                        <p class="text-muted">

                            Data penilaian
                            <strong id="hapus_nama"></strong>
                            akan dihapus.

                        </p>

                    </div>

                    <div class="modal-footer justify-content-center">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button
                            type="submit"
                            name="hapus"
                            class="btn btn-danger">

                            Ya, Hapus

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <?php include '../layout/footer.php'; ?>