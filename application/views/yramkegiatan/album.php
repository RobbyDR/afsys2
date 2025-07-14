<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <a href="<?= base_url('yramkegiatan') ?>" class="btn btn-outline-secondary btn-sm mx-1">back</a>
            <div class="col">
                <h1 class="h2"><?= $judul ?></h1>
                <h6 class="h6"><?= $getutama['nama'] ?> (<?= $getutama['tgl1'] ?>)</h6>
            </div>
            <div class="col d-flex justify-content-end">
                <button type="button" class="btn btn-primary btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah</button>
                <!-- <button type="button" class="btn btn-primary btn-sm">Small button</button> -->
            </div>
        </div>

        <div class="album py-5 bg-body-tertiary">
            <div class="container">

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php foreach ($get as $row): ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <!-- <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">
                                <title>Placeholder</title>
                                <rect width="100%" height="100%" fill="#55595c" /><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text>
                            </sv    g> -->
                                <a href="<?= base_url('upload/yramkegiatan/' . $row['nama']) ?>" target="_blank">
                                    <?php
                                    $file_ext = pathinfo($row['nama'], PATHINFO_EXTENSION);
                                    $image_ext = ['jpg', 'jpeg', 'png', 'gif']; // Daftar ekstensi gambar

                                    // Cek apakah file merupakan gambar
                                    if (in_array(strtolower($file_ext), $image_ext)) {
                                        // Jika file adalah gambar, tampilkan gambarnya
                                        echo '<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="' . base_url('upload/yramkegiatan/' . $row['nama']) . '" alt="" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false">';
                                    } else {
                                        // Jika bukan gambar, tampilkan ikon dokumen
                                        echo '<img class="bd-placeholder-img card-img-top" width="100%" height="225" src="' . base_url('assets/img/doc-ico.png') . '" alt="Dokumen" role="img" aria-label="Dokumen" preserveAspectRatio="xMidYMid slice" focusable="false">';
                                    }
                                    ?></a>
                                <div class="card-body">
                                    <p class="card-text"><?= $row['judul'] ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-id="<?= $row['id']; ?>" data-utamaid="<?= $getutama['id']; ?>" data-bs-toggle="modal" data-bs-target="#editalbumModal">Edit</button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="delete_data('<?= $row['id'] ?>','<?= $row['nama'] ?>','<?= $row['path'] ?>')">Del</button>
                                        </div>
                                        <small class="text-body-secondary"><?= $row['timestamp'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- tambahModal -->
<div class="modal modal-xl fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="tambahModalLabel">Tambah Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url() ?>yramkegiatan/tambahalbum" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" value="<?= $getutama['id'] ?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col">
                            <label for="judul" class="col-form-label">judul</label>
                            <input type="text" class="form-control" name="judul" value="untitled">
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
        </div>
    </div>
</div>

<!-- viewModal -->
<!-- <div class="modal modal-lg fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="viewModalLabel">View Data</h1>
            </div>
            <div id="getview"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->


<!-- editalbumModal -->
<div class="modal modal-xl fade" id="editalbumModal" tabindex="-1" aria-labelledby="editalbumModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editalbumModalLabel">Edit Album</h1>
            </div>
            <div id="geteditalbum"></div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $("#viewModal").on('shown.bs.modal', function(e) {
        //     var csfrData = {};
        //     csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        //     $.ajaxSetup({
        //         data: csfrData
        //     });

        //     var id = $(e.relatedTarget).data('id');
        //     $.ajax({
        //         type: "POST",
        //         url: "<?= base_url() ?>yramkegiatan/getview",
        //         data: {
        //             id: id
        //         }
        //     }).done(function(response) {
        //         $("#getview").html(response);
        //     });
        // });

        $("#editalbumModal").on('shown.bs.modal', function(e) {
            var csfrData = {};
            csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            var id = $(e.relatedTarget).data('id');
            var utamaid = $(e.relatedTarget).data('utamaid');
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>yramkegiatan/geteditalbum",
                data: {
                    id: id,
                    utamaid: utamaid
                }
            }).done(function(response) {
                $("#geteditalbum").html(response);
            });
        });

    });

    function delete_data(id, nama, path) {
        Swal.fire({
            title: 'Konfirmasi ?',
            text: "Apakah kamu yakin ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            showLoaderOnConfirm: true,
            preConfirm: function() {

                var csfrData = {};
                csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?=
                                                                                $this->security->get_csrf_hash(); ?>';
                $.ajaxSetup({
                    data: csfrData
                });

                return new Promise(function(resolve, reject) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url() ?>yramkegiatan/delalbum",
                        data: {
                            id: id,
                            nama: nama,
                            path: path
                        }
                    }).done(function(msg) {
                        console.log(msg);
                        if (msg == "ok") {
                            swal.fire("OK!", "Data berhasil dihapus!", "success").then(function() {
                                location.reload();
                                // let status = $($(e.target).attr('href')).data('status');
                                // window.location.href = "<?= base_url('admin/ithelpdeska/index/') ?>" + status;
                            })
                        } else {
                            swal.fire("Gagal!", msg, "error").then(function() {
                                location.reload();
                            })
                        }
                    })
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }
</script>