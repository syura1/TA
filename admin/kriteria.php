    <?php
    session_start();
    include "../koneksi/koneksi.php";

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "kriteria";
    $title = "Data Kriteria";
    $query = mysqli_query($conn, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");

    //tambah data kriteria
    $queryKode = mysqli_query($conn, "
        SELECT kode_kriteria
        FROM kriteria
        ORDER BY id_kriteria DESC
        LIMIT 1
    ");

    $dataKode = mysqli_fetch_assoc($queryKode);

    if ($dataKode) {
        $angka = (int) substr($dataKode['kode_kriteria'], 1);
        $kodeBaru = "C" . ($angka + 1);
    } else {
        $kodeBaru = "C1";
    }

    if (isset($_POST['simpan'])) {

        $kode = $_POST['kode_kriteria'];
        $nama = $_POST['nama_kriteria'];
        $atribut = $_POST['atribut'];
        $bobot = $_POST['bobot'];

        $cek = mysqli_query(
            $conn,
            "SELECT * FROM kriteria
             WHERE kode_kriteria='$kode'"
        );
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>
            alert('Kode kriteria sudah ada');
            location='kriteria.php';
        </script>";
            exit;
        }

        mysqli_query($conn, "
            INSERT INTO kriteria
            VALUES(
                NULL,
                '$kode',
                '$nama',
                '$atribut',
                '$bobot'
            )
        ");

        echo "<script>
            alert('Data berhasil disimpan');
            location='kriteria.php';
        </script>";
    }

    //edit data kriteria
    if (isset($_POST['update'])) {

        $id = $_POST['id_kriteria'];
        $kode = $_POST['kode_kriteria'];
        $nama = $_POST['nama_kriteria'];
        $atribut = $_POST['atribut'];
        $bobot = $_POST['bobot'];

        mysqli_query($conn, "
            UPDATE kriteria
            SET

            kode_kriteria='$kode',
            nama_kriteria='$nama',
            atribut='$atribut',
            bobot='$bobot'

            WHERE id_kriteria='$id'
        ");

        echo "<script>

            alert('Data berhasil diubah');

            location='kriteria.php';

        </script>";
    }

    //hapus data kriteria
    if (isset($_POST['hapus'])) {

        $id = $_POST['id_kriteria'];

        mysqli_query($conn, "
            DELETE FROM kriteria
            WHERE id_kriteria='$id'
        ");

        echo "<script>

            alert('Data berhasil dihapus');

            location='kriteria.php';

        </script>";
    }


    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-list-check"></i>
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

                    <h2 class="fw-bold">

                        Data Kriteria

                    </h2>

                    <small class="text-muted">

                        Kelola seluruh data kriteria TOPSIS

                    </small>

                </div>

                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">

                    <i class="bi bi-plus-circle"></i>

                    Tambah Kriteria

                </button>

            </div>

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-primary">

                                <tr>

                                    <th>No</th>

                                    <th>Kode</th>

                                    <th>Nama Kriteria</th>

                                    <th>Jenis</th>

                                    <th>Bobot</th>

                                    <th width="120">Aksi</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($data = mysqli_fetch_assoc($query)) :

                                ?>

                                    <tr>

                                        <td><?= $no++ ?></td>

                                        <td><?= $data['kode_kriteria'] ?></td>

                                        <td><?= $data['nama_kriteria'] ?></td>

                                        <td>

                                            <?php if ($data['atribut'] == "Benefit") { ?>

                                                <span class="badge bg-success">

                                                    Benefit

                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">

                                                    Cost

                                                </span>

                                            <?php } ?>

                                        </td>

                                        <td><?= $data['bobot'] ?></td>

                                        <td>

                                            <button
                                                class="btn btn-warning btn-sm btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit"

                                                data-id="<?= $data['id_kriteria']; ?>"
                                                data-kode="<?= $data['kode_kriteria']; ?>"
                                                data-nama="<?= $data['nama_kriteria']; ?>"
                                                data-atribut="<?= $data['atribut']; ?>"
                                                data-bobot="<?= $data['bobot']; ?>">

                                                <i class="bi bi-pencil-square"></i>

                                            </button>

                                            <button
                                                class="btn btn-danger btn-sm btn-hapus"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalHapus"

                                                data-id="<?= $data['id_kriteria']; ?>"
                                                data-nama="<?= $data['nama_kriteria']; ?>">

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

            <!-- MODAL TAMBAH -->
            <div class="modal fade" id="modalTambah" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title">

                                Tambah Kriteria

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

                                        Kode Kriteria

                                    </label>

                                    <input
                                        type="text"
                                        name="kode_kriteria"
                                        class="form-control"
                                        value="<?= $kodeBaru ?>"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Nama Kriteria

                                    </label>

                                    <input
                                        type="text"
                                        name="nama_kriteria"
                                        class="form-control"
                                        placeholder="Masukkan nama kriteria"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Jenis

                                    </label>

                                    <select
                                        name="atribut"
                                        class="form-select"
                                        required>
                                        <option value="" selected disabled>-- Pilih Jenis --</option>
                                        <option value="Benefit">Benefit</option>
                                        <option value="Cost">Cost</option>

                                    </select>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Bobot

                                    </label>

                                    <input
                                        type="number"
                                        name="bobot"
                                        class="form-control"
                                        placeholder="Masukkan bobot"
                                        min="1"
                                        max="5"
                                        step="1"
                                        required>

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
                                    name="simpan"
                                    class="btn btn-primary"
                                    type="submit">

                                    Simpan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <!-- MODAL EDIT -->
            <div class="modal fade" id="modalEdit" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header bg-warning">

                            <h5 class="modal-title">
                                Edit Kriteria
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <form action="" method="POST">

                            <div class="modal-body">

                                <input type="hidden" name="id_kriteria" id="edit_id_kriteria">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Kode Kriteria
                                    </label>

                                    <input
                                        type="text"
                                        name="kode_kriteria"
                                        id="edit_kode"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Kriteria
                                    </label>

                                    <input
                                        type="text"
                                        name="nama_kriteria"
                                        id="edit_nama"
                                        class="form-control"
                                        placeholder="Contoh: Harga"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Jenis
                                    </label>

                                    <select
                                        name="atribut"
                                        id="edit_atribut"
                                        class="form-select"
                                        required>

                                        <option value="Benefit">Benefit</option>
                                        <option value="Cost">Cost</option>

                                    </select>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Bobot
                                    </label>

                                    <input
                                        type="number"
                                        name="bobot"
                                        id="edit_bobot"
                                        class="form-control"
                                        min="1"
                                        max="5"
                                        step="1"
                                        required>

                                </div>

                            </div>

                            <div class="modal-footer">

                                <button
                                    type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">

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
                                Hapus Kriteria
                            </h5>

                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <form action="" method="POST">

                            <input
                                type="hidden"
                                name="id_kriteria"
                                id="hapus_id">

                            <div class="modal-body text-center">

                                <i class="bi bi-trash-fill text-danger"
                                    style="font-size:70px;"></i>

                                <h5 class="mt-3">
                                    Apakah Anda yakin?
                                </h5>

                                <p class="text-muted">

                                    Data
                                    <strong id="hapus_nama"></strong>
                                    akan dihapus dan tidak dapat dikembalikan.

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

            </body>

            </html>