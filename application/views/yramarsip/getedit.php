<form action="<?= base_url() ?>yramarsip/edit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?= $get['id'] ?>">
    <div class="modal-body">
        <div class="row">

            <div class="row">

                <div class="mb-3 col">
                    <label for="nosurat" class="col-form-label">nosurat</label>
                    <input type="text" class="form-control" name="nosurat" value="<?= $get['nosurat'] ?>" required>
                </div>
                <div class="mb-3 col">
                    <label for="dari" class="col-form-label">dari</label>
                    <input type="text" class="form-control" name="dari" value="<?= $get['dari'] ?>" required>
                </div>
                <div class="mb-3 col">
                    <label for="tujuan" class="col-form-label">tujuan</label>
                    <input type="text" class="form-control" name="tujuan" value="<?= $get['tujuan'] ?>" required>
                </div>
                <div class="mb-3 col">
                    <label for="perihal" class="col-form-label">perihal</label>
                    <input type="text" class="form-control" name="perihal" value="<?= $get['perihal'] ?>" required>
                </div>
                <div class="mb-3 col">
                    <label for="tanggal" class="col-form-label">tanggal</label>
                    <input type="date" class="form-control" name="tanggal" value='<?= $get['tanggal'] ?>'>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col">
                    <label for="jenis" class="col-form-label">jenis</label>
                    <select class="form-select" name="jenis" required>
                        <option value="" selected>---pilih---</option>
                        <?php foreach ($getcat as $row): ?>
                            <option value="<?= $row['id'] ?>" <?= ($row['id'] == $get['jenis']) ? 'selected' : '' ?>><?= $row['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
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
                <div class="mb-3 col">
                    <label for="file" class="col-form-label">file <a href="<?= base_url('yramarsip/getfile/' . $get['file']) ?>" target="_blank">click here!</a></label>
                    <input type="file" class="form-control" name="file" accept=".gif, .jpg, .jpeg, .png, .pdf, .doc, .docx">
                </div>
                <div class="mb-3 col">
                    <label for="keterangan" class="col-form-label">keterangan</label>
                    <textarea class="form-control" name="keterangan"><?= $get['keterangan'] ?></textarea>
                </div>
            </div>


        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
    </div>
</form>