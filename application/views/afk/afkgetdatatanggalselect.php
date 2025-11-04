<table class="table table-dark">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kategori</th>
            <th scope="col">Deskripsi</th>
            <th scope="col">Nilai</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $akumulasinilai = 0;
        foreach ($getcat as $row) : ?>
            <tr>
                <th scope="row"><?= $row['id'] ?></th>
                <td><?= $row['tbl_afkcatcat'] ?></td>
                <td><?= $row['tbl_afkcatdeskripsi'] ?></td>
                <td><?= $row['nilai'] ?></td>
                <?php $akumulasinilai += $row['nilai'] ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">Saldo</th>
            <th><?= $akumulasinilai ?></th>
        </tr>
    </tfoot>
</table>