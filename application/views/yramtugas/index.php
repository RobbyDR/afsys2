<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= $judul ?></h1>
            </div>
            <div class="col d-flex justify-content-end">
                <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah</button>
                <!-- <button type="button" class="btn btn-primary btn-sm">Small button</button> -->
            </div>
        </div>


        <div class="table-responsive small">
            <table class="table table-striped table-sm" id="yramtugastable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">no</th>
                        <th scope="col">namapegawai</th>
                        <th scope="col">judul</th>
                        <th scope="col">tanggal</th>
                        <th scope="col">tempat</th>
                        <th scope="col">keterangan</th>
                        <th scope="col">status</th>
                        <th scope="col">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get as $row): ?>
                        <!-- class="text-decoration-line-through" -->
                        <tr class="<?= $row['status'] == "2" ? 'fw-lighter' : ''; ?>">
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['no'] ?></td>
                            <td><?= $row['namapegawai'] ?></td>
                            <td><?= $row['judul'] ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['tempat'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu">
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewModal">View</button>
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload</button>
                                        <li><a class="dropdown-item disabled" href="#">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<!-- tambahModal -->
<div class="modal modal-xl fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Tambah Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>yramtugas/tambah" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="nip" class="col-form-label">nama</label>
                            <select class="form-select" name="nip">
                                <option>---pilih---</option>
                                <?php foreach ($getnip as $row): ?>
                                    <option value="<?= $row['nip'] ?>"><?= $row['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="no" class="col-form-label">no</label>
                            <input type="text" class="form-control" name="no">
                        </div>
                        <div class="mb-3 col">
                            <label for="judul" class="col-form-label">judul</label>
                            <input type="text" class="form-control" name="judul">
                        </div>
                        <div class="mb-3 col">
                            <label for="tempat" class="col-form-label">tempat</label>
                            <input type="text" class="form-control" name="tempat">
                        </div>
                        <div class="mb-3 col">
                            <label for="tanggal" class="col-form-label">tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value=<?= date('Y-m-d') ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="isi" class="col-form-label">isi</label>
                            <textarea class="form-control" name="isi"></textarea>
                        </div>
                        <div class="mb-3 col">
                            <label for="keterangan" class="col-form-label">keterangan</label>
                            <textarea class="form-control" name="keterangan"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- viewModal -->
<div class="modal modal-lg fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewModalLabel">View Data</h1>
            </div>
            <div id="getview"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- uploadModal -->
<div class="modal modal-xl fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uploadModalLabel">Upload Data</h1>
            </div>
            <div id="getupload"></div>
        </div>
    </div>
</div>

<!-- editModal -->
<div class="modal modal-xl fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Data</h1>
            </div>
            <div id="getedit"></div>
        </div>
    </div>
</div>

<!-- renameModal -->
<div class="modal modal-xl fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="renameModalLabel">Rename</h1>
            </div>
            <div id="getrename"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#viewModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>yramtugas/getview",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getview").html(response);
            });
        });

        $("#editModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>yramtugas/getedit",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getedit").html(response);
            });
        });

        $("#uploadModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>yramtugas/getupload",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getupload").html(response);
            });
        });

        $("#renameModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            let id = $(e.relatedTarget).data('id');
            // console.log(id);
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>yramtugas/getrename",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getrename").html(response);
            });
        });

        $('#yramtugastable').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            pageLength: -1,
            order: [
                [6, 'asc'],
                [4, 'asc']
            ]
        });
    });

    function delete_data(id, nama, path) {
        Swal.fire({
            title: 'Konfirmasi ?',
            text: "Apakah kamu yakin ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            showLoaderOnConfirm: true,
            preConfirm: function() {

                var csfrData = {};
                csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?=
                                                                                $this->security->get_csrf_hash(); ?>';
                $.ajaxSetup({
                    data: csfrData
                });

                return new Promise(function(resolve, reject) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url() ?>yramtugas/delfile",
                        data: {
                            id: id,
                            nama: nama,
                            path: path
                        }
                    }).done(function(msg) {
                        console.log(msg);
                        if (msg == "ok") {
                            swal.fire("OK!", "Data berhasil dihapus!", "success").then(function() {
                                location.reload();
                                // let status = $($(e.target).attr('href')).data('status');
                                // window.location.href = "<?= base_url('admin/ithelpdeska/index/') ?>" + status;
                            })
                        } else {
                            swal.fire("Gagal!", msg, "error").then(function() {
                                location.reload();
                            })
                        }
                    })
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }
</script>