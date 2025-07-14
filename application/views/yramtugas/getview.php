<ul class="list-group">
    <li class="list-group-item">Nama: <?= $get['namapegawai'] ?> (<?= $get['nip'] ?>)</li>
    <li class="list-group-item">No: <?= $get['no'] ?></li>
    <li class="list-group-item">Judul: <?= $get['judul'] ?></li>
    <li class="list-group-item">Isi: <?= $get['isi'] ?></li>
    <li class="list-group-item">Tempat: <?= $get['tempat'] ?></li>
    <li class="list-group-item">Tanggal: <?= $get['tanggal'] ?></li>
    <li class="list-group-item">Keterangan: <?= $get['keterangan'] ?></li>
    <li class="list-group-item">Status: <?= $get['status'] ?></li>
    <li class="list-group-item">Data Dukung:
        <ul>
            <?php foreach ($getdatadukung as $row): ?>
                <li>
                    <a href="<?= base_url() . $row['path'] . $row['nama'] ?>" target="_blank"><?= $row['judul'] ?></a>
                    <button class="btn btn-warning btn-sm" data-id="<?= $row['id']; ?>" data-bs-toggle="modal" data-bs-target="#renameModal">Rename</button>
                    <!-- <a href="#!" class="btn btn-warning btn-sm" onclick="rename_data('<?= $row['id'] ?>','<?= $row['nama'] ?>','<?= $row['path'] ?>')">rename</a> -->
                    <a href="#!" class="btn btn-danger btn-sm" onclick="delete_data('<?= $row['id'] ?>','<?= $row['nama'] ?>','<?= $row['path'] ?>')">X</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>