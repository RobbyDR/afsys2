<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= $judul ?></h1>
            </div>
            <div class="col d-flex justify-content-end">
                <!-- <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah</button> -->
                <!-- <button type="button" class="btn btn-primary btn-sm disabled">Laporan Bulanan</button> -->

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" id="insight" class="btn btn-sm btn-warning" title="Insight">Insight</button>
                    <button type="button" id="jurnalkeuangan" class="btn btn-sm btn-primary" title="Jurnal Keuangan">Jurnal Keuangan</button>
                    <button type="button" id="potret" class="btn btn-sm btn-info" title="Posisi keuangan pada tanggal tertentu">Potret</button>
                    <div class="btn-group" role="group">
                        <a href="#" id="grafik"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-primary"
                            title="goto grafik keuangan">
                            <span data-feather="pie-chart"></span>
                        </a>
                        <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- <i data-feather="refresh-ccw"></i> -->
                            <span data-feather="refresh-ccw" class="align-text-bottom"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#" id="updaterekap">Update Rekap Keseluruhan</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('afk/akun') ?>">Kelola Akun</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="card col">
                <div class="card-body">
                    <h5 class="card-title">Wilujeng Sumping</h5>
                    <p class="card-text">
                    <figure>
                        <blockquote class="blockquote">
                            مَنْ يَزْرَعْ يَحْصُدْ <br>
                            “Barang siapa yang menanam ia akan memetik hasilnya”.
                        </blockquote>
                        <figcaption class="blockquote-footer">
                            Pepatah <cite title="Source Title">lama</cite>
                        </figcaption>
                    </figure>
                    </p>
                    <hr>
                    <p class="card-text mb-0">
                        <strong>Pengeluaran Hari Ini:</strong>
                        Rp <?= number_format($pengeluaranHariIni, 0, ',', '.') ?>
                        <?php if ($persenPengeluaranHariIni !== null): ?>
                            <span class="badge <?= ($persenPengeluaranHariIni > 100) ? 'bg-danger' : 'bg-secondary' ?>">
                                <?= number_format($persenPengeluaranHariIni, 1, ',', '.') ?>% dari pemasukan hari ini
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">belum ada pemasukan hari ini</span>
                        <?php endif; ?>
                    </p>
                    <hr>
                    <?php
                    // warna baterai: hijau = di atas/pas jalur, kuning = sedikit di bawah (<= 10 poin), merah = jauh di bawah
                    $warnaBaterai = 'bg-secondary';
                    if ($persenSisaBulanIni !== null) {
                        $selisihPoin = $persenSisaBulanIni - $persenProporsional;
                        $warnaBaterai = ($selisihPoin >= 0) ? 'bg-success' : (($selisihPoin >= -10) ? 'bg-warning' : 'bg-danger');
                    }
                    $lebarBaterai = ($persenSisaBulanIni !== null) ? max(0, min(100, $persenSisaBulanIni)) : 0;
                    ?>
                    <p class="card-text mb-1">
                        <strong>Baterai bulan ini:</strong>
                        <?php if ($persenSisaBulanIni !== null): ?>
                            <?= number_format($persenSisaBulanIni, 1, ',', '.') ?>%
                            <small class="text-muted">(Rp <?= number_format($sisaBulanIni, 0, ',', '.') ?> dari pemasukan Rp <?= number_format($pemasukanBulanIni, 0, ',', '.') ?>)</small>
                        <?php else: ?>
                            <span class="badge bg-secondary">belum ada pemasukan bulan ini</span>
                        <?php endif; ?>
                    </p>
                    <div class="progress position-relative" style="height: 18px;" title="Garis hitam = sisa ideal hari ini menurut kalender">
                        <div class="progress-bar <?= $warnaBaterai ?>" role="progressbar" style="width: <?= number_format($lebarBaterai, 2, '.', '') ?>%;"></div>
                        <div class="position-absolute top-0 bottom-0" style="left: <?= number_format(max(0, min(100, $persenProporsional)), 2, '.', '') ?>%; width: 2px; background: #000;"></div>
                    </div>
                    <p class="card-text small text-muted mt-1 mb-0">
                        Proporsional (tgl <?= (int) $tglHariIni ?> dari <?= (int) $hariDalamBulan ?>): <strong><?= number_format($persenProporsional, 1, ',', '.') ?>%</strong>
                        <?php if ($targetSisaHariIni !== null): ?>
                            &middot; sisa seharusnya <strong>Rp <?= number_format($targetSisaHariIni, 0, ',', '.') ?></strong>
                            &middot;
                            <?php if ($selisihTarget >= 0): ?>
                                <span class="text-success">di atas jalur Rp <?= number_format($selisihTarget, 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-danger">di bawah jalur Rp <?= number_format(abs($selisihTarget), 0, ',', '.') ?> (rem dulu)</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="card col">
                <div class="card-body">
                    <h5 class="card-title">Saldo Uang Hari Ini</h5>
                    <dl class="row">
                        <?php foreach ($akunsaldo as $i => $a): ?>
                            <dt class="col-sm-4"><?= html_escape($a['nama']) ?></dt>
                            <dd class="col-sm-8"><?= number_format(floatval($a['saldo']), 0, ',', '.') ?> | <?= $a['state'] ?><?= number_format(floatval($a['delta']), 0, ',', '.') ?></dd>
                            <?php
                            // subtotal grup muncul setelah akun terakhir dalam grup yang sama
                            $grupBerikut = $akunsaldo[$i + 1]['grup'] ?? null;
                            if ($a['grup'] !== '' && $grupBerikut !== $a['grup']):
                                $g = $grupTotal[$a['grup']];
                            ?>
                                <dt class="col-sm-4"><strong>TOTAL <?= html_escape(strtoupper($a['grup'])) ?></strong></dt>
                                <dd class="col-sm-8"><strong><?= number_format(floatval($g['saldo']), 0, ',', '.') ?> | <?= $g['state'] ?><?= number_format(floatval($g['delta']), 0, ',', '.') ?></strong></dd>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <dt class="col-sm-4"><strong>SUPER TOTAL</strong></dt>
                        <dd class="col-sm-8"><strong><?= number_format(floatval($superSaldo), 0, ',', '.') ?> | <?= $superState ?><?= number_format(floatval($superDelta), 0, ',', '.') ?></strong></dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="input-group mb-3 align-items-center">
                            <h5 class="card-title me-3 mt-2">Rekap per tanggal</h5>
                            <select class="form-select me-2" id="pilihbulan">
                                <option value="">---pilih---</option>
                                <?php
                                $bulan = [
                                    1 => "Januari",
                                    2 => "Februari",
                                    3 => "Maret",
                                    4 => "April",
                                    5 => "Mei",
                                    6 => "Juni",
                                    7 => "Juli",
                                    8 => "Agustus",
                                    9 => "September",
                                    10 => "Oktober",
                                    11 => "November",
                                    12 => "Desember"
                                ];
                                foreach ($bulan as $num => $nama) : ?>
                                    <option value="<?= $num ?>" <?= ($blnskr2 == $num) ? 'selected' : '' ?>><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select class="form-select" id="pilihtahun">
                                <option value="">---pilih---</option>
                                <?php for ($thn = $thnterlama; $thn <= $thnterbaru; $thn++): ?>
                                    <option value="<?= $thn ?>" <?= ($thn == $thnskr) ? 'selected' : '' ?>><?= $thn ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="afkgetrekaptanggal"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekap per bulan -->
        <div class="card mt-3">
            <div class="card-header">
                <div class="input-group mb-3 align-items-center">
                    <h5 class="card-title me-3 mt-2">Rekap per bulan</h5>
                    <select class="form-select" id="pilihtahun2">
                        <option value="">---pilih---</option>
                        <?php for ($thn = $thnterlama; $thn <= $thnterbaru; $thn++): ?>
                            <option value="<?= $thn ?>" <?= ($thn == $thnskr) ? 'selected' : '' ?>><?= $thn ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="afkgetrekapbulan"></div>
            </div>
        </div>

        <!-- Rekap per tahun -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mt-2">Rekap per tahun</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover table-sm align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cat</th>
                                <th>Deskripsi</th>
                                <?php for ($tahun = $thnterlama; $tahun <= $thnterbaru; $tahun++): ?>
                                    <th class="text-end"><?= $tahun ?></th>
                                <?php endfor; ?>
                                <th class="text-end">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $datatotalrow = []; ?>
                            <?php for ($tahun = $thnterlama; $tahun <= $thnterbaru; $tahun++):
                                $tglkey = (new DateTime("$tahun-01-01"))->format('Y-m-d');
                                $datatotalrow[$tglkey] = 0;
                            endfor; ?>

                            <?php foreach ($datacat as $row): ?>
                                <tr>
                                    <th><?= $row['id'] ?></th>
                                    <td><a href="<?= base_url('afk/afkrinci/' . $row['id']) ?>"><?= $row['cat'] ?></a></td>
                                    <td><?= $row['deskripsi'] ?></td>
                                    <?php $datatotalcol = 0; ?>
                                    <?php for ($tahun = $thnterlama; $tahun <= $thnterbaru; $tahun++):
                                        $tglkey = (new DateTime("$tahun-01-01"))->format('Y-m-d'); ?>
                                        <td class="text-end">
                                            <?= number_format(floatval($datanilai[$row['id']][$tglkey]['nilai']), 0, ',', '.') ?>
                                        </td>
                                        <?php
                                        $val = $datanilai[$row['id']][$tglkey]['nilai'];
                                        $datatotalrow[$tglkey] += $val;
                                        $datatotalcol += $val;
                                        ?>
                                    <?php endfor; ?>
                                    <td class="text-end"><?= number_format(floatval($datatotalcol), 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <?php $supertotal = 0; ?>
                                <?php foreach ($datatotalrow as $tglkey => $nilai): ?>
                                    <th class="text-end"><?= number_format(floatval($nilai), 0, ',', '.') ?></th>
                                    <?php $supertotal += $nilai; ?>
                                <?php endforeach; ?>
                                <th class="text-end"><?= number_format(floatval($supertotal), 0, ',', '.') ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        $("#jurnalkeuangan").on("click", function() {
            window.location.href = '<?= base_url('afk/afkjurnal') ?>';
        });
        $("#insight").on("click", function() {
            window.location.href = '<?= base_url('afk/insight') ?>';
        });
        $("#grafik").on("click", function() {
            window.location.href = '<?= base_url('afk/grafik') ?>';
        });
        $("#updaterekap").click(() => window.location.href = '<?= base_url('afk/afkupdaterekap') ?>');
        $("#potret").click(() => window.location.href = '<?= base_url('afk/potret') ?>');

        $("#pilihbulan, #pilihtahun").on("change", afkgetrekaptanggal);
        $("#pilihtahun2").on("change", afkgetrekapbulan);

        afkgetrekaptanggal();
        afkgetrekapbulan();
    });

    function afkgetrekaptanggal() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "<?= base_url(); ?>afk/afkgetrekaptanggal",
                method: "POST",
                data: {
                    pilihbulan: $("#pilihbulan").val(),
                    pilihtahun: $("#pilihtahun").val(),
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    $("#afkgetrekaptanggal").html(response);
                    resolve(response);
                },
                error: function(_, __, error) {
                    reject(error);
                }
            });
        });
    }

    function afkgetrekapbulan() {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "<?= base_url(); ?>afk/afkgetrekapbulan",
                method: "POST",
                data: {
                    pilihtahun: $("#pilihtahun2").val(),
                    '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    $("#afkgetrekapbulan").html(response);
                    resolve(response);
                },
                error: function(_, __, error) {
                    reject(error);
                }
            });
        });
    }
</script>