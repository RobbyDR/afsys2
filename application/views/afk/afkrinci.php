<div class="container-fluid scrollarea">
    <div class="row ">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <div class="col">
                <h1 class="h2"><?= $judul ?></h1>
                <h1 class="h5"><?= $cat ?> <?= (isset($bulan) ? $bulan . '/' : '') ?><?= $tahun ?></h1>
            </div>
        </div>
        <div class="container-fluid mt-n5">
            <div class="table-responsive">
                <table class="table align-items-center table-sm table-flush table-hover" id="afkrinci" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>Tanggal</th>
                            <th>Deskripsi</th>
                            <th>Saku</th>
                            <th>BNI</th>
                            <th>BNIk</th>
                            <th>TOTAL</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($get as $row) : ?>
                            <?php $total = $total + $row['saku'] + $row['bni'] + $row['bnik'] ?>
                            <tr>
                                <td class="text-wrap"><?= $row['id'] ?></td>
                                <td class="text-wrap"><?= $row['tanggal'] ?></td>
                                <td class="text-wrap"><?= $row['deskripsi']; ?></td>
                                <td class="text-wrap"><?= number_format($row['saku'], 0, ',', '.'); ?></td>
                                <td class="text-wrap"><?= number_format($row['bni'], 0, ',', '.'); ?></td>
                                <td class="text-wrap"><?= number_format($row['bnik'], 0, ',', '.'); ?></td>
                                <td class="text-wrap"><?= number_format($total, 0, ',', '.'); ?></td>
                                <td>
                                    <button type="button" data-toggle="modal" data-id="<?= $row['id'] ?>" data-target="#editafkModal" class="btn btn-sm btn-secondary mr-1" title="edit data"><span data-feather="edit" class="align-text-bottom"></span></button>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>




</div>

<!-- Edit Modal -->
<div class="modal fade" id="editafkModal" tabindex="-1" role="dialog" aria-labelledby="editafkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editafkModalLabel">Edit Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div id="isiedit"></div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        //====DATA TABLE
        $('#afkrinci1').DataTable({
            lengthMenu: [
                [50, 25, 50, -1],
                [50, 25, 50, 'All'],
            ],
            language: {
                paginate: {
                    previous: "<i class='fa fa-arrow-left'>",
                    next: "<i class='fa fa-arrow-right'>"
                }
            },
            lengthChange: !1,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'print'
            ],
            order: [
                [0, 'desc']
            ]
        });

        //===EDIT MODAL
        $("#editafkModal").on('shown.bs.modal', function(e) {
            let id = $(e.relatedTarget).data('id');
            // console.log(id);
            let csfrData = {};
            csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
            $.ajaxSetup({
                data: csfrData
            });

            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>afk/afkjurnalisiedit",
                data: {
                    id: id
                }
            }).done(function(response) {
                $("#isiedit").html(response);
            });
        });

    })
</script>