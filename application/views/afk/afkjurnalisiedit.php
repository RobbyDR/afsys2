<form action="<?= base_url() ?>afk/afkjurnaledit/<?= $get['id'] ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="modal-body">
        <div class="row">
            <div class="form-group col">
                <label>Tanggal<span class="text-danger">*</span></label>
                <input type="date" name="tanggal" class="form-control" title="isi tanggal" value="<?= $get['tanggal'] ?>" id="tanggal">
            </div>

            <div class="form-group col">
                <label>kategori</label>
                <select class="form-control mb-3" name="catid" id="catid2" required>
                    <option value="" <?php if ($get['catid'] == NULL) echo 'selected' ?>>pilih</option>
                    <?php foreach ($getcat as $row): ?>
                        <option value="<?= $row['id'] ?>" <?php if ($row['id'] == $get['catid']) echo 'selected' ?>>
                            <?= $row['deskripsi'] ?> (<?= $row['io'] ?>;<?= $row['cat'] ?>;<?= $row['desk2'] ?>)
                            <?= ($row['visibility'] == '1') ? '*' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="row">
            <div class="form-group col">
                <label>deskripsi<span class="text-danger">*</span></label>
                <input type="text" name="deskripsi" class="form-control" value="<?= $get['deskripsi'] ?>" title="isikan deskripsi">
            </div>
            <div class="form-group col">
                <label>deskripsi2<span class="text-danger">*</span></label>
                <input type="text" name="deskripsi2" class="form-control" value="<?= $get['deskripsi2'] ?>" title="isikan deskripsi2">
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label>saku<span class="text-danger">*</span></label>
                <input type="text" name="saku" class="form-control" title="isikan saku" value="<?= $get['saku'] ?>" id="saku2" required>
            </div>
            <div class="form-group col">
                <label>bni<span class="text-danger">*</span></label>
                <input type="text" name="bni" class="form-control" title="isikan bni" value="<?= $get['bni'] ?>" id="bni2" required>
            </div>
            <div class="form-group col">
                <label>bnik<span class="text-danger">*</span></label>
                <input type="text" name="bnik" class="form-control" title="isikan bnik" value="<?= $get['bnik'] ?>" id="bnik2" required>
            </div>
            <div class="form-group col">
                <label>saldo<span class="text-danger">**</span></label>
                <input type="text" name="saldo" class="form-control" title="otomatis" value="<?= $get['saldo'] ?>" id='saldo2' readonly>
            </div>
        </div>
        <span class="text-danger">**) saldo update manual</span>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        let saldoawal = afkjurnalgetlastsaldo();
        $("#saku2, #bni2, #bnik2").on("input", function() {
            updateSaldo(saldoawal);
        });

        // handle catid
        $("#catid2").on("change", function() {
            updateSaldo(saldoawal);
        });
    })

    function updateSaldo(saldoawal) {
        let saku = parseFloat($("#saku2").val()) || 0;
        let bni = parseFloat($("#bni2").val()) || 0;
        let bnik = parseFloat($("#bnik2").val()) || 0;

        let catid = $("#catid2").val();
        let io = await afkjurnalgetio(catid);
        // console.log(io);
        let saldo = 0;
        if (io == 'o') {
            saldo = saldoawal - saku - bni - bnik;
        } else if (io == 'i') {
            saldo = saldoawal + saku + bni + bnik;
        } else {
            saldo = saldoawal
        }

        $("#saldo2").val(saldo);
    }
</script>

<script>
    $(document).ready(function() {
        $('#catid2').select2({
            dropdownParent: $('#editafkModal'),
            // theme: 'bootstrap-5', // opsional, jika kamu mau menyesuaikan dengan Bootstrap 5
            width: '100%',
            placeholder: 'Pilih kategori...',
            allowClear: true
        });
    });
</script>