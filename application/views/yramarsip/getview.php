<ul class="list-group">
    <li class="list-group-item">Jenis: <?= $get['namayramarsipcat'] ?></li>
    <li class="list-group-item">nosurat: <?= $get['nosurat'] ?></li>
    <li class="list-group-item">Tanggal: <?= $get['tanggal'] ?></li>
    <li class="list-group-item">dari: <?= $get['dari'] ?></li>
    <li class="list-group-item">tujuan: <?= $get['tujuan'] ?></li>
    <li class="list-group-item">perihal: <?= $get['perihal'] ?></li>

    <li class="list-group-item">file:
        <a href="<?= base_url('upload/yramarsip/' . $get['file']) ?>" target="_blank">
            <?= $get['file'] ?>
        </a>
    </li>
    <li class="list-group-item">Keterangan: <?= $get['keterangan'] ?></li>
    <li class="list-group-item">Status: <?= $get['status'] ?></li>
</ul>