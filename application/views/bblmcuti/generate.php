<!-- saved from url=(0101)https://intranew.kemenperin.go.id/cuti/cuti_cetak.php?id=dmgs8E999YU8SIzX5uSy1oJCbsrXCO1qxx8FrJsuX6U, -->
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>e-Formulir Permohonan Cuti</title>
</head>

<body bgcolor="white" onload="window.print()">
    <style type="text/css">
        td {
            font-family: "arial";
            font-size: 12px
        }
    </style>

    <center>
        <table border="0" width="700">
            <tbody>
                <tr>
                    <td valign="top">
                        No &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; : <br>
                        <br>
                        Yth. Kepala Balai Besar Standardisasi dan Pelayanan Jasa Industri Logam dan Mesin<br>
                        di -<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; Bandung<!--
melalui<br>
Pejabat Fungsional Teknisi Litkayasa<br>
di<br>

&nbsp; &nbsp; &nbsp; J A K A R T A
-->
                    </td>



                    <td align="right" valign="top">Bandung, <?= dateID(date('Y-m-d')) ?><br><br>

                    </td>
                </tr>
            </tbody>
        </table>


        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="0" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>



        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">I. DATA PEGAWAI </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">Nama</td>
                                    <td align="left"><?= $get['pegawai'] ?></td>
                                    <td align="left">NIP</td>
                                    <td align="left">
                                        <?= $get['nippegawai'] ?></td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">Jabatan</td>
                                    <td align="left"><?= $get['jabatanpegawai'] ?></td>
                                    <td align="left">Masa Kerja</td>
                                    <td align="left"> <?= $masakerja ?> </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">Unit Kerja</td>
                                    <td colspan="3" align="left">Balai Besar Standardisasi dan Pelayanan Jasa Industri Logam dan Mesin, Badan Standardisasi dan Kebijakan Jasa Industri</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="4" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">I. JENIS CUTI YANG DIAMBIL**</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">1. Cuti Tahunan</td>
                                    <td align="left">

                                        <input type="checkbox" name="cuti" value="CT" <?= $get['jenis'] == 1 ? 'checked' : '' ?>>




                                    </td>
                                    <td align="left">2. Cuti Besar</td>
                                    <td align="left">

                                        <input type="checkbox" name="cuti" value="CB" <?= $get['jenis'] == 2 ? 'checked' : '' ?>>
                                    </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">3. Cuti Sakit</td>
                                    <td align="left">


                                        <input type="checkbox" name="cuti" value="CS" <?= $get['jenis'] == 3 ? 'checked' : '' ?>>






                                    </td>
                                    <td align="left">4. Cuti Melahirkan</td>
                                    <td align="left">

                                        <input type="checkbox" name="cuti" value="CM" <?= $get['jenis'] == 4 ? 'checked' : '' ?>>


                                    </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">5. Cuti Karena Alasan Penting</td>
                                    <td align="left">

                                        <input type="checkbox" name="cuti" value="CAP" <?= $get['jenis'] == 5 ? 'checked' : '' ?>>

                                    </td>
                                    <td align="left">6. Cuti di Luar Tanggungan Negara</td>
                                    <td align="left">

                                        <input type="checkbox" name="cuti" value="CLTN" <?= $get['jenis'] == 6 ? 'checked' : '' ?>>

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">III. ALASAN CUTI</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left"> <?= $get['alasan'] ?><br> </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">IV. LAMANYA CUTI</td>
                                </tr>


                                <tr bgcolor="white" align="center">
                                    <td align="left" width="10%">Selama</td>
                                    <td align="left" width="30%">&nbsp; <?= $jumlahcuti ?> (<?= get_angkaketulisan($jumlahcuti) ?>) hari kerja </td>
                                    <td align="center" width="12%">Mulai Tanggal</td>
                                    <td align="center" width="35%">&nbsp; <?= dateID($get['tgl1']) ?> s/d <?= dateID($get['tgl2']) ?> </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">


                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="3" align="left">V. CATATAN CUTI***</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" rowspan="2" width="40%">1. CUTI TAHUNAN</td>
                                    <td align="left" width="20%">Jatah*****</td>
                                    <td align="left">
                                        <?= $get['jatah'] ?>
                                    </td>
                                </tr>

                                <tr bgcolor="white" align="center">
                                    <td align="left" width="20%">Diambil******</td>
                                    <td align="left"></td>
                                </tr>




                                <tr bgcolor="white" align="center">
                                    <td align="left">2. CUTI BESAR </td>
                                    <td align="left" colspan="2"> </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">3. CUTI SAKIT </td>
                                    <td align="left" colspan="2"> </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">4. CUTI MELAHIRKAN </td>
                                    <td align="left" colspan="2"> </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">5. CUTI KARENA ALASAN PENTING </td>
                                    <td align="left" colspan="2"> </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left">6. CUTI DI LUAR TANGGUNGAN NEGARA </td>
                                    <td align="left" colspan="2"> </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>


        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="3" align="left">VI. ALAMAT SELAMA MENJALANKAN CUTI</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" width="45%">&nbsp;<?= $get['alamat'] ?> </td>
                                    <td align="left" width="20%">TELP</td>
                                    <td align="left">
                                        <?= $get['telp'] ?>

                                    </td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" width="45%">&nbsp;&nbsp;</td>
                                    <td align="center" colspan="2" width="20%">Hormat Saya <br><img src="<?= base_url('upload/ttd/' . $get['ttd_filepegawai']) ?>" alt="Si ganteng maut..." height="80"><br><u> <?= $get['pegawai'] ?></u><br>NIP. <?= $get['nippegawai'] ?></td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>



        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">VII. PERTIMBANGAN ATASAN LANGSUNG**</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" width="20%">Disetujui</td>
                                    <td align="left" width="20%">Perubahan****</td>
                                    <td align="left" width="20%">DITANGGUHKAN****</td>
                                    <td align="center" width="40%">TIDAK DISETUJUI****</td>
                                </tr>

                                <tr bgcolor="white" align="center">
                                    <td align="center"><input type="checkbox" name="pertimbangan_AL" value="perubahan" <?= $get['approval1'] == 1 ? 'checked' : '' ?>> </td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_AL" value="ditangguhkan"></td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_AL" value="tidak" disetujui=""></td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_AL" value="tdk" disetujui=""></td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" colspan="3" cellspacing="0" valign="top"> Catatan</td>
                                    <td align="center">
                                        <?php if ($get['ttdchoose1'] == 1) : ?>
                                            <img src="<?= base_url('upload/bblmcuti/' . $get['ttdqr1']) ?>" alt="Si ganteng maut..." height="80">
                                        <?php else : ?>
                                            <img src="<?= base_url('upload/ttd/' . $get['ttd_filepejabat1']) ?>" alt="Si ganteng maut..." height="80">
                                        <?php endif; ?>
                                        <br><u><?= $get['pejabat1'] ?></u><br>
                                        NIP. <?= $get['nippejabat1'] ?>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <br>



        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="1" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="center">
                                    <td colspan="4" align="left">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI**</td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" width="20%">Disetujui</td>
                                    <td align="left" width="20%">Perubahan****</td>
                                    <td align="left" width="20%">DITANGGUHKAN****</td>
                                    <td align="center" width="40%">TIDAK DISETUJUI****</td>
                                </tr>

                                <tr bgcolor="white" align="center">
                                    <td align="center"><input type="checkbox" name="pertimbangan_PBMC" value="disetujui" <?= $get['approval2'] == 1 ? 'checked' : '' ?>></td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_PBMC" value="perubahan"></td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_PBMC" value="ditangguhkan"></td>
                                    <td align="center"><input type="checkbox" name="pertimbangan_PBMC" value="tidak" disetujui=""></td>
                                </tr>
                                <tr bgcolor="white" align="center">
                                    <td align="left" colspan="3" cellspacing="0" valign="top">Catatan</td>
                                    <td align="center"><br><?php if ($get['ttdchoose2'] == 1) : ?>
                                            <img src="<?= base_url('upload/bblmcuti/' . $get['ttdqr2']) ?>" alt="Si ganteng maut..." height="80">
                                        <?php else : ?>
                                            <img src="<?= base_url('upload/ttd/' . $get['ttd_filepejabat2']) ?>" alt="Si ganteng maut..." height="80">
                                        <?php endif; ?><br><u> <?= $get['pejabat2'] ?></u><br>NIP.
                                        <?= $get['nippejabat2'] ?> <br>
                                    </td>
                                </tr>


                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>



        <table border="0" cellpadding="0" cellspacing="0" width="700">
            <tbody>
                <tr bgcolor="0">
                    <td>
                        <table border="0" cellpadding="3" cellspacing="0" width="100%">
                            <tbody>
                                <tr bgcolor="white" align="left">
                                    <td colspan="4" align="left">


                                        <!-- <img src="./e-Formulir Permohonan Cuti_files/barcode.php"><br>
                                        <font size="1"><i>#200929</i></font> -->
                                        <font size="2"><i>Dokumen ini telah ditandatangani secara elektronik menggunakan sertifikat elektronik yang diterbitkan oleh <br>Balai Sertifikat Elektronik (BSrE) Badan Siber dan Sandi Negara</i></font>
                                    </td>
                                </tr>



                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>



    </center>

</body>

</html>