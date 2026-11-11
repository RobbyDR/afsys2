<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= html_escape($judul) ?></h1>
            </div>
            <div class="col d-flex justify-content-end align-items-center gap-2">
                <label for="tanggalPotret" class="col-form-label-sm mb-0">Posisi per tanggal</label>
                <input type="date" id="tanggalPotret" class="form-control form-control-sm w-auto" value="<?= html_escape($tanggal) ?>">
                <button type="button" id="lihatPotret" class="btn btn-sm btn-primary">Lihat</button>
                <button type="button" id="akhirBulanLalu" class="btn btn-sm btn-outline-secondary" title="Akhir bulan sebelum tanggal ini">Akhir bulan lalu</button>
                <a href="<?= base_url('afk') ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
            </div>
        </div>

        <div class="container-fluid mt-n5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Posisi keuangan pada <?= html_escape(date('d-m-Y', strtotime($tanggal))) ?></h5>
                    <p class="card-text text-muted mb-2">
                        Selisih dibandingkan posisi akhir bulan sebelumnya (<?= html_escape(date('d-m-Y', strtotime($akhirBulanLalu))) ?>).
                    </p>
                    <dl class="row mb-0">
                        <?php foreach ($akunsaldo as $i => $a): ?>
                            <dt class="col-sm-4"><?= html_escape($a['nama']) ?></dt>
                            <dd class="col-sm-8"><?= number_format(floatval($a['saldo']), 0, ',', '.') ?> | <?= $a['state'] ?><?= number_format(floatval($a['delta']), 0, ',', '.') ?></dd>
                            <?php
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
                    <hr>
                    <p class="card-text mb-0">
                        <strong>Bulan <?= html_escape(date('m-Y', strtotime($tanggal))) ?> (<?= html_escape(date('d', strtotime($awalBulan))) ?> s/d <?= html_escape(date('d', strtotime($tanggal))) ?>):</strong>
                        pemasukan Rp <?= number_format($pemasukan, 0, ',', '.') ?>,
                        pengeluaran Rp <?= number_format($pengeluaran, 0, ',', '.') ?>
                        <?php if ($persenSisa !== null): ?>
                            <span class="badge bg-secondary" title="Sisa = (pemasukan - pengeluaran) / pemasukan"><?= number_format($persenSisa, 1, ',', '.') ?>% sisa</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">belum ada pemasukan</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const go = (tgl) => {
            if (!tgl) return;
            window.location.href = '<?= base_url('afk/potret') ?>/' + tgl;
        };
        $("#lihatPotret").on("click", () => go($("#tanggalPotret").val()));
        $("#tanggalPotret").on("change", () => go($("#tanggalPotret").val()));
        $("#akhirBulanLalu").on("click", () => go('<?= html_escape($akhirBulanLalu) ?>'));
    });
</script>
