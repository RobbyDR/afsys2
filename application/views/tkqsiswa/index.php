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
            <table class="table table-striped table-sm" id="tkqsiswatable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">nama</th>
                        <th scope="col">deskripsi</th>
                        <th scope="col">tanggal mulai</th>
                        <th scope="col">tanggal selesai</th>
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
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td><?= $row['tgl1'] ?></td>
                            <td><?= ($row['tgl2'] == '0000-00-00') ? "" : $row['selesai'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu">
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewModal">View</button>
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
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
            <form action="<?= base_url() ?>tkqsiswa/tambah" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">

                        <div class="mb-3 col">
                            <label for="nama" class="col-form-label">nama</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="mb-3 col">
                            <label for="tgl1" class="col-form-label">tanggal mulai</label>
                            <input type="date" class="form-control" name="tgl1" value=<?= date('Y-m-d') ?>>
                        </div>
                        <div class="mb-3 col">
                            <label for="tgl2" class="col-form-label">tanggal selesai</label>
                            <input type="date" class="form-control" name="tgl2" ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="deskripsi" class="col-form-label">deskripsi</label>
                            <textarea class="form-control" name="deskripsi"></textarea>
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
                url: "<?= base_url() ?>tkqsiswa/getview",
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
                url: "<?= base_url() ?>tkqsiswa/getedit",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getedit").html(response);
            });
        });

        $('#tkqsiswatable').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            pageLength: -1,
            order: [
                [7, 'asc'],
                [4, 'asc']
            ]
        });
    });
</script>