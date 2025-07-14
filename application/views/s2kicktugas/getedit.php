<form action="<?= base_url() ?>s2kicktugas/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col">
                <label for="matkulid" class="col-form-label">matkulid</label>
                <select class="form-select" name="matkulid">
                    <option>---pilih---</option>
                    <?php foreach ($getmatkulid as $row): ?>
                        <option <?= $row['id'] == $get['matkulid'] ? 'selected' : ''; ?> value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 col">
                <label for="nama" class="col-form-label">nama</label>
                <input type="text" class="form-control" name="nama" value="<?= $get['nama'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="tanggal" class="col-form-label">tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?= $get['tanggal'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="deadline" class="col-form-label">deadline</label>
                <input type="date" class="form-control" name="deadline" value="<?= $get['deadline'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="selesai" class="col-form-label">selesai</label>
                <input type="date" class="form-control" name="selesai" value="<?= $get['selesai'] ?>">
            </div>

            <div class="mb-3 col">
                <label for="status" class="col-form-label">status</label>
                <select class="form-select" name="status">
                    <option>---pilih---</option>
                    <?php for ($i = 0; $i <= 2; $i++): ?>
                        <option <?= $i == $get['status'] ? 'selected' : ''; ?> value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
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