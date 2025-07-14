<form action="<?= base_url() ?>yramsdm/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">

            <div class="mb-3 col">
                <label for="nama" class="col-form-label">nama</label>
                <input type="text" class="form-control" name="nama" value="<?= $get['nama'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="nip" class="col-form-label">nip</label>
                <input type="text" class="form-control" name="nip" value="<?= $get['nip'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="nik" class="col-form-label">nik</label>
                <input type="text" class="form-control" name="nik" value="<?= $get['nik'] ?>">
            </div>
            <div class="mb-3 col">
                <label for="foto" class="col-form-label">foto</label>
                <input type="text" class="form-control" name="foto" value="<?= $get['foto'] ?>">
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