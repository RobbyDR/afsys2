<div class="card shadow-sm border-0">
    <!-- <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Data Jurnal</h6>
        <small class="text-white-50">Klik ikon pensil untuk edit</small>
    </div> -->

    <div class="card-body p-3">
        <div class="table-responsive">
            <table id="afkjurnaltable" class="table table-hover table-bordered align-middle table-sm mb-0" width="100%">
                <thead class="table-light text-center align-middle">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Saku</th>
                        <th>BNI</th>
                        <th>BNIk</th>
                        <th>Saldo</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($get as $row) : ?>
                        <tr title="Last update <?= $row['updated_at'] ?>">
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= $row['tbl_afkcatdeskripsi']; ?> <?= $row['tbl_afkcatio'] ?><?= $row['tbl_afkcatcat'] ?></td>
                            <td><?= $row['deskripsi']; ?></td>
                            <td class="text-end"><?= number_format($row['saku'], 0, ',', '.'); ?></td>
                            <td class="text-end"><?= number_format($row['bni'], 0, ',', '.'); ?></td>
                            <td class="text-end"><?= number_format($row['bnik'], 0, ',', '.'); ?></td>
                            <td class="text-end fw-semibold text-primary"><?= number_format($row['saldo'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="modal"
                                    data-id="<?= $row['id'] ?>"
                                    data-bs-target="#editafkModal"
                                    title="Edit data">
                                    <span data-feather="edit" class="align-text-bottom"></span>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables setup -->
<script>
    $(document).ready(function() {
        $('#afkjurnaltable').DataTable({
            lengthMenu: [
                [25, 50, 100, -1],
                [25, 50, 100, 'Semua']
            ],
            pageLength: -1, // default tampil semua
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    previous: "<i class='bi bi-chevron-left'></i>",
                    next: "<i class='bi bi-chevron-right'></i>"
                },
                zeroRecords: "Tidak ada data ditemukan"
            },
            dom: '<"d-flex justify-content-between align-items-center mb-2"lfB>tip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-sm btn-outline-primary'
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-outline-secondary'
                }
            ],
            order: [
                [0, 'desc']
            ]
        });
    });
</script>
<script>
    feather.replace();
</script>