    <?php
    session_start();
    include '../koneksi/koneksi.php';

    if (!isset($_SESSION['login'])) {
        header("Location: login.php");
        exit;
    }

    $menu = "alternatif";
    $title = "Data Alternatif";

    $query = mysqli_query($conn, " SELECT * FROM alternatif ORDER BY id_alternatif ASC ");

    // Tambah data alternatif
    $queryKode = mysqli_query($conn, " SELECT kode_alternatif FROM alternatif ORDER BY id_alternatif DESC LIMIT 1 ");

    $dataKode = mysqli_fetch_assoc($queryKode);

    if ($dataKode) {

        $angka = (int) substr($dataKode['kode_alternatif'], 1);
        $kodeBaru = "A" . ($angka + 1);
    } else {

        $kodeBaru = "A1";
    }

    if (isset($_POST['simpan'])) {

        $kode   = $_POST['kode_alternatif'];
        $nama   = $_POST['nama_alternatif'];
        $kategori = $_POST['kategori'];
        $merek  = $_POST['merek'];
        $harga  = $_POST['harga'];
        $status = $_POST['status'];

        $cek = mysqli_query(
            $conn,
            "SELECT * FROM alternatif
         WHERE kode_alternatif='$kode'"
        );

        if (mysqli_num_rows($cek) > 0) {

            echo "<script>
            alert('Kode alternatif sudah ada');
            location='alternatif.php';
        </script>";

            exit;
        }

        mysqli_query($conn, " INSERT INTO alternatif VALUES(
            NULL,
            '$kode',
            '$nama',
            '$kategori',
            '$merek',
            '$harga',
            '$status'
            )
        ");

        echo "<script>
        alert('Data berhasil disimpan');
        location='alternatif.php';
        </script>";
    }

    // Edit data alternatif
    if (isset($_POST['update'])) {

        $id     = $_POST['id_alternatif'];
        $kode   = $_POST['kode_alternatif'];
        $nama   = $_POST['nama_alternatif'];
        $merek  = $_POST['merek'];
        $harga  = $_POST['harga'];
        $status = $_POST['status'];

        mysqli_query($conn, " UPDATE alternatif SET
            kode_alternatif='$kode',
            nama_alternatif='$nama',
            merek='$merek',
            harga='$harga',
            status='$status'
        WHERE id_alternatif='$id'
        ");

        echo "<script>
        alert('Data berhasil diubah');
        location='alternatif.php';
        </script>";
    }

    // Hapus data alternatif
    if (isset($_POST['hapus'])) {

        $id = $_POST['id_alternatif'];

        mysqli_query($conn, " DELETE FROM alternatif WHERE id_alternatif='$id' ");

        echo "<script>
        alert('Data berhasil dihapus');
        location='alternatif.php';
    </script>";
    }

    include '../layout/header.php';
    include '../layout/sidebar.php';
    ?>

    <div class="content" id="content">

        <nav class="navbar navbar-expand-lg topbar">

            <div class="container-fluid">

                <span class="navbar-brand">

                    <i class="bi bi-box-seam"></i>
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

                        Kelola seluruh data alternatif aksesoris motor

                    </small>

                </div>

                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">

                    <i class="bi bi-plus-circle"></i>

                    Tambah Alternatif

                </button>

            </div>

            <div class="card">

                <div class="card-header d-flex justify-content-between align-items-center">

                    <span>Data Alternatif</span>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead>

                                <tr>

                                    <th>No</th>

                                    <th>Kode</th>

                                    <th>Nama Alternatif</th>

                                    <th>Merek</th>

                                    <th>Harga</th>

                                    <th>Status</th>

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

                                        <td><?= $data['kode_alternatif'] ?></td>

                                        <td><?= $data['nama_alternatif'] ?></td>

                                        <td><?= $data['merek'] ?></td>

                                        <td>
                                            Rp<?= number_format($data['harga'], 0, ',', '.') ?>
                                        </td>

                                        <td>

                                            <?php if ($data['status'] == "Tersedia") { ?>

                                                <span class="badge bg-success">
                                                    Tersedia
                                                </span>

                                            <?php } else { ?>

                                                <span class="badge bg-danger">
                                                    Habis
                                                </span>

                                            <?php } ?>

                                        </td>

                                        <td>

                                            <button
                                                class="btn btn-warning btn-sm me-1 btn-edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit"

                                                data-id="<?= $data['id_alternatif']; ?>"
                                                data-kode="<?= $data['kode_alternatif']; ?>"
                                                data-nama="<?= $data['nama_alternatif']; ?>"
                                                data-merek="<?= $data['merek']; ?>"
                                                data-harga="<?= $data['harga']; ?>"
                                                data-status="<?= $data['status']; ?>">

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


            <!-- MODAL TAMBAH -->
            <div class="modal fade" id="modalTambah" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header bg-primary text-white">

                            <h5 class="modal-title">

                                Tambah Alternatif

                            </h5>

                            <button
                                type="button"
                                class="btn-close btn-close-white"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <form action="" method="POST">

                            <div class="modal-body">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Kode Alternatif

                                    </label>

                                    <input
                                        type="text"
                                        name="kode_alternatif"
                                        class="form-control"
                                        value="<?= $kodeBaru ?>"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Nama Alternatif

                                    </label>

                                    <input
                                        type="text"
                                        name="nama_alternatif"
                                        class="form-control"
                                        placeholder="Masukkan nama alternatif"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Merek

                                    </label>

                                    <input
                                        type="text"
                                        name="merek"
                                        class="form-control"
                                        placeholder="Masukkan merek"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Harga

                                    </label>

                                    <input
                                        type="number"
                                        name="harga"
                                        class="form-control"
                                        placeholder="Masukkan harga"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">

                                        Status Stok

                                    </label>

                                    <select
                                        name="status"
                                        class="form-select"
                                        required>

                                        <option value="" selected disabled>
                                            -- Pilih Status --
                                        </option>

                                        <option value="Tersedia">
                                            Tersedia
                                        </option>

                                        <option value="Habis">
                                            Habis
                                        </option>

                                    </select>

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
                                    name="simpan"
                                    class="btn btn-primary">

                                    Simpan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <!-- Modal Edit -->

            <div class="modal fade" id="modalEdit" tabindex="-1">

                <div class="modal-dialog">

                    <div class="modal-content">

                        <div class="modal-header bg-warning">

                            <h5 class="modal-title">

                                Edit Alternatif

                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>

                        <form action="" method="POST">

                            <div class="modal-body">

                                <input
                                    type="hidden"
                                    name="id_alternatif"
                                    id="edit_id_alternatif">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Kode Alternatif
                                    </label>

                                    <input
                                        type="text"
                                        name="kode_alternatif"
                                        id="edit_kode"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Alternatif
                                    </label>

                                    <input
                                        type="text"
                                        name="nama_alternatif"
                                        id="edit_nama"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Merek
                                    </label>

                                    <input
                                        type="text"
                                        name="merek"
                                        id="edit_merek"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Harga
                                    </label>

                                    <input
                                        type="number"
                                        name="harga"
                                        id="edit_harga"
                                        class="form-control"
                                        required>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select
                                        name="status"
                                        id="edit_status"
                                        class="form-select"
                                        required>

                                        <option value="Tersedia">Tersedia</option>
                                        <option value="Habis">Habis</option>

                                    </select>

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
                                Hapus Alternatif
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
                                name="id_alternatif"
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

        </div>

    </div>

    <?php include '../layout/footer.php'; ?>