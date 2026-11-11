<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= $judul ?></h1>
            </div>
            <div class="col d-flex justify-content-end">
                <!-- <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah</button> -->
                <!-- <button type="button" class="btn btn-primary btn-sm disabled">Laporan Bulanan</button> -->

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-primary" title="Tambah" data-bs-toggle="modal" data-bs-target="#tambahafkjurnalModal">Tambah</button>
                    <!-- <button type="button" id="jurnalkeuangan" class="btn btn-sm btn-primary" title="Jurnal Keuangan">Jurnal Keuangan</button> -->
                    <!-- <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span data-feather="refresh-ccw" class="align-text-bottom"></span>
                        </button> -->
                    <!-- <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li>
                                <button type="button" class="dropdown-item" id="updaterekap">
                                    Update Rekap
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" id="updatesaldo">
                                    Update Saldo Keseluruhan
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item" id="updatesaldo2">
                                    Update Saldo Bulanan
                                </button>
                            </li>
                        </ul> -->
                    <a href="#" id="dashboard"
                        rel="noopener noreferrer"
                        class="btn btn-sm btn-outline-warning"
                        title="goto dashboard">
                        Dashboard
                    </a>
                    <a href="#" id="insight"
                        rel="noopener noreferrer"
                        class="btn btn-sm btn-outline-warning"
                        title="goto insight">
                        Insight
                    </a>
                    <a href="#" id="grafik"
                        rel="noopener noreferrer"
                        class="btn btn-sm btn-outline-warning"
                        title="goto grafik keuangan">
                        <span data-feather="pie-chart"></span>
                    </a>
                    <a href="#" id="updatesaldo"
                        rel="noopener noreferrer"
                        class="btn btn-sm btn-outline-warning"
                        title="refresh">
                        <span data-feather="refresh-cw"></span>
                    </a>


                    <select class="form-select form-select-sm me-1" id="bulanselect">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == $bulanselect ? 'selected' : '' ?>>
                                <?= DateTime::createFromFormat('!m', $i)->format('F') ?>
                            </option>
                        <?php endfor; ?>
                    </select>

                    <select class="form-select form-select-sm" id="tahunselect">
                        <?php for ($i = $Ylama; $i <= $Ybaru; $i++): ?>
                            <option value="<?= $i ?>" <?= $i == $tahunselect ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="container-fluid mt-n5">
            <div class="row">
                <div class="col" id="isi"></div>
            </div>
        </div>

    </div>
</div>

<!-- Tambah Modal -->
<div class="modal fade" id="tambahafkjurnalModal" tabindex="-1" aria-labelledby="tambahafkjurnalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahafkjurnalModalLabel">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>afk/afkjurnaltambah" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label class="form-label">Tanggal<span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" class="form-control" value="<?= $tanggalafkjurnal ?>" id="tanggal">
                        </div>
                        <div class="col">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="catid" id="catid" required>
                                <option value="" <?= $catid == NULL ? 'selected' : '' ?>>---pilih---</option>
                                <?php foreach ($getcat as $row): ?>
                                    <option value="<?= $row['id'] ?>" <?= $row['id'] == $catid ? 'selected' : '' ?>>
                                        <?= $row['deskripsi'] ?> (<?= $row['io'] ?>;<?= $row['cat'] ?>;<?= $row['desk2'] ?>)
                                        <?= ($row['visibility'] == '1') ? '*' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Deskripsi<span class="text-danger">*</span></label>
                            <input type="text" name="deskripsi" class="form-control" value="-" title="isikan deskripsi">
                        </div>
                        <div class="col">
                            <label class="form-label">Deskripsi 2<span class="text-danger">*</span></label>
                            <input type="text" name="deskripsi2" class="form-control" value="-" title="isikan deskripsi2">
                        </div>
                    </div>

                    <div class="row row-cols-2 row-cols-lg-4 g-2">
                        <?php foreach ($akun as $a): ?>
                            <div class="col">
                                <label class="form-label"><?= html_escape($a['nama']) ?><span class="text-danger">*</span></label>
                                <input type="number" step="any" inputmode="decimal" name="akun[<?= (int) $a['id'] ?>]" data-nama="<?= html_escape($a['nama']) ?>" class="form-control input-akun" value="0" required>
                            </div>
                        <?php endforeach; ?>
                        <div class="col">
                            <label class="form-label">Saldo<span class="text-danger">*</span></label>
                            <input type="text" name="saldo" id="saldo" class="form-control" value="0" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editafkModal" tabindex="-1" aria-labelledby="editafkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editafkModalLabel">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="isiedit"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(async function() {
        $("#bulanselect, #tahunselect").on('change', getdataafkjurnal);
        await getdataafkjurnal();

        let saldoawal = await afkjurnalgetlastsaldo();
        $(".input-akun").on("input", function() {
            updateSaldo(saldoawal);
        });
        $("#catid").on("change", async function() {
            updateSaldo(saldoawal);
        });

        $("#updatesaldo").click(() => window.location.href = '<?= base_url('afk/afkrefreshallsaldo') ?>');

        // Validasi tambahan sebelum submit form Tambah: pastikan saku/bni/bnik
        // benar-benar angka. (Validasi utama tetap di server - lihat _validasiNominal
        // di controller - ini cuma lapisan UX supaya user langsung tahu salahnya di mana)
        $("#tambahafkjurnalModal form").on("submit", function(e) {
            let valid = true;
            $(".input-akun").each(function() {
                const val = $(this).val();
                if (val === "" || isNaN(val)) {
                    e.preventDefault();
                    alert("Nilai " + $(this).data('nama') + " harus berupa angka.");
                    $(this).focus();
                    valid = false;
                    return false; // hentikan each
                }
            });
            return valid;
        });

        $("#editafkModal").on('shown.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id');
            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });
            $.post("<?= base_url() ?>afk/afkjurnalisiedit", {
                id: id
            }, function(response) {
                $("#isiedit").html(response);
            });
        });
    });

    // ====== SALDO OTOMATIS ======
    function afkjurnalgetlastsaldo() {
        return new Promise((resolve, reject) => {
            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            $.ajax({
                url: "<?= base_url(); ?>afk/afkjurnalgetlastsaldo2",
                method: "POST",
                success: function(data) {
                    let saldoawal = parseFloat(data) || 0;
                    $("#saldo").val(saldoawal);
                    resolve(saldoawal);
                },
                error: function(_, __, errorThrown) {
                    reject(errorThrown);
                }
            });
        });
    }

    function afkjurnalgetio(catid) {
        return new Promise((resolve, reject) => {
            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            $.ajax({
                url: "<?= base_url(); ?>afk/afkjurnalgetio",
                method: "POST",
                data: {
                    id: catid
                },
                success: function(data) {
                    resolve(data);
                },
                error: function(_, __, errorThrown) {
                    reject(errorThrown);
                }
            });
        });
    }

    async function updateSaldo(saldoawal) {
        let total = 0;
        $(".input-akun").each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        let catid = $("#catid").val();
        let io = await afkjurnalgetio(catid);
        let saldo = saldoawal;

        if (io == 'o') saldo -= total;
        else if (io == 'i') saldo += total;

        $("#saldo").val(saldo);
    }

    function getdataafkjurnal() {
        let bulanselect = $("#bulanselect").val();
        let tahunselect = $("#tahunselect").val();

        return new Promise((resolve, reject) => {
            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            $.ajax({
                url: "<?= base_url(); ?>afk/getdataafkjurnal",
                method: "POST",
                data: {
                    bulanselect,
                    tahunselect
                },
                success: function(response) {
                    $("#isi").html(response);
                    resolve(response);
                },
                error: function(_, __, errorThrown) {
                    reject(errorThrown);
                }
            });
        });
    }
</script>

<script>
    $(document).ready(function() {
        $('#catid').select2({
            dropdownParent: $('#tambahafkjurnalModal'),
            // theme: 'bootstrap-5', // opsional, jika kamu mau menyesuaikan dengan Bootstrap 5
            width: '100%',
            placeholder: 'Pilih kategori...',
            allowClear: true
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#updaterekap").click(() => window.location.href = '<?= base_url('afk/afkupdaterekap') ?>');
        $("#insight").on("click", function() {
            window.location.href = '<?= base_url('afk/insight') ?>';
        });
        $("#dashboard").on("click", function() {
            window.location.href = '<?= base_url('afk') ?>';
        });
        $("#grafik").on("click", function() {
            window.location.href = '<?= base_url('afk/grafik') ?>';
        });
    });
</script>