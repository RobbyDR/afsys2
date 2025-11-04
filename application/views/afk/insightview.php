<div class="modal-content bg-dark text-light border-secondary">
    <div class="modal-header border-secondary">
        <h5 class="modal-title" id="viewModalLabel">
            <span data-feather="list" class="align-text-bottom"></span>
            Detail <?= $get[0]["tbl_afkcatdeskripsi"] ?? '' ?> <?= $bulan ?>/<?= $tahun ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="table-responsive">
            <table class="table table-dark table-striped table-hover table-sm align-middle mb-0">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Tanggal</th>
                        <?php if ($jenis == 'pemasukan') : ?>
                            <th scope="col">Kategori</th>
                        <?php endif; ?>
                        <th scope="col">Deskripsi</th>
                        <th scope="col" class="text-end">Saku</th>
                        <th scope="col" class="text-end">BNI</th>
                        <th scope="col" class="text-end">BNIK</th>
                        <th scope="col" class="text-end">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sum = 0;
                    $i = 1; ?>
                    <?php foreach ($get as $row): ?>
                        <?php $total = floatval($row['saku']) + floatval($row['bni']) + floatval($row['bnik']); ?>
                        <?php $sum += $total; ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <?php if ($jenis == 'pemasukan') : ?>
                                <td><?= $row['tbl_afkcatdeskripsi'] ?></td>
                            <?php endif; ?>
                            <td><?= $row['deskripsi'] ?></td>
                            <td class="text-end"><?= number_format(floatval($row['saku']), 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format(floatval($row['bni']), 0, ',', '.') ?></td>
                            <td class="text-end"><?= number_format(floatval($row['bnik']), 0, ',', '.') ?></td>
                            <td class="text-end text-info"><strong><?= number_format($total, 0, ',', '.') ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="border-top border-secondary">
                    <tr>
                        <?php $colspan = ($jenis == 'pemasukan') ? 7 : 6; ?>
                        <td colspan="<?= $colspan ?>"><strong>TOTAL</strong></td>
                        <td class="text-end text-warning"><strong><?= number_format(floatval($sum), 0, ',', '.') ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <span data-feather="x-circle" class="align-text-bottom"></span> Close
        </button>
    </div>
</div>