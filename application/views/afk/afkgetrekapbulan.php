<table class="table table-dark table-responsive table-striped table-sm table-hover">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Cat</th>
            <th scope="col">Deskripsi</th>
            <?php if ($pilihtahun == '2023') $startbulan = 12;
            else $startbulan = 1 ?>
            <?php for ($i = $startbulan; $i <= $blnterbaru1; $i++) : ?>
                <th scope="col" style="text-align: right;"><?= $i ?></th>
            <?php endfor; ?>
            <th style="text-align: right;">TOTAL</th>
        </tr>
    </thead>
    <tbody>


        <?php foreach ($datacat as $row) : ?> <!--  -->
            <?php $total = 0; ?>
            <tr>
                <th scope="row"><?= $row['id'] ?></th>
                <td><a href="<?= base_url('afk/afkrinci/' . $row['id'] . '/' . $pilihtahun) ?>"><?= $row['cat'] ?></a></td>
                <td><?= $row['deskripsi'] ?></td>
                <?php foreach ($datanilai[$row['id']] as $row1) : ?> <!--  -->
                    <td style="text-align: right;"><?= number_format(floatval($row1), 0, ',', '.'); ?></td>
                    <?php $total += $row1; ?>
                <?php endforeach; ?> <!--  -->
                <th style="text-align: right;"><?= number_format(floatval($total), 0, ',', '.'); ?></th>

            </tr>
        <?php endforeach; ?> <!--  -->

    </tbody>
    <?php $resume = array();

    foreach ($datanilai as $entry) {
        foreach ($entry as $date => $value) {
            if (isset($resume[$date])) {
                $resume[$date] += intval($value);
            } else {
                $resume[$date] = intval($value);
            }
        }
    }

    // var_dump($resume);
    // die; 
    $supertotal = 0;
    ?>

    <tfoot>
        <tr>
            <th colspan="3">TOTAL</th>
            <?php foreach ($resume as $resume1) : $supertotal += $resume1; ?>
                <th><?= number_format(floatval($resume1), 0, ',', '.'); ?></th>
            <?php endforeach; ?>
            <th><?= number_format(floatval($supertotal), 0, ',', '.'); ?></th>
        </tr>
    </tfoot>
</table>