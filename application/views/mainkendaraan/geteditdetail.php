<form action="<?= base_url() ?>mainkendaraan/editdetail" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">

            <div class="mb-3 col">
                <label for="mainid" class="col-form-label">mainid</label>
                <select class="form-select" name="mainid" required>
                    <option value="">---pilih---</option>
                    <?php foreach ($getmain as $row): ?>
                        <option value="<?= $row['id'] ?>" <?= $row['id'] == $get['mainid'] ? 'selected' : ''; ?>><?= $row['nama'] ?> <?= $row['tgl1'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 col">
                <label for="nama" class="col-form-label">nama</label>
                <input type="text" class="form-control" name="nama" value="<?= $get['nama'] ?>" required>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="jumlah" class="col-form-label">jumlah</label>
                    <input type="text" class="form-control" name="jumlah" value="<?= $get['jumlah'] ?>" required>
                </div>
                <div class="mb-3 col">
                    <label for="harga" class="col-form-label">harga</label>
                    <input type="text" class="form-control" name="harga" value="<?= $get['harga'] ?>" required>
                </div>

                <div class="mb-3 col">
                    <label for="status" class="col-form-label">status</label>
                    <select class="form-select" name="status" required>
                        <option value="">---pilih---</option>
                        <?php for ($i = 0; $i <= 2; $i++): ?>
                            <option <?= $i == $get['status'] ? 'selected' : ''; ?> value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="mb-3 col">
                <label for="deskripsi" class="col-form-label">deskripsi</label>
                <textarea class="form-control" name="deskripsi"><?= $get['deskripsi'] ?></textarea>
            </div>
            <div class="mb-3 col">
                <label for="keterangan" class="col-form-label">keterangan</label>
                <textarea class="form-control" name="keterangan"><?= $get['keterangan'] ?></textarea>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>