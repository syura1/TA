    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        //script edit kriteria
        document.querySelectorAll('.btn-edit').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('edit_id_kriteria').value = this.dataset.id;
                document.getElementById('edit_kode').value = this.dataset.kode;
                document.getElementById('edit_nama').value = this.dataset.nama;
                document.getElementById('edit_atribut').value = this.dataset.atribut;
                document.getElementById('edit_bobot').value = this.dataset.bobot;

            });

        });

        //script hapus kriteria
        document.querySelectorAll('.btn-hapus').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('hapus_id').value = this.dataset.id;
                document.getElementById('hapus_nama').innerHTML = this.dataset.nama;

            });

        });

        //script edit alternatif
        document.querySelectorAll('.btn-edit').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('edit_id_alternatif').value = this.dataset.id;
                document.getElementById('edit_kode').value = this.dataset.kode;
                document.getElementById('edit_nama').value = this.dataset.nama;
                document.getElementById('edit_merek').value = this.dataset.merek;
                document.getElementById('edit_harga').value = this.dataset.harga;
                document.getElementById('edit_status').value = this.dataset.status;

            });

        });

        //script hapus alternatif
        document.querySelectorAll('.btn-hapus').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('hapus_id').value = this.dataset.id;
                document.getElementById('hapus_nama').textContent = this.dataset.nama;

            });

        });

        //script edit penilaian
        document.querySelectorAll('.btn-edit').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_alternatif').value = this.dataset.id;

                document.getElementById('edit_c1').value = this.dataset.c1;
                document.getElementById('edit_c2').value = this.dataset.c2;
                document.getElementById('edit_c3').value = this.dataset.c3;
                document.getElementById('edit_c4').value = this.dataset.c4;
                document.getElementById('edit_c5').value = this.dataset.c5;
                document.getElementById('edit_c6').value = this.dataset.c6;
                document.getElementById('edit_c7').value = this.dataset.c7;
                document.getElementById('edit_c8').value = this.dataset.c8;

            });

        });

        //script hapus penilaian
        document.querySelectorAll('.btn-hapus').forEach(function(btn) {

            btn.addEventListener('click', function() {

                document.getElementById('hapus_id').value = this.dataset.id;
                document.getElementById('hapus_nama').textContent = this.dataset.nama;

            });

        });

        //script lihat selengkapnya
        document.querySelectorAll(".btnLihat").forEach(function(btn) {

            btn.addEventListener("click", function() {

                const table = btn.closest(".table-responsive");

                const rows = table.querySelectorAll(".baris-lain");

                const buka = btn.dataset.open === "true";

                rows.forEach(function(row) {
                    row.classList.toggle("d-none");
                });

                if (buka) {

                    btn.innerHTML =
                        '<i class="bi bi-chevron-down"></i> Lihat Selengkapnya';

                    btn.dataset.open = "false";

                } else {

                    btn.innerHTML =
                        '<i class="bi bi-chevron-up"></i> Sembunyikan';

                    btn.dataset.open = "true";

                }

            });

        });

        //script cetak
        function printDiv(id) {

            var isi = document.getElementById(id).innerHTML;

            var halaman = window.open('', '', 'width=900,height=700');

            halaman.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>

                        <title>Laporan Ranking</title>

                        <link rel="stylesheet"
                            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

                        <style>

                            body{
                                margin:40px;
                                font-family:Arial, sans-serif;
                            }

                            h3,h5{
                                text-align:center;
                            }

                            table{
                                width:100%;
                                border-collapse:collapse;
                            }

                            table th,
                            table td{
                                border:1px solid #000;
                                padding:8px;
                                text-align:center;
                            }

                        </style>

                    </head>

                    <body>

                        ${isi}

                    </body>

                    </html>
                `);

            halaman.document.close();

            halaman.focus();

            halaman.print();

            halaman.close();

        }


            function cetakLaporan(url) {

                let win = window.open(url, "_blank");

                win.onload = function() {

                    win.print();

                };

            }
    
    </script>

    </body>

    </html>