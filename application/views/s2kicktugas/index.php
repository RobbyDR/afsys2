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

        <?php
        // Set timezone
        date_default_timezone_set('Asia/Jakarta');

        // Tanggal awal
        $startDate = new DateTime('2024-09-17');
        // Tanggal hari ini
        $endDate = new DateTime();

        // Menghitung selisih antara kedua tanggal
        $interval = $startDate->diff($endDate);

        // Menghitung jumlah minggu
        $weeks = floor($interval->days / 7);

        // Menampilkan hasil
        echo "Minggu ke- $weeks.";
        ?>
        Progress: <?= $progress ?>/<?= $progresstot ?> =
        <?php
        if ($progresstot > 0) {
            $percent = round(($progress / $progresstot) * 100);
            echo $percent . '%';
        } else {
            echo 'Total progres tidak boleh nol.';
        }
        ?>

        <div class="table-responsive small">
            <table class="table table-striped table-sm" id="s2kicktugastable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">matkul</th>
                        <th scope="col">nama</th>
                        <th scope="col">deskripsi</th>
                        <th scope="col">range tanggal</th>
                        <th scope="col">selesai</th>
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
                            <td><?= $row['namamatkul'] ?></td>
                            <td><?= $row['nama'] ?></td>
                            <td><?= $row['deskripsi'] ?></td>
                            <td><?= $row['tanggal'] ?> s.d. <?= $row['deadline'] ?></td>
                            <td><?= ($row['selesai'] == '0000-00-00') ? "" : $row['selesai'] ?></td>
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
            <form action="<?= base_url() ?>s2kicktugas/tambah" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="matkulid" class="col-form-label">matkulid</label>
                            <select class="form-select" name="matkulid">
                                <option>---pilih---</option>
                                <?php foreach ($getmatkulid as $row): ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3 col">
                            <label for="nama" class="col-form-label">nama</label>
                            <input type="text" class="form-control" name="nama">
                        </div>
                        <div class="mb-3 col">
                            <label for="tanggal" class="col-form-label">tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value=<?= date('Y-m-d') ?>>
                        </div>
                        <div class="mb-3 col">
                            <label for="deadline" class="col-form-label">deadline</label>
                            <input type="date" class="form-control" name="deadline" value=<?= date('Y-m-d', strtotime(' +7 days')) ?>>
                        </div>
                        <div class="mb-3 col">
                            <label for="selesai" class="col-form-label">selesai</label>
                            <input type="date" class="form-control" name="selesai" ?>>
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
                url: "<?= base_url() ?>s2kicktugas/getview",
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
                url: "<?= base_url() ?>s2kicktugas/getedit",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getedit").html(response);
            });
        });

        $('#s2kicktugastable').DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            pageLength: -1,
            order: [
                [7, 'asc'],
                [5, 'desc']
            ]
        });
    });
</script>