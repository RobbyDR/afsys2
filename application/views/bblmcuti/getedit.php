<form action="<?= base_url() ?>bblmcuti/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col">
                <label for="pegawai" class="col-form-label">pegawai *</label>
                <select class="form-select" name="pegawai" required>
                    <option value="" selected>---pilih---</option>
                    <?php foreach ($pegawai as $p): ?>
                        <option value="<?= $p['nip'] ?>" <?= $p['nip'] == $get['pegawai'] ? 'selected' : '' ?>><?= $p['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="jenis" class="col-form-label">jenis *</label>
                <select class="form-select" name="jenis" required>
                    <option value="">---pilih---</option>
                    <option value="1" <?= $get['jenis'] == 1 ? 'selected' : '' ?>>cuti tahunan</option>
                    <option value="2" <?= $get['jenis'] == 2 ? 'selected' : '' ?>>cuti besar</option>
                    <option value="3" <?= $get['jenis'] == 3 ? 'selected' : '' ?>>cuti sakit</option>
                    <option value="4" <?= $get['jenis'] == 4 ? 'selected' : '' ?>>cuti melahirkan</option>
                    <option value="5" <?= $get['jenis'] == 5 ? 'selected' : '' ?>>cuti karena alasan penting</option>
                    <option value="6" <?= $get['jenis'] == 6 ? 'selected' : '' ?>>cuti diluar tanggungan negara</option>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="tgl1" class="col-form-label">tanggal mulai *</label>
                <input type="date" class="form-control" name="tgl1" value="<?= $get['tgl1'] ?>" required>
            </div>
            <div class="mb-3 col">
                <label for="tgl2" class="col-form-label">tanggal selesai *</label>
                <input type="date" class="form-control" name="tgl2" value="<?= $get['tgl2'] ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col-1">
                <label for="jatah" class="col-form-label">jatah *</label>
                <input type="text" class="form-control" name="jatah" value="<?= $get['jatah'] ?>" required>
            </div>
            <div class="mb-3 col-5">
                <label for="alamat" class="col-form-label">alamat *</label>
                <input type="text" class="form-control" name="alamat" value="<?= $get['alamat'] ?>" required>
            </div>
            <div class="mb-3 col">
                <label for="telp" class="col-form-label">telp *</label>
                <input type="text" class="form-control" name="telp" value="<?= $get['telp'] ?>" required>
            </div>
            <div class="mb-3 col">
                <label for="approval1" class="col-form-label">approval atasan *</label>
                <select class="form-select" name="approval1" required>
                    <option value="0" <?= $get['approval1'] == 0 ? 'selected' : '' ?>>not approved</option>
                    <option value="1" <?= $get['approval1'] == 1 ? 'selected' : '' ?>>approved</option>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="approval2" class="col-form-label">approval pejabat *</label>
                <select class="form-select" name="approval2" required>
                    <option value="0" <?= $get['approval2'] == 0 ? 'selected' : '' ?>>not approved</option>
                    <option value="1" <?= $get['approval2'] == 1 ? 'selected' : '' ?>>approved</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col">
                <label for="pejabat1" class="col-form-label">atasan *</label>
                <select class="form-select" name="pejabat1" required>
                    <option value="" selected>---pilih---</option>
                    <?php foreach ($pegawai as $p): ?>
                        <option value="<?= $p['nip'] ?>" <?= $p['nip'] == $get['pejabat1'] ? 'selected' : '' ?>><?= $p['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="pejabat2" class="col-form-label">pejabat *</label>
                <select class="form-select" name="pejabat2" required>
                    <option value="" selected>---pilih---</option>
                    <?php foreach ($pegawai as $p): ?>
                        <option value="<?= $p['nip'] ?>" <?= $p['nip'] == $get['pejabat2'] ? 'selected' : '' ?>><?= $p['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="ttd1" class="col-form-label">ttd/qr code atasan <small><?= $get['ttd1'] ?></small></label>
                <input type="file" class="form-control" name="ttd1" accept=".jpg, .jpeg, .png">
            </div>
            <div class="mb-3 col">
                <label for="ttd2" class="col-form-label">ttd/qr code pejabat <small><?= $get['ttd2'] ?></small></label>
                <input type="file" class="form-control" name="ttd2" accept=".jpg, .jpeg, .png">
            </div>
        </div>
        <div class="row">
            <div class="mb-3 col">
                <label for="alasan" class="col-form-label">alasan *</label>
                <textarea class="form-control" name="alasan" required><?= $get['alasan'] ?></textarea>
            </div>
            <div class="mb-3 col">
                <label for="keterangan" class="col-form-label">keterangan</label>
                <textarea class="form-control" name="keterangan"><?= $get['keterangan'] ?></textarea>
            </div>
            <div class="mb-3 col">
                <label for="ttdchoose1" class="col-form-label">ttd choose 1 *</label>
                <select class="form-select" name="ttdchoose1" required>
                    <option value="0" <?= $get['ttdchoose1'] == 0 ? 'selected' : '' ?>>ttd</option>
                    <option value="1" <?= $get['ttdchoose1'] == 1 ? 'selected' : '' ?>>qr code upload</option>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="ttdchoose2" class="col-form-label">ttd choose 2 *</label>
                <select class="form-select" name="ttdchoose2" required>
                    <option value="0" <?= $get['ttdchoose2'] == 0 ? 'selected' : '' ?>>ttd</option>
                    <option value="1" <?= $get['ttdchoose2'] == 1 ? 'selected' : '' ?>>qr code upload</option>
                </select>
            </div>
            <div class="mb-3 col-1">
                <label for="status" class="col-form-label">status *</label>
                <select class="form-select" name="status" required>
                    <option value="0" <?= $get['status'] == 0 ? 'selected' : '' ?>>0</option>
                    <option value="1" <?= $get['status'] == 1 ? 'selected' : '' ?>>1</option>
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