<?php
// ===============================
// SINGLE SOURCE OF TRUTH: TANGGAL
// ===============================
$baseDate = new DateTime($tanggal_full ?? date('Y-m-d'));

// Navigasi HARI
$prevDay = (clone $baseDate)->modify('-1 day');
$nextDay = (clone $baseDate)->modify('+1 day');

// Navigasi BULAN
$prevMonth = (clone $baseDate)->modify('first day of last month');
$nextMonth = (clone $baseDate)->modify('first day of next month');

// Navigasi TAHUN (opsional)
$prevYear = (clone $baseDate)->modify('first day of january last year');
$nextYear = (clone $baseDate)->modify('first day of january next year');

// ===============================
// DERIVASI UNTUK TAMPILAN
// ===============================
$tahun   = (int)$baseDate->format('Y');
$bulan   = (int)$baseDate->format('m');
$tanggal = (int)$baseDate->format('d');
?>


<div class="container-fluid scrollarea py-3">
    <div class="row">
        <div class="col-12 mb-3 border-bottom pb-2 d-flex justify-content-between align-items-center">

            <h1 class="h3 text-white mb-0">
                <span data-feather="bar-chart-2" class="align-text-bottom"></span>
                <?= $judul ?>
            </h1>


            <div class="btn-group" role="group" aria-label="Navigasi Bulan">
                <a href="#" id="dashboard"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto dashboard keuangan">
                    Dashboard
                </a>
                <a href="#" id="jurnal"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto jurnal keuangan">
                    Jurnal
                </a>
                <a href="#" id="grafik"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="goto grafik keuangan">
                    <span data-feather="pie-chart"></span>
                </a>
                <a href="#" id="updaterekap"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-warning"
                    title="refresh">
                    <span data-feather="refresh-cw"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $prevMonth->format('Y-m-d')) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Bulan sebelumnya">
                    <span data-feather="chevrons-left"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $prevDay->format('Y-m-d')) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Hari sebelumnya">
                    <span data-feather="chevron-left"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . date('Y-m-d')) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-secondary fw-bold"
                    title="Sekarang">
                    <span data-feather="calendar"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $nextDay->format('Y-m-d')) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Hari berikutnya">
                    <span data-feather="chevron-right"></span>
                </a>

                <a href="<?= base_url('afk/insight/' . $nextMonth->format('Y-m-d')) ?>"
                    rel="noopener noreferrer"
                    class="btn btn-sm btn-outline-secondary"
                    title="Bulan berikutnya">
                    <span data-feather="chevrons-right"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- ====== Kartu Bulanan / Tahunan / 4EVER ====== -->
        <?php
        $summary = [
            ["label" => "Tanggal $tanggal", "in" => $INhari, "out" => $OUThari, "saldo" => $SALDOhari, "jeniswaktu" => "harian"],
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
                                            data-tanggal="<?= $tanggal ?>"
                                            data-jeniswaktu="<?= $item['jeniswaktu'] ?>">
                                            Pemasukan
                                        </a>
                                    </td>
                                    <td class="text-end"><?= number_format($item['in'], 0, ',', '.') ?></td>
                                </tr>
                                <?php
                                $outPercent   = ($item['in'] != 0) ? ($item['out'] / $item['in']) * 100 : 0;
                                $saldoPercent = ($item['in'] != 0) ? ($item['saldo'] / $item['in']) * 100 : 0;
                                ?>

                                <tr>
                                    <td>
                                        Pengeluaran
                                        <span class="badge bg-secondary ms-1"
                                            style="font-size:0.75em;">
                                            <?= number_format($outPercent, 0, ',', '.') ?>%
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?= number_format($item['out'], 0, ',', '.') ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <strong>
                                            SALDO
                                            <span class="badge ms-1 <?= $saldoPercent < 0 ? 'bg-danger' : 'bg-info' ?>"
                                                style="font-size:0.75em;">
                                                <?= number_format($saldoPercent, 0, ',', '.') ?>%
                                            </span>
                                        </strong>
                                    </td>
                                    <td class="text-end <?= (float)$item['saldo'] <= 0 ? 'text-danger' : 'text-info' ?>">
                                        <strong><?= number_format($item['saldo'], 0, ',', '.') ?></strong>
                                    </td>
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
            ["title" => "Top Pengeluaran RT Tanggal $tanggal", "data" => $gethari, "total" => $OUThari, "jeniswaktu" => "harian"],
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
                                <?php
                                // Hitung total (dipakai untuk persentase & footer)
                                $sum = 0;
                                foreach ($tbl['data'] as $r) {
                                    if (isset($r['nilai']) && is_numeric($r['nilai'])) {
                                        $sum += (float) $r['nilai'];
                                    }
                                }
                                ?>

                                <?php foreach ($tbl['data'] as $row): ?>
                                    <tr>
                                        <td>
                                            <a href="#!" class="text-reset text-decoration-none"
                                                data-bs-toggle="modal" data-bs-target="#viewModal"
                                                data-jenis="<?= $row['jenis'] ?>"
                                                data-tahun="<?= $tahun ?>"
                                                data-bulan="<?= $bulan ?>"
                                                data-tanggal="<?= $tanggal ?>"
                                                data-jeniswaktu="<?= $tbl['jeniswaktu'] ?>">


                                                <?= $row['deskripsi'] ?><span class="badge rounded-pill bg-danger ms-1 align-middle"
                                                    style="font-size: 0.75em;">
                                                    <?= $sum != 0
                                                        ? strtolower(number_format(($row['nilai'] / $sum) * 100, 0, ',', '.')) . '%'
                                                        : '0%'
                                                    ?>
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-end"><?= number_format($row['nilai'], 0, ',', '.') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            <tfoot class="border-top border-secondary">
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td class="text-end">
                                        <strong><?= number_format($sum, 0, ',', '.') ?></strong>
                                    </td>
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
            let tanggal = btn.data('tanggal');
            let jeniswaktu = btn.data('jeniswaktu');
            // console.log(tanggal);
            // console.log(bulan);
            // console.log(tahun);
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
                tanggal: tanggal,
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
        $("#jurnal").on("click", function() {
            window.location.href = '<?= base_url('afk/afkjurnal') ?>';
        });
        $("#dashboard").on("click", function() {
            window.location.href = '<?= base_url('afk') ?>';
        });
        $("#grafik").on("click", function() {
            window.location.href = '<?= base_url('afk/grafik') ?>';
        });
    });
</script>