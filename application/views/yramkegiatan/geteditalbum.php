<form action="<?= base_url() ?>yramkegiatan/editalbum" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <input type="hidden" name="utamaid" value="<?= $utamaid ?>">
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col">
                <label for="judul" class="col-form-label">judul</label>
                <input type="text" class="form-control" name="judul" value="<?= $get['judul'] ?>">
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>