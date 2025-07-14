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

        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link " aria-current="page" href="<?= base_url('mainkendaraan') ?>">Utama</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?= base_url('mainkendaraan/detail') ?>">Detail</a>
            </li>
        </ul>

        <div class="table-responsive small">
            <table class="table table-striped table-sm" id="mainkendaraantable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Main</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col">jumlah</th>
                        <th scope="col">harga</th>
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
                            <td><?= $row['mainnama'] ?></td>
                            <td><?= $row['maintgl1'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td><?= $row['jumlah'] ?></td>
                            <td><?= $row['harga'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu">
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewDetailModal">View</button>
                                        <button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#editDetailModal">Edit</button>
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
            <form action="<?= base_url() ?>mainkendaraan/tambahdetail" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">

                        <div class="mb-3 col">
                            <label for="mainid" class="col-form-label">mainid</label>
                            <select class="form-select" name="mainid" required>
                                <option value="" selected>---pilih---</option>
                                <?php foreach ($getmain as $row): ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?> <?= $row['tgl1'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3 col">
                            <label for="nama" class="col-form-label">nama</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="jumlah" class="col-form-label">jumlah</label>
                            <input type="text" class="form-control" name="jumlah" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="harga" class="col-form-label">harga</label>
                            <input type="text" class="form-control" name="harga" required>
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

<!-- viewDetailModal -->
<div class="modal modal-lg fade" id="viewDetailModal" tabindex="-1" aria-labelledby="viewDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewDetailModalLabel">View Data</h1>
            </div>
            <div id="getviewdetail"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- editDetailModal -->
<div class="modal modal-xl fade" id="editDetailModal" tabindex="-1" aria-labelledby="editDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editDetailModalLabel">Edit Data</h1>
            </div>
            <div id="geteditdetail"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#viewDetailModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>mainkendaraan/getviewdetail",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getviewdetail").html(response);
            });
        });

        $("#editDetailModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>mainkendaraan/geteditdetail",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#geteditdetail").html(response);
            });
        });

        $('#mainkendaraantable').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            pageLength: -1,
            order: [
                [2, 'desc'],
                [1, 'asc']
            ]
        });
    });
</script>