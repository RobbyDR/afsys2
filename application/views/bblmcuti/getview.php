<?php
$jenis = null;
if ($get['jenis'] == 1) {
    $jenis = 'cuti tahunan';
} else if ($get['jenis'] == 2) {
    $jenis = 'cuti besar';
} else if ($get['jenis'] == 3) {
    $jenis = 'cuti sakit';
} else if ($get['jenis'] == 4) {
    $jenis = 'cuti melahirkan';
} else if ($get['jenis'] == 5) {
    $jenis = 'cuti karena alasan penting';
} else if ($get['jenis'] == 6) {
    $jenis = 'cuti diluar tanggungan negara';
}

?>

<ul class="list-group">
    <li class="list-group-item">pegawai: <?= $get['pegawai'] ?></li>
    <li class="list-group-item">jenis: <?= $jenis ?></li>
    <li class="list-group-item">tgl1: <?= $get['tgl1'] ?></li>
    <li class="list-group-item">tgl2: <?= $get['tgl2'] ?></li>
    <li class="list-group-item">jatah: <?= $get['jatah'] ?></li>
    <li class="list-group-item">alamat: <?= $get['alamat'] ?></li>
    <li class="list-group-item">telp: <?= $get['telp'] ?></li>
    <li class="list-group-item">approval1: <?= $get['approval1'] ?></li>
    <li class="list-group-item">approval2: <?= $get['approval2'] ?></li>
    <li class="list-group-item">ttdchoose1: <?= $get['ttdchoose1'] ?></li>
    <li class="list-group-item">ttdchoose2: <?= $get['ttdchoose2'] ?></li>
    <li class="list-group-item">pejabat1: <?= $get['pejabat1'] ?></li>
    <li class="list-group-item">pejabat2: <?= $get['pejabat2'] ?></li>
    <li class="list-group-item">alasan: <?= $get['alasan'] ?></li>
    <li class="list-group-item">Keterangan: <?= $get['keterangan'] ?></li>
    <li class="list-group-item">Status: <?= $get['status'] ?></li>
</ul>