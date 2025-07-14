<form action="<?= base_url() ?>yramtugas/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col">
                <label for="nip" class="col-form-label">nama</label>
                <select class="form-select" name="nip">
                    <option>---pilih---</option>
                    <?php foreach ($getnip as $row): ?>
                        <option <?= $row['nip'] == $get['nip'] ? 'selected' : ''; ?> value="<?= $row['nip'] ?>"><?= $row['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 col">
                <label for="no" class="col-form-label">no</label>
                <input type="text" class="form-control" name="no" value="<?= $get['no'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="judul" class="col-form-label">judul</label>
                <input type="text" class="form-control" name="judul" value="<?= $get['judul'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="tempat" class="col-form-label">tempat</label>
                <input type="text" class="form-control" name="tempat" value="<?= $get['tempat'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="tanggal" class="col-form-label">tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="<?= $get['tanggal'] ?>">
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
                <label for="isi" class="col-form-label">isi</label>
                <textarea class="form-control" name="isi"><?= $get['isi'] ?></textarea>
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