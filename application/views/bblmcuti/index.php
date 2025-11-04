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
            <table class="table table-striped table-sm" id="bblmcutitable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">jenis</th>
                        <th scope="col">alasan</th>
                        <th scope="col">tanggal</th>
                        <th scope="col">keterangan</th>
                        <th scope="col">form</th>
                        <th scope="col">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get as $row): ?>
                        <!-- class="text-decoration-line-through" -->
                        <tr class="<?= $row['status'] == "2" ? 'fw-lighter' : ''; ?>">
                            <td><?= $row['id'] ?></td>
                            <?php
                            $jenis = null;
                            if ($row['jenis'] == 1) {
                                $jenis = 'cuti tahunan';
                            } else if ($row['jenis'] == 2) {
                                $jenis = 'cuti besar';
                            } else if ($row['jenis'] == 3) {
                                $jenis = 'cuti sakit';
                            } else if ($row['jenis'] == 4) {
                                $jenis = 'cuti melahirkan';
                            } else if ($row['jenis'] == 5) {
                                $jenis = 'cuti karena alasan penting';
                            } else if ($row['jenis'] == 6) {
                                $jenis = 'cuti diluar tanggungan negara';
                            }
                            ?>
                            <td><?= $jenis ?></td>
                            <td class="text-break"><?= $row['alasan'] ?></td>
                            <td><?= $row['tgl1'] ?>~<?= $row['tgl2'] ?></td>
                            <td><?= $row['keterangan'] ?></td>
                            <td><a href="<?= base_url('bblmcuti/generate/' . $row['id']) ?>" target="_blank">Generate form</a></td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#viewModal">View</button></li>
                                        <li><button class="dropdown-item" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button></li>
                                        <li><a class="dropdown-item" href="<?= base_url('bblmcuti/generate/' . $row['id']) ?>" target="_blank">Generate</a></li>
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
            <form action="<?= base_url() ?>bblmcuti/tambah" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="pegawai" class="col-form-label">pegawai *</label>
                            <select class="form-select" name="pegawai" required>
                                <option value="" selected>---pilih---</option>
                                <?php foreach ($pegawai as $p): ?>
                                    <option value="<?= $p['nip'] ?>"><?= $p['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="jenis" class="col-form-label">jenis *</label>
                            <select class="form-select" name="jenis" required>
                                <option value="">---pilih---</option>
                                <option value="1" selected>cuti tahunan</option>
                                <option value="2">cuti besar</option>
                                <option value="3">cuti sakit</option>
                                <option value="4">cuti melahirkan</option>
                                <option value="5">cuti karena alasan penting</option>
                                <option value="6">cuti diluar tanggungan negara</option>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="tgl1" class="col-form-label">tanggal mulai *</label>
                            <input type="date" class="form-control" name="tgl1" value=<?= date('Y-m-d') ?> required>
                        </div>
                        <div class="mb-3 col">
                            <label for="tgl2" class="col-form-label">tanggal selesai *</label>
                            <input type="date" class="form-control" name="tgl2" value=<?= date('Y-m-d') ?> required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-1">
                            <label for="jatah" class="col-form-label">jatah *</label>
                            <input type="text" class="form-control" name="jatah" required>
                        </div>
                        <div class="mb-3 col-5">
                            <label for="alamat" class="col-form-label">alamat *</label>
                            <input type="text" class="form-control" name="alamat" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="telp" class="col-form-label">telp *</label>
                            <input type="text" class="form-control" name="telp" required>
                        </div>
                        <div class="mb-3 col">
                            <label for="approval1" class="col-form-label">approval atasan *</label>
                            <select class="form-select" name="approval1" required>
                                <option value="0">not approved</option>
                                <option value="1" selected>approved</option>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="approval2" class="col-form-label">approval pejabat *</label>
                            <select class="form-select" name="approval2" required>
                                <option value="0">not approved</option>
                                <option value="1" selected>approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="pejabat1" class="col-form-label">atasan *</label>
                            <select class="form-select" name="pejabat1" required>
                                <option value="" selected>---pilih---</option>
                                <?php foreach ($pegawai as $p): ?>
                                    <option value="<?= $p['nip'] ?>"><?= $p['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="pejabat2" class="col-form-label">pejabat *</label>
                            <select class="form-select" name="pejabat2" required>
                                <option value="" selected>---pilih---</option>
                                <?php foreach ($pegawai as $p): ?>
                                    <option value="<?= $p['nip'] ?>"><?= $p['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="ttd1" class="col-form-label">ttd/qr code atasan</label>
                            <input type="file" class="form-control" name="ttd1" accept=".jpg, .jpeg, .png">
                        </div>
                        <div class="mb-3 col">
                            <label for="ttd2" class="col-form-label">ttd/qr code pejabat</label>
                            <input type="file" class="form-control" name="ttd2" accept=".jpg, .jpeg, .png">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="alasan" class="col-form-label">alasan *</label>
                            <textarea class="form-control" name="alasan" required></textarea>
                        </div>
                        <div class="mb-3 col">
                            <label for="keterangan" class="col-form-label">keterangan</label>
                            <textarea class="form-control" name="keterangan"></textarea>
                        </div>
                        <div class="mb-3 col">
                            <label for="ttdchoose1" class="col-form-label">ttd choose 1 *</label>
                            <select class="form-select" name="ttdchoose1" required>
                                <option value="0">ttd</option>
                                <option value="1" selected>qr code upload</option>
                            </select>
                        </div>
                        <div class="mb-3 col">
                            <label for="ttdchoose2" class="col-form-label">ttd choose 2 *</label>
                            <select class="form-select" name="ttdchoose2" required>
                                <option value="0">ttd</option>
                                <option value="1" selected>qr code upload</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-form-label">*) wajib diisi</div>
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
                url: "<?= base_url() ?>bblmcuti/getview",
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
                url: "<?= base_url() ?>bblmcuti/getedit",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#getedit").html(response);
            });
        });

        $('#bblmcutitable').DataTable({
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