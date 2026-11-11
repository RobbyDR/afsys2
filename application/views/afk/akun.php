<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= html_escape($judul) ?></h1>
            </div>
            <div class="col d-flex justify-content-end">
                <a href="<?= base_url('afk') ?>" class="btn btn-sm btn-outline-secondary">Dashboard</a>
            </div>
        </div>

        <div class="container-fluid mt-n5">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Tambah akun</h5>
                    <form action="<?= base_url('afk/akuntambah') ?>" method="POST" class="row g-2 align-items-end">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <div class="col-sm-3">
                            <label class="form-label mb-0">Kode <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control form-control-sm" maxlength="15" pattern="[a-z][a-z0-9]{1,14}" placeholder="mis. dana" title="Huruf kecil/angka, diawali huruf, 2-15 karakter. Tidak bisa diubah setelah dibuat." required>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label mb-0">Nama tampil <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control form-control-sm" maxlength="63" placeholder="mis. DANA" required>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label mb-0">Grup (opsional)</label>
                            <input type="text" name="grup" class="form-control form-control-sm" maxlength="31" placeholder="mis. BNI untuk subtotal">
                        </div>
                        <div class="col-sm-2">
                            <label class="form-label mb-0">Urutan</label>
                            <input type="number" name="urutan" class="form-control form-control-sm" min="0" max="9999" value="<?= (int) $urutanBerikut ?>">
                        </div>
                        <div class="col-sm-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100">Tambah</button>
                        </div>
                    </form>
                    <p class="text-muted small mb-0 mt-2">
                        Akun dengan grup yang sama akan dikumpulkan dan diberi baris subtotal "TOTAL &lt;GRUP&gt;" di dashboard.
                        Akun tidak bisa dihapus, hanya dinonaktifkan, supaya histori transaksi tetap utuh.
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-sm table-hover">
                    <thead class="table-secondary">
                        <tr>
                            <th>Kode</th>
                            <th>Nama tampil</th>
                            <th>Grup</th>
                            <th>Urutan</th>
                            <th class="text-end">Saldo saat ini</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($akun as $a): ?>
                            <?php $fid = 'akunedit' . (int) $a['id']; ?>
                            <tr class="<?= $a['aktif'] ? '' : 'text-muted' ?>">
                                <td><code><?= html_escape($a['kode']) ?></code></td>
                                <td>
                                    <form id="<?= $fid ?>" action="<?= base_url('afk/akunedit/' . (int) $a['id']) ?>" method="POST">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    </form>
                                    <input form="<?= $fid ?>" type="text" name="nama" class="form-control form-control-sm" maxlength="63" value="<?= html_escape($a['nama']) ?>" required>
                                </td>
                                <td><input form="<?= $fid ?>" type="text" name="grup" class="form-control form-control-sm" maxlength="31" value="<?= html_escape($a['grup']) ?>"></td>
                                <td style="width:110px"><input form="<?= $fid ?>" type="number" name="urutan" class="form-control form-control-sm" min="0" max="9999" value="<?= (int) $a['urutan'] ?>"></td>
                                <td class="text-end"><?= number_format($a['total'], 0, ',', '.') ?></td>
                                <td><span class="badge <?= $a['aktif'] ? 'bg-success' : 'bg-secondary' ?>"><?= $a['aktif'] ? 'aktif' : 'nonaktif' ?></span></td>
                                <td class="text-nowrap">
                                    <button form="<?= $fid ?>" type="submit" class="btn btn-sm btn-secondary">Simpan</button>
                                    <form action="<?= base_url('afk/akunaktif/' . (int) $a['id']) ?>" method="POST" class="d-inline"
                                        onsubmit="return confirm('<?= $a['aktif'] ? 'Nonaktifkan akun ini? Histori tetap tersimpan, hanya tidak muncul di form input.' : 'Aktifkan kembali akun ini?' ?>');">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="aktif" value="<?= $a['aktif'] ? '0' : '1' ?>">
                                        <button type="submit" class="btn btn-sm <?= $a['aktif'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"><?= $a['aktif'] ? 'Nonaktifkan' : 'Aktifkan' ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
