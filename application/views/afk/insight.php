<?php
// Penyesuaian bulan sebelumnya
$prevYear = $tahun;
$prevMonth = $bulan - 1;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

// Penyesuaian bulan berikutnya
$nextYear = $tahun;
$nextMonth = $bulan + 1;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}
?>

<div class="container-fluid scrollarea py-3">
    <div class="row">
        <div class="col-12 mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">

            <h1 class="h3 text-white mb-0">
                <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                <?= $judul ?>
            </h1>


            <div class="btn-group" role="group" aria-label="Navigasi Bulan">

                <a href="#" id="updaterekap"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="refresh">
                    <span data-feather="refresh-cw"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $prevYear . '/' . $prevMonth) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Bulan sebelumnya">
                    <span data-feather="chevron-left"></span>
                </a>

                <a href="<?= base_url('afk/insight/') ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-light fw-bold"
                    title="Bulan ini">
                    <span data-feather="calendar"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $nextYear . '/' . $nextMonth) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Bulan berikutnya">
                    <span data-feather="chevron-right"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- ====== Kartu Bulanan / Tahunan / 4EVER ====== -->
        <?php
        $summary = [
            ["label" => "Bulan $bulan", "in" => $INbulan, "out" => $OUTbulan, "saldo" => $SALDObulan, "jeniswaktu" => "bulanan"],
            ["label" => "Tahun $tahun", "in" => $INtahun, "out" => $OUTtahun, "saldo" => $SALDOtahun, "jeniswaktu" => "tahunan"],
            ["label" => "4EVER", "in" => $IN4ever, "out" => $OUT4ever, "saldo" => $SALDO4ever, "jeniswaktu" => "4ever"],
        ];
        foreach ($summary as $item): ?>
            <div class="col-md">
                <div class="card bg-dark border-secondary h-100">
                    <div class="card-body">
                        <h5 class="card-title text-light"><?= $item['label'] ?></h5>
                        <table class="table table-dark table-striped table-hover table-sm align-middle mb-0">
                            <thead class="table-secondary text-dark">
                                <tr>
                                    <th>Aspek</th>
                                    <th class="text-end">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="#!" class="text-reset text-decoration-none"
                                            data-bs-toggle="modal" data-bs-target="#viewModal"
                                            data-jenis="pemasukan"
                                            data-tahun="<?= $tahun ?>"
                                            data-bulan="<?= $bulan ?>"
                                            data-jeniswaktu="<?= $item['jeniswaktu'] ?>">
                                            Pemasukan
                                        </a>
                                    </td>
                                    <td class="text-end"><?= number_format($item['in'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td>Pengeluaran (<?= $item['in'] != 0 ? number_format(($item['out'] / $item['in']) * 100, 0) . '%' : '0%' ?>)</td>
                                    <td class="text-end"><?= number_format($item['out'], 0, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>SALDO (<?= $item['in'] != 0 ? number_format(($item['saldo'] / $item['in']) * 100, 0) . '%' : '0%' ?>)</strong></td>
                                    <td class="text-end <?= (float)$item['saldo'] <= 0 ? 'text-danger' : 'text-info' ?>"><strong><?= number_format($item['saldo'], 0, ',', '.') ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ====== Top Pengeluaran ====== -->
    <div class="row g-3 mt-3">
        <?php
        $topdata = [
            ["title" => "Top Pengeluaran RT Bulan $bulan", "data" => $get, "total" => $OUTbulan, "jeniswaktu" => "bulanan"],
            ["title" => "Top Pengeluaran RT Tahun $tahun", "data" => $gettahun, "total" => $OUTtahun, "jeniswaktu" => "tahunan"],
            ["title" => "Top Pengeluaran RT 4EVER", "data" => $get4ever, "total" => $OUT4ever, "jeniswaktu" => "4ever"],
        ];
        foreach ($topdata as $key => $tbl): ?>
            <div class="col-md">
                <div class="card bg-dark border-secondary h-100">
                    <div class="card-body">
                        <h5 class="card-title text-light">
                            <?= $tbl['title'] ?>
                            <?php if ($tbl['jeniswaktu'] === '4ever'): ?>
                                <span class="badge bg-secondary">Since 20231209</span>
                            <?php endif; ?>
                        </h5>
                        <table class="table table-dark table-striped table-hover table-sm align-middle mb-0">
                            <thead class="table-secondary text-dark">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sum = 0;
                                foreach ($tbl['data'] as $row): $sum += $row['nilai']; ?>
                                    <tr>
                                        <td>
                                            <a href="#!" class="text-reset text-decoration-none"
                                                data-bs-toggle="modal" data-bs-target="#viewModal"
                                                data-jenis="<?= $row['jenis'] ?>"
                                                data-tahun="<?= $tahun ?>"
                                                data-bulan="<?= $bulan ?>"
                                                data-jeniswaktu="<?= $tbl['jeniswaktu'] ?>">
                                                <?= $row['deskripsi'] ?> (<?= number_format(($row['nilai'] / $tbl['total']) * 100, 0, ',', '.') ?>%)
                                            </a>
                                        </td>
                                        <td class="text-end"><?= number_format($row['nilai'], 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="border-top border-secondary">
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-end"><strong><?= number_format($sum, 0, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ====== Modal Bootstrap 5 ====== -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content bg-dark text-light border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="viewModalLabel">
                    <span data-feather="list" class="align-text-bottom"></span> Detail
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="isiViewModal">
                <div class="text-center text-secondary">Memuat data...</div>
            </div>
        </div>
    </div>
</div>

<!-- ====== Script Modal ====== -->
<script>
    $(document).ready(function() {
        // Event saat modal tampil
        $('#viewModal').on('shown.bs.modal', function(e) {
            let btn = $(e.relatedTarget);
            let jenis = btn.data('jenis');
            let tahun = btn.data('tahun');
            let bulan = btn.data('bulan');
            let jeniswaktu = btn.data('jeniswaktu');

            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            $("#isiViewModal").html('<div class="text-center text-secondary py-3"><span data-feather="loader" class="spin"></span> Memuat...</div>');
            feather.replace();

            $.post("<?= base_url('afk/insightview') ?>", {
                jenis: jenis,
                tahun: tahun,
                bulan: bulan,
                jeniswaktu: jeniswaktu
            }).done(function(response) {
                $("#isiViewModal").html(response);
                feather.replace();
            }).fail(function() {
                $("#isiViewModal").html('<div class="alert alert-danger">Gagal memuat data.</div>');
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#updaterekap").click(() => window.location.href = '<?= base_url('afk/afkupdaterekap') ?>');
    });
</script>