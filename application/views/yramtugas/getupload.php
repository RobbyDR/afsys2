<form action="<?= base_url() ?>yramtugas/upload" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="card text-center">
            <?= $get['namapegawai'] ?>-
            <?= $get['judul'] ?>-
            <?= $get['tempat'] ?>-
            <?= $get['tanggal'] ?>
        </div>
        <div class="row">
            <div class="mb-3 col">
                <label for="judul" class="col-form-label">judul</label>
                <input type="text" class="form-control" name="judul">
            </div>
            <div class="mb-3 col">
                <label for="attachment" class="col-form-label">attachment</label>
                <input type="file" class="form-control" name="attachment" accept=".gif, .jpg, .jpeg, .png, .pdf, .doc, .docx    ">
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>