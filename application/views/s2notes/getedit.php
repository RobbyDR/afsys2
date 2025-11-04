<form action="<?= base_url() ?>s2notes/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">
            <div class="mb-3 col">
                <label for="matkulid" class="col-form-label">matkulid</label>
                <select class="form-select" name="matkulid" id="matkulidedit">
                    <option>---pilih---</option>
                    <?php foreach ($getmatkulid as $row): ?>
                        <option <?= $row['id'] == $get['matkulid'] ? 'selected' : ''; ?> value="<?= $row['id'] ?>"><?= $row['nama'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 col">
                <label for="judul" class="col-form-label">judul</label>
                <input type="text" class="form-control" name="judul" value="<?= $get['judul'] ?>">
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

<script>
    $(document).ready(function() {
        $('#matkulidedit').select2({
            dropdownParent: $('#editModal'),
            // theme: 'bootstrap-5', // opsional, jika kamu mau menyesuaikan dengan Bootstrap 5
            width: '100%',
            placeholder: 'Pilih kategori...',
            allowClear: true
        });
    });
</script>