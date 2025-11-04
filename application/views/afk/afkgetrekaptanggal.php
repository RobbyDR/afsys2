<?php
// Informasi tanggal
echo 'Tanggal terbaru aplikasi: ' . $tglterbaru . '<br>';
echo 'Tanggal terbaru sistem: ' . date('Y-m-d');
?>

<div class="table-responsive mt-2">
    <table class="table table-dark table-striped table-sm table-hover align-middle" id="tabel">
        <?php
        $start = new DateTime($tglsatu);
        $end = new DateTime($tglterbaru);
        $end->modify('+1 day'); // agar tanggal terakhir tetap ikut (karena DatePeriod eksklusif)

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);
        ?>

        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Cat</th>
                <th scope="col">Deskripsi</th>
                <?php foreach ($period as $date): ?>
                    <th scope="col" class="text-end"><?= $date->format('d') ?></th>
                <?php endforeach; ?>
                <th scope="col" class="text-end">TOTAL</th>
            </tr>
        </thead>


        <tbody>
            <?php
            // Inisialisasi total per tanggal
            $datatotalrow = [];
            for ($waktu = strtotime($tglsatu); $waktu <= strtotime($tglterbaru); $waktu += 86400) {
                $datatotalrow[date('Y-m-d', $waktu)] = 0;
            }
            ?>

            <?php foreach ($datacat as $row): ?>
                <tr>
                    <th scope="row"><?= $row['id'] ?></th>
                    <td>
                        <a href="<?= base_url('afk/afkrinci/' . $row['id'] . '/' . $pilihtahun . '/' . $pilihbulan) ?>">
                            <?= $row['cat'] ?>
                        </a>
                    </td>
                    <td><?= $row['deskripsi'] ?></td>

                    <?php $datatotalcol = 0; ?>
                    <?php for ($waktu = strtotime($tglsatu); $waktu <= strtotime($tglterbaru); $waktu += 86400): ?>
                        <?php
                        $tanggalKey = date('Y-m-d', $waktu);
                        $nilai = isset($datanilai[$row['id']][$tanggalKey]['nilai'])
                            ? floatval($datanilai[$row['id']][$tanggalKey]['nilai'])
                            : 0;

                        // Akumulasi total baris dan kolom
                        $datatotalrow[$tanggalKey] += $nilai;
                        $datatotalcol += $nilai;
                        ?>
                        <td class="text-end"><?= number_format($nilai, 0, ',', '.') ?></td>
                    <?php endfor; ?>

                    <td class="text-end fw-bold"><?= number_format($datatotalcol, 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="3">TOTAL</th>
                <?php $supertotal = 0; ?>
                <?php foreach ($datatotalrow as $tanggalKey => $nilai): ?>
                    <th class="text-end"><?= number_format($nilai, 0, ',', '.') ?></th>
                    <?php $supertotal += $nilai; ?>
                <?php endforeach; ?>
                <th class="text-end fw-bold"><?= number_format($supertotal, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function() {
        if ($('#tabel').length) {
            // Hapus DataTable lama jika sudah pernah dibuat
            if ($.fn.DataTable.isDataTable('#tabel')) {
                $('#tabel').DataTable().clear().destroy();
            }

            // Inisialisasi DataTable baru
            $('#tabel').DataTable({
                fixedColumns: {
                    left: 3,
                    right: 1
                },
                paging: false,
                scrollX: true,
                scrollCollapse: true,
                ordering: false, // ubah ke true kalau mau enable sorting
                info: false,
                searching: false,
                language: {
                    emptyTable: "Tidak ada data untuk periode ini"
                }
            });
        }
    });
</script>