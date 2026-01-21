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
                            <li><a class="dropdown-item disabled" href="#" id="updaterekap2">Update Rekap Bulanan</a></li>
                            <li><a class="dropdown-item disabled" href="#" id="updaterekap3">Update Rekap Lampau</a></li>
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
                </div>
            </div>

            <div class="card col">
                <div class="card-body">
                    <h5 class="card-title">Saldo Uang Hari Ini</h5>
                    <dl class="row">
                        <dt class="col-sm-4">BNI</dt>
                        <dd class="col-sm-8"><?= number_format(floatval($saldoskrbni), 0, ',', '.') ?> | <?= $bnistate ?><?= number_format(floatval($deltabni), 0, ',', '.') ?></dd>
                        <dt class="col-sm-4">BNIk</dt>
                        <dd class="col-sm-8"><?= number_format(floatval($saldoskrbnik), 0, ',', '.') ?> | <?= $bnikstate ?><?= number_format(floatval($deltabnik), 0, ',', '.') ?></dd>
                        <dt class="col-sm-4"><strong>TOTAL BNI</strong></dt>
                        <dd class="col-sm-8"><strong><?= number_format(floatval($saldoskrbnik + $saldoskrbni), 0, ',', '.') ?> | <?= $bnibnikstate ?><?= number_format(floatval($deltabnibnik), 0, ',', '.') ?></strong></dd>
                        <dt class="col-sm-4">Saku</dt>
                        <dd class="col-sm-8"><?= number_format(floatval($saldoskrsaku), 0, ',', '.') ?> | <?= $sakustate ?><?= number_format(floatval($deltasaku), 0, ',', '.') ?></dd>
                        <dt class="col-sm-4"><strong>SUPER TOTAL</strong></dt>
                        <dd class="col-sm-8"><strong><?= number_format(floatval($saldoskrbni + $saldoskrbnik + $saldoskrsaku), 0, ',', '.') ?> | <?= $bnibniksakustate ?><?= number_format(floatval($deltabnibniksaku), 0, ',', '.') ?></strong></dd>
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
        $("#updaterekap2").click(() => window.location.href = '<?= base_url('afk/afkupdaterekapbulanan') ?>');
        $("#updaterekap3").click(() => window.location.href = '<?= base_url('afk/afkupdaterekaplampau') ?>');

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