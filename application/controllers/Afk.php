<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Afk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Afk_mod');
        $this->load->model(['sql']);
        $this->load->helper('cookie');
        // is_logged_in();
    }

    public function index()
    {
        $tanggalskrObj = new DateTime();
        $data['blnskr2'] = $tanggalskrObj->format('m');
        $data['thnskr'] = $tanggalskrObj->format('Y');
        $data['thnterlama'] = date('Y', strtotime($this->Afk_mod->afkgettanggal('lama', 'tbl_afkmain')));
        $data['thnterbaru'] = date('Y', strtotime($this->Afk_mod->afkgettanggal('baru', 'tbl_afkmain')));

        $data['datacat'] = $this->Afk_mod->afkgetallcatdata(null, '1')->result_array();

        if ($data['thnterlama'] || $data['thnterbaru']) {
            $datanilaiValues = [];
            foreach ($data['datacat'] as $row) {
                $datanilaiRow = [];

                for ($tahun = $data['thnterlama']; $tahun <=  $data['thnterbaru']; $tahun++) {
                    $tgl10 = new DateTime($tahun . '-01-01');
                    $tgl11 = $tgl10->format('Y-m-d');
                    if ($this->Afk_mod->afkgetdatarekapnilai($row['id'], 'tahunan', $tgl11)->row_array()) {
                        $datanilaiRow[$tgl11] = $this->Afk_mod->afkgetdatarekapnilai($row['id'], 'tahunan', $tgl11)->row_array();
                    } else $datanilaiRow[$tgl11]['nilai'] = '0';
                }

                $datanilaiValues[$row['id']] = $datanilaiRow;
            }
            $data['datanilai'] = $datanilaiValues;
        } else {
            $data['datanilai'] = null;
        }

        $data['saldoskrsaku'] = $this->Afk_mod->afkgetsaldoskr('saku');
        $data['saldoskrbni'] = $this->Afk_mod->afkgetsaldoskr('bni');
        $data['saldoskrbnik'] = $this->Afk_mod->afkgetsaldoskr('bnik');

        $data['bnikstate'] = $this->Afk_mod->afkgetdeltastate($data['saldoskrbnik'], $this->Afk_mod->afkgetsaldo1('bnik'));
        $data['deltabnik'] = ($data['saldoskrbnik'] - $this->Afk_mod->afkgetsaldo1('bnik'));

        $data['bnistate'] = $this->Afk_mod->afkgetdeltastate($data['saldoskrbni'], $this->Afk_mod->afkgetsaldo1('bni'));
        $data['deltabni'] = ($data['saldoskrbni'] - $this->Afk_mod->afkgetsaldo1('bni'));

        $data['sakustate'] = $this->Afk_mod->afkgetdeltastate($data['saldoskrsaku'], $this->Afk_mod->afkgetsaldo1('saku'));
        $data['deltasaku'] = ($data['saldoskrsaku'] - $this->Afk_mod->afkgetsaldo1('saku'));

        $data['deltabnibnik'] = $data['deltabni'] + $data['deltabnik'];
        $data['bnibnikstate'] = $this->Afk_mod->afkgetdeltastate($data['deltabnibnik'], 0);

        $data['deltabnibniksaku'] = $data['deltabnibnik'] + $data['deltasaku'];
        $data['bnibniksakustate'] = $this->Afk_mod->afkgetdeltastate($data['deltabnibniksaku'], 0);

        $data['judul'] = "AF - Dashboard Keuangan";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();
        $data['subview'] = "afk/index";
        $this->load->view('partial', $data);
    }

    public function afkjurnal($bulanselect = null, $tahunselect = null)
    {
        if ($tahunselect == null || $bulanselect == null) {
            $tahunselect = date("Y");
            $bulanselect = date("m");
        }

        //informasi tanggal ter-
        if ($this->Afk_mod->afkgettanggal('lama', 'tbl_afkmain') != null) {
            $data['Ylama'] = date('Y', strtotime($this->Afk_mod->afkgettanggal('lama', 'tbl_afkmain')));
            // $data['Ybaru'] = date('Y', strtotime($this->Afk_mod->afkgettanggal('baru', 'tbl_afkmain')));
            $data['Ybaru'] = date('Y');
        } else {
            $data['Ylama'] = null;
            $data['Ybaru'] = null;
        }

        $data['getcat'] = $this->Afk_mod->afkgetallcatdata('descvisibility')->result_array();

        //PENGATURAN COOKIE
        if (get_cookie('tanggalafkjurnal')) {
            $data['tanggalafkjurnal'] = get_cookie('tanggalafkjurnal');
        } else $data['tanggalafkjurnal'] = date("Y-m-d");

        $data['catid'] = get_cookie('catid');

        $data['bulanselect'] = $bulanselect;
        $data['tahunselect'] = $tahunselect;

        $data['judul'] = "AF - Jurnal Keuangan";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();
        $data['subview'] = "afk/afkjurnal";
        $this->load->view('partial', $data);
    }

    public function getdataafkjurnal()
    {
        $datapost = $this->input->post();
        if ($datapost) {
            $data['get'] = $this->Afk_mod->afkgetalldatajurnal()->result_array();
            // $data['get'] = 'robby';
            // var_dump($data['get']);
            // die;
            $this->load->view('afk/afkgetdatajurnal', $data);
        } else {
            echo 'error1';
        }
    }

    public function afkjurnalgetlastsaldo()
    {
        echo $this->Afk_mod->afkjurnalgetlastsaldo();
    }

    public function afkjurnalgetio()
    {
        $datapost = $this->input->post();
        if ($datapost) {
            echo $this->Afk_mod->afkjurnalgetio($this->input->post('id'));
        } else {
            echo 'error';
        }
    }

    public function afkjurnaltambah()
    {
        if ($this->input->post()) {
            $this->Afk_mod->afkjurnaltambah();
            $this->session->set_flashdata('success', 'Berhasil menambahkan data');

            redirect("afk/afkjurnal");
        } else {
            $this->session->set_flashdata('error', 'Error!');
            redirect("afk/afkjurnal");
        }
    }

    public function afkjurnalisiedit()
    {
        if ($this->input->post()) {
            $id = $this->input->post('id');
            $data['get'] = $this->Afk_mod->afkjurnalgetdatabyid($id);
            // $data['getcat'] = $this->Afk_mod->afkgetallcatdata()->result_array();
            $data['getcat'] = $this->Afk_mod->afkgetallcatdata('descvisibility')->result_array();
            // var_dump($data['get']);
            // die;
            $this->load->view('afk/afkjurnalisiedit', $data);
        } else {
            echo "error";
        }
    }

    public function afkjurnaledit($id)
    {
        if ($this->input->post()) {
            //edit update
            $this->Afk_mod->afkjurnaledit($id);
            $this->session->set_flashdata('success', 'Berhasil mengubah data');

            redirect("afk/afkjurnal");
        } else {
            $this->session->set_flashdata('error', 'Error!');
            redirect("afk/afkjurnal");
        }
    }

    public function afkrefreshallsaldo()
    {
        $this->Afk_mod->afkrefreshallsaldo();
        $this->afkupdaterekap(); //sekalian
        $this->session->set_flashdata('success', 'Berhasil refresh saldo');
        redirect("afk/afkjurnal");
    }

    public function afkrefreshallsaldobulanan($tanggal = null)
    {
        if ($tanggal == null) {
            $bulan = date('m');
            $tahun = date('Y');
        }

        $this->Afk_mod->afkrefreshallsaldobulanan($bulan, $tahun);

        $this->session->set_flashdata('success', 'Berhasil refreshsaldo bulanan');
        redirect("afk/afkjurnal");
    }

    //==========REKAP

    public function afkupdaterekap()
    {
        $querycat = $this->Afk_mod->afkgetallcatdata()->result_array();
        foreach ($querycat as $row) $this->Afk_mod->afkupdaterekapfast($row['id']);
        // die;
        $this->session->set_flashdata('success', 'Berhasil update rekap');
        // Cek apakah ada halaman sebelumnya
        $referer = $this->input->server('HTTP_REFERER');

        if (!empty($referer)) {
            // Kembali ke halaman sebelumnya
            redirect($referer);
        } else {
            // Jika tidak ada referer, fallback ke afk/index
            redirect("afk/index");
        }
    }

    public function afkupdaterekapbulanan($tanggal = null)
    {
        if ($tanggal == null) $tanggal = date('Y-m-d');

        $querycat = $this->Afk_mod->afkgetallcatdata()->result_array();
        foreach ($querycat as $row) $this->Afk_mod->afkupdaterekapbulanan($row['id'], $tanggal);

        $this->session->set_flashdata('success', 'Berhasil update rekap bulanan');
        redirect("afk/index");
    }

    public function afkupdaterekaplampau()
    {
        $tanggal = date('Y-m-d');
        $tanggal = date('Y-m-d', strtotime($tanggal . ' +1 day'));

        $querycat = $this->Afk_mod->afkgetallcatdata()->result_array();
        foreach ($querycat as $row) $this->Afk_mod->afkupdaterekapbulanan($row['id'], $tanggal, '7');

        $this->session->set_flashdata('success', 'Berhasil update rekap bulanan');
        redirect("afk/index");
    }

    //===REKAP
    // public function afkgetdatatanggalselect()
    // {
    //     $datapost = $this->input->post();
    //     if ($datapost) {
    //         $data['getcat'] = $this->Afk_mod->afkgetalldatarekap('harian', $this->input->post('tanggalselect'))->result_array();
    //         // var_dump($data['get']);
    //         // die;
    //         $this->load->view('afk/afkgetdatatanggalselect', $data);
    //     } else {
    //         echo 'error';
    //     }
    // }

    public function afkgetrekaptanggal()
    {
        $datapost = $this->input->post();
        if (!$datapost) {
            echo 'error';
            return;
        }

        // Ambil dan validasi input
        $pilihbulan = isset($datapost['pilihbulan']) ? intval($datapost['pilihbulan']) : 0;
        $pilihtahun  = isset($datapost['pilihtahun'])  ? intval($datapost['pilihtahun'])  : 0;

        // minimal validasi sederhana
        if ($pilihbulan < 1 || $pilihbulan > 12 || $pilihtahun < 1970) {
            echo 'invalid date';
            return;
        }

        $data['pilihbulan'] = $pilihbulan;
        $data['pilihtahun'] = $pilihtahun;

        // Hitung awal & akhir bulan yang dipilih
        $monthStart = date('Y-m-d', strtotime(sprintf('%04d-%02d-01', $pilihtahun, $pilihbulan)));
        $monthEnd   = date('Y-m-t', strtotime($monthStart));
        $today      = date('Y-m-d');

        // Tglsatu: jika model mengembalikan 'lama' pakai itu, kalau tidak pakai awal bulan
        $lama = $this->Afk_mod->afkgettanggal2('lama', $pilihbulan, $pilihtahun);
        if ($lama) {
            $data['tglsatu'] = date('Y-m-d', strtotime($lama));
        } else {
            $data['tglsatu'] = $monthStart;
        }

        // Tglterbaru:
        // Jika model bilang ada data 'baru' untuk bulan itu, maka:
        // - jika bulan yang dipilih adalah bulan berjalan => sampai hari ini
        // - jika bukan bulan berjalan => sampai akhir bulan yang dipilih
        $hasNew = $this->Afk_mod->afkgettanggal2('baru', $pilihbulan, $pilihtahun);
        if ($hasNew) {
            if ((int)$pilihtahun === (int)date('Y') && (int)$pilihbulan === (int)date('n')) {
                $data['tglterbaru'] = $today;
            } else {
                $data['tglterbaru'] = $monthEnd;
            }
            $data['tglterbaru1'] = date('d', strtotime($data['tglterbaru']));
        } else {
            // Tidak ada data "baru" di bulan itu
            $data['tglterbaru'] = '0';
            $data['tglterbaru1'] = '0';
        }

        // Pastikan rentang valid: tglsatu <= tglterbaru. Jika tidak, sesuaikan.
        if ($data['tglterbaru'] !== '0' && strtotime($data['tglsatu']) > strtotime($data['tglterbaru'])) {
            // fallback: set tglsatu ke awal bulan
            $data['tglsatu'] = $monthStart;
            // jika masih > tglterbaru, set tglsatu = tglterbaru
            if (strtotime($data['tglsatu']) > strtotime($data['tglterbaru'])) {
                $data['tglsatu'] = $data['tglterbaru'];
            }
        }

        $data['datacat'] = $this->Afk_mod->afkgetallcatdata(null, '1')->result_array();

        // Ambil data nilai per hari (atau kosongkan ke 0)
        if ($data['tglsatu'] !== '0' && $data['tglterbaru'] !== '0') {
            $datanilaiValues = [];
            foreach ($data['datacat'] as $row) {
                $datanilaiRow = [];
                for ($waktu = strtotime($data['tglsatu']); $waktu <= strtotime($data['tglterbaru']); $waktu += 86400) {
                    $tglkey = date('Y-m-d', $waktu);
                    $r = $this->Afk_mod->afkgetdatarekapnilai($row['id'], 'harian', $tglkey)->row_array();
                    if ($r) {
                        $datanilaiRow[$tglkey] = $r;
                    } else {
                        $datanilaiRow[$tglkey] = ['nilai' => 0];
                    }
                }
                $datanilaiValues[$row['id']] = $datanilaiRow;
            }
            $data['datanilai'] = $datanilaiValues;
        } else {
            // Tidak ada rentang valid / tidak ada data
            $data['datanilai'] = null;
        }

        $this->load->view('afk/afkgetrekaptanggal', $data);
    }


    public function afkgetrekapbulan()
    {
        $datapost = $this->input->post();
        if ($datapost['pilihtahun']) {
            //pengaturan tanggal
            $pilihtahun = $datapost['pilihtahun'];
            $data['pilihtahun'] = $pilihtahun;

            //mencari bulan pertama 1/1/xxxx
            if ($this->Afk_mod->afkgetbulan('lama', $pilihtahun)) {
                $data['blnsatu'] = date('Y-m-d', strtotime($this->Afk_mod->afkgetbulan('lama', $pilihtahun)));
                $tglsatuterlamaDate = new DateTime($data['blnsatu']);
                $tglsatuterlamaDate->modify('first day of this month');

                $tglsatuterlamaDate1 = new DateTime($data['blnsatu']);
                $tglsatuterlamaDate1->modify('first day of this month');
                $data['tglsatuterlamaDate1'] = $tglsatuterlamaDate1;

                $tglsatuterlamaDate2 = new DateTime($data['blnsatu']);
                $tglsatuterlamaDate2->modify('first day of this month');
                $data['tglsatuterlamaDate2'] = $tglsatuterlamaDate2;

                $tglsatuterlamaDate3 = new DateTime($data['blnsatu']);
                $tglsatuterlamaDate3->modify('first day of this month');
                $data['tglsatuterlamaDate3'] = $tglsatuterlamaDate3;
                // var_dump($data['blnsatu']);
                // var_dump($data['tglsatuterlamaDate1']);
                // var_dump($data['tglsatuterlamaDate2']);
                // var_dump($data['tglsatuterlamaDate3']);
                // die;
            } else {
                $data['blnsatu'] = '0';
            }

            //mencari bulan akhir 1/12/xxxx
            if ($this->Afk_mod->afkgetbulan('baru', $pilihtahun)) {
                $data['blnterbaru'] = date('Y-m-d', strtotime($this->Afk_mod->afkgetbulan('baru', $pilihtahun)));
                $data['blnterbaru1'] = date('m', strtotime($this->Afk_mod->afkgetbulan('baru', $pilihtahun)));
                $tglsatuterbaruDate = new DateTime($data['blnterbaru']);
                $tglsatuterbaruDate->modify('first day of this month');
                $data['tglsatuterbaruDate'] = $tglsatuterbaruDate;
            } else {
                $data['blnterbaru'] = '0';
                $data['blnterbaru1'] = '0';
            }
            // var_dump($data['blnterbaru']);
            // var_dump($data['blnterbaru1']);
            // die;

            $data['datacat'] = $this->Afk_mod->afkgetallcatdata(null, '1')->result_array();
            // var_dump($tglsatuterlamaDate);
            // var_dump($tglsatuterbaruDate);
            // die;

            //restrukturisasi data
            if ($data['blnsatu'] || $data['blnterbaru']) {

                // $datanilaiValues = [];
                // $bulanawal = $tglsatuterlamaDate->format('m');
                // $bulanakhir = $tglsatuterbaruDate->format('m');

                // while ($tglsatuterlamaDate <= $tglsatuterbaruDate) {
                //     foreach ($data['datacat'] as $row) {
                //         $datanilaiRow = [];



                //         if ($this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan',  $tglsatuterlamaDate->format('Y-m-d'))->row_array()) {
                //             $datanilaiRow[$tglsatuterlamaDate->format('Y-m-d')] = $this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan',  $tglsatuterlamaDate->format('Y-m-d'))->row_array();
                //         } else $datanilaiRow[$tglsatuterlamaDate->format('Y-m-d')]['nilai'] = '0';



                //         $datanilaiValues[$row['id']] = $datanilaiRow;
                //     }
                //     // var_dump($tglsatuterlamaDate);
                //     $tglsatuterlamaDate->add(new DateInterval('P1M'));
                // }
                // $data['datanilai'] = $datanilaiValues;

                $datanilaiValues = [];
                foreach ($data['datacat'] as $row) {
                    while ($tglsatuterlamaDate <= $tglsatuterbaruDate) {

                        if (!$this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan',  $tglsatuterlamaDate->format('Y-m-d'))->row_array()) {
                            $datanilaiValues[$row['id']][$tglsatuterlamaDate->format('Y-m-d')] = '0';
                        } else {
                            $datanilaiValues[$row['id']][$tglsatuterlamaDate->format('Y-m-d')] = $this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan',  $tglsatuterlamaDate->format('Y-m-d'))->row_array()['nilai'];
                        }


                        $tglsatuterlamaDate->add(new DateInterval('P1M'));
                    }
                    $tglsatuterlamaDate = new DateTime($data['blnsatu']);
                    $tglsatuterlamaDate->modify('first day of this month');
                }
                $data['datanilai'] = $datanilaiValues;
            } else {
                $data['datanilai'] = null;
            }
            // var_dump($data['datanilai']);
            // die;
            // if ($data['blnsatu'] || $data['blnterbaru']) {
            //     $datanilaiValues = [];
            //     foreach ($data['datacat'] as $row) {
            //         $datanilaiRow = [];

            //         for ($waktu = strtotime($data['blnsatu']); $waktu <= strtotime($data['blnterbaru']); $waktu += 86400) {
            //             if ($this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan', date('Y-m-d', $waktu))->row_array()) {
            //                 $datanilaiRow[date('Y-m-d', $waktu)] = $this->Afk_mod->afkgetdatarekapnilai($row['id'], 'bulanan', date('Y-m-d', $waktu))->row_array();
            //             } else $datanilaiRow[date('Y-m-d', $waktu)]['nilai'] = '0';
            //         }

            //         $datanilaiValues[$row['id']] = $datanilaiRow;
            //     }
            //     $data['datanilai'] = $datanilaiValues;
            // } else {
            //     $data['datanilai'] = null;
            // }

            $this->load->view('afk/afkgetrekapbulan', $data);
        } else {
            echo 'kosong';
        }
    }

    public function afkrinci($cat, $pilihtahun = null, $pilihbulan = null)
    {

        $data['get'] = $this->Afk_mod->afkgetalldatajurnalbyid($pilihtahun, $pilihbulan, $cat, 'ascid')->result_array();
        // var_dump($data['get']);
        // die;
        $data['tahun'] = $pilihtahun;
        $data['bulan'] = $pilihbulan;
        $data['cat'] = $this->Afk_mod->afkjurnalgetdeskripsi($cat);
        $data['judul'] = "Rincian";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();
        $data['subview'] = "afk/afkrinci";
        $this->load->view('partial', $data);
    }

    public function insight($tanggalUrl = null)
    {
        // =====================================================
        // 1. SINGLE SOURCE OF TRUTH : DATE OBJECT
        // =====================================================
        try {
            $date = new DateTime($tanggalUrl ?? date('Y-m-d'));
        } catch (Exception $e) {
            show_404();
        }

        // =====================================================
        // 2. DERIVASI WAKTU
        // =====================================================
        $tahun   = $date->format('Y');
        $bulan   = $date->format('m');
        $tanggal = $date->format('d');

        $waktuhari  = $date->format('Y-m-d');
        $waktuBulan = $date->format('Y-m-01');
        $waktuTahun = $date->format('Y-01-01');

        $data['tanggal'] = (int) $tanggal;
        $data['bulan']   = (int) $bulan;
        $data['tahun']   = (int) $tahun;

        $data['tanggal_full'] = $date->format('Y-m-d');

        // =====================================================
        // 3. KONFIG UMUM QUERY
        // =====================================================
        $column = '
        tbl_afkrekap.jenis  AS jenis,
        tbl_afkrekap.nilai  AS nilai,
        tbl_afkcat.deskripsi AS deskripsi,
        tbl_afkcat.cat      AS cat
    ';
        $order_by = 'CONVERT(nilai, SIGNED) ASC';

        // =====================================================
        // 4. HARIAN
        // =====================================================
        $data['gethari'] = $this->sql->select_table_join(
            'tbl_afkrekap',
            $column,
            'tbl_afkcat',
            'tbl_afkcat.id = tbl_afkrekap.jenis',
            'LEFT',
            [
                'tbl_afkrekap.jeniswaktu' => 'harian',
                'tbl_afkrekap.waktu'      => $waktuhari,
                'tbl_afkcat.io'           => 'o'
            ],
            $order_by
        )->result_array();

        $data['OUThari'] = array_sum(array_column($data['gethari'], 'nilai'));

        $data['INhari'] = array_sum(array_column(
            $this->sql->select_table_join(
                'tbl_afkrekap',
                'tbl_afkrekap.nilai AS nilai',
                'tbl_afkcat',
                'tbl_afkcat.id = tbl_afkrekap.jenis',
                'LEFT',
                [
                    'tbl_afkrekap.jeniswaktu' => 'harian',
                    'tbl_afkrekap.waktu'      => $waktuhari,
                    'tbl_afkcat.io'           => 'i'
                ]
            )->result_array(),
            'nilai'
        ));

        $data['SALDOhari'] = $data['INhari'] + $data['OUThari'];

        // =====================================================
        // 5. BULANAN
        // =====================================================
        $data['get'] = $this->sql->select_table_join(
            'tbl_afkrekap',
            $column,
            'tbl_afkcat',
            'tbl_afkcat.id = tbl_afkrekap.jenis',
            'LEFT',
            [
                'tbl_afkrekap.jeniswaktu' => 'bulanan',
                'tbl_afkrekap.waktu'      => $waktuBulan,
                'tbl_afkcat.io'           => 'o'
            ],
            $order_by
        )->result_array();

        $data['OUTbulan'] = array_sum(array_column($data['get'], 'nilai'));

        $data['INbulan'] = array_sum(array_column(
            $this->sql->select_table_join(
                'tbl_afkrekap',
                'tbl_afkrekap.nilai AS nilai',
                'tbl_afkcat',
                'tbl_afkcat.id = tbl_afkrekap.jenis',
                'LEFT',
                [
                    'tbl_afkrekap.jeniswaktu' => 'bulanan',
                    'tbl_afkrekap.waktu'      => $waktuBulan,
                    'tbl_afkcat.io'           => 'i'
                ]
            )->result_array(),
            'nilai'
        ));

        $data['SALDObulan'] = $data['INbulan'] + $data['OUTbulan'];

        // =====================================================
        // 6. TAHUNAN
        // =====================================================
        $data['gettahun'] = $this->sql->select_table_join(
            'tbl_afkrekap',
            $column,
            'tbl_afkcat',
            'tbl_afkcat.id = tbl_afkrekap.jenis',
            'LEFT',
            [
                'tbl_afkrekap.jeniswaktu' => 'tahunan',
                'tbl_afkrekap.waktu'      => $waktuTahun,
                'tbl_afkcat.io'           => 'o'
            ],
            $order_by
        )->result_array();

        $data['OUTtahun'] = array_sum(array_column($data['gettahun'], 'nilai'));

        $data['INtahun'] = array_sum(array_column(
            $this->sql->select_table_join(
                'tbl_afkrekap',
                'tbl_afkrekap.nilai AS nilai',
                'tbl_afkcat',
                'tbl_afkcat.id = tbl_afkrekap.jenis',
                'LEFT',
                [
                    'tbl_afkrekap.jeniswaktu' => 'tahunan',
                    'tbl_afkrekap.waktu'      => $waktuTahun,
                    'tbl_afkcat.io'           => 'i'
                ]
            )->result_array(),
            'nilai'
        ));

        $data['SALDOtahun'] = $data['INtahun'] + $data['OUTtahun'];

        // =====================================================
        // 7. 4EVER
        // =====================================================
        $data['get4ever'] = $this->sql->select_table_join(
            'tbl_afkrekap',
            $column,
            'tbl_afkcat',
            'tbl_afkcat.id = tbl_afkrekap.jenis',
            'LEFT',
            [
                'tbl_afkrekap.jeniswaktu' => '4ever',
                'tbl_afkcat.io'           => 'o'
            ],
            $order_by
        )->result_array();

        $data['OUT4ever'] = array_sum(array_column($data['get4ever'], 'nilai'));

        $data['IN4ever'] = array_sum(array_column(
            $this->sql->select_table_join(
                'tbl_afkrekap',
                'tbl_afkrekap.nilai AS nilai',
                'tbl_afkcat',
                'tbl_afkcat.id = tbl_afkrekap.jenis',
                'LEFT',
                [
                    'tbl_afkrekap.jeniswaktu' => '4ever',
                    'tbl_afkcat.io'           => 'i'
                ]
            )->result_array(),
            'nilai'
        ));

        $data['SALDO4ever'] = $data['IN4ever'] + $data['OUT4ever'];

        // =====================================================
        // 8. VIEW
        // =====================================================
        $data['judul'] = 'Wawasan';
        $data['getmenu'] = $this->sql->select_table(
            'tbl_devmenuaf',
            ['status' => '1', 'jenis' => 'menu'],
            'urutan ASC'
        )->result_array();

        $data['getsubmenu'] = $this->sql->select_table(
            'tbl_devmenuaf',
            ['status' => '1', 'jenis' => 'submenu'],
            'urutan ASC'
        )->result_array();

        // SIMPAN DATA INSIGHT KE SESSION (TEMPORARY)
        $this->session->set_userdata('afk_insight_cache', [
            'tanggal'   => $data['tanggal'],
            'bulan'     => $data['bulan'],
            'tahun'     => $data['tahun'],
            'gethari'   => $data['gethari'],
            'get'       => $data['get'],
            'gettahun'  => $data['gettahun'],
            'get4ever'  => $data['get4ever'],
        ]);


        $data['subview'] = 'afk/insight';
        $this->load->view('partial', $data);
    }


    // public function insight($tahun = null, $bulan = null, $tanggal = null)
    // {
    //     // RT Bulan
    //     // Jika $tahun atau $bulan tidak diberikan, gunakan nilai default (tahun dan bulan saat ini)
    //     $tahun = $tahun ?? date('Y');
    //     $bulan = $bulan ?? date('m');
    //     $tanggal = $tanggal ?? date('d');
    //     $waktuhari = date("$tahun-$bulan-$tanggal");

    //     // Tentukan tanggal pertama bulan ini dan tahun ini
    //     $waktu = date("$tahun-$bulan-01");
    //     $waktutahun = date("$tahun-01-01");

    //     // Simpan bulan dan tahun dalam array data
    //     $data['tanggal'] = $tanggal;
    //     $data['bulan'] = $bulan;
    //     $data['tahun'] = $tahun;


    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'bulanan',
    //         'tbl_afkrekap.waktu' => $waktu,
    //         'tbl_afkcat.io' => 'o'
    //     ];
    //     $column = 'tbl_afkrekap.jenis as jenis,
    //     tbl_afkrekap.nilai as nilai,
    //     tbl_afkcat.deskripsi as deskripsi,
    //     tbl_afkcat.cat as cat';
    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';
    //     $data['get'] = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $where, $order_by)->result_array();
    //     // RT Tahun
    //     // $waktutahun = date('Y-01-01');
    //     $wheretahun = [
    //         'tbl_afkrekap.jeniswaktu' => 'tahunan',
    //         'tbl_afkrekap.waktu' => $waktutahun,
    //         'tbl_afkcat.io' => 'o'
    //     ];
    //     $data['gettahun'] = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $wheretahun, $order_by)->result_array();
    //     //RT 4EVER
    //     $wher4ever = [
    //         'tbl_afkrekap.jeniswaktu' => '4ever',
    //         'tbl_afkcat.io' => 'o'
    //     ];
    //     $data['get4ever'] = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $wher4ever, $order_by)->result_array();

    //     //============================================================================
    //     // RT Harian
    //     //OUT
    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'harian',
    //         'tbl_afkrekap.waktu'      => $waktuhari,
    //         'tbl_afkcat.io'           => 'o'
    //     ];

    //     $column = '
    //         tbl_afkrekap.jenis as jenis,
    //         tbl_afkrekap.nilai as nilai,
    //         tbl_afkcat.deskripsi as deskripsi,
    //         tbl_afkcat.cat as cat
    //     ';

    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';

    //     $data['gethari'] = $this->sql
    //         ->select_table_join(
    //             'tbl_afkrekap',
    //             $column,
    //             'tbl_afkcat',
    //             'tbl_afkcat.id = tbl_afkrekap.jenis',
    //             'LEFT',
    //             $where,
    //             $order_by
    //         )
    //         ->result_array();

    //     $a = 0;
    //     foreach ($data['gethari'] as $row) {
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float) $row['nilai'];
    //         }
    //     }
    //     $data['OUThari'] = $a;

    //     //IN
    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'harian',
    //         'tbl_afkrekap.waktu'      => $waktuhari,
    //         'tbl_afkcat.io'           => 'i'
    //     ];

    //     $column = '
    //         tbl_afkrekap.nilai as nilai,
    //         tbl_afkcat.deskripsi as deskripsi,
    //         tbl_afkcat.cat as cat
    //     ';

    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';

    //     $query = $this->sql
    //         ->select_table_join(
    //             'tbl_afkrekap',
    //             $column,
    //             'tbl_afkcat',
    //             'tbl_afkcat.id = tbl_afkrekap.jenis',
    //             'LEFT',
    //             $where,
    //             $order_by
    //         )
    //         ->result_array();

    //     $a = 0;
    //     foreach ($query as $row) {
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float) $row['nilai'];
    //         }
    //     }
    //     $data['INhari'] = $a;


    //     $data['SALDOhari'] = $data['INhari'] + $data['OUThari'];

    //     //============================================================================
    //     // Tab Bulan
    //     //OUT
    //     $a = 0;
    //     foreach ($data['get'] as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['OUTbulan'] = $a;
    //     //IN
    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'bulanan',
    //         'tbl_afkrekap.waktu' => $waktu,
    //         'tbl_afkcat.io' => 'i'
    //     ];
    //     $column = 'tbl_afkrekap.nilai as nilai,
    //     tbl_afkcat.deskripsi as deskripsi,
    //     tbl_afkcat.cat as cat';
    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';
    //     $query = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $where, $order_by)->result_array();

    //     $a = 0;
    //     foreach ($query as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['INbulan'] = $a;

    //     $data['SALDObulan'] = $data['INbulan'] + $data['OUTbulan'];

    //     // Tab Tahun
    //     //OUT
    //     $a = 0;
    //     foreach ($data['gettahun'] as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['OUTtahun'] = $a;
    //     //IN
    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'tahunan',
    //         'tbl_afkrekap.waktu' => $waktutahun,
    //         'tbl_afkcat.io' => 'i'
    //     ];
    //     $column = 'tbl_afkrekap.nilai as nilai,
    //     tbl_afkcat.deskripsi as deskripsi,
    //     tbl_afkcat.cat as cat';
    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';
    //     $query = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $where, $order_by)->result_array();

    //     $a = 0;
    //     foreach ($query as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['INtahun'] = $a;

    //     $data['SALDOtahun'] = $data['INtahun'] + $data['OUTtahun'];

    //     //TAB 4EVER
    //     //OUT
    //     $a1 = 0;
    //     foreach ($data['get4ever'] as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a1 += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['OUT4ever'] = $a1;
    //     //IN
    //     $where = [
    //         'tbl_afkrekap.jeniswaktu' => 'tahunan',
    //         'tbl_afkcat.io' => 'i'
    //     ];
    //     $column = 'tbl_afkrekap.nilai as nilai,
    //         tbl_afkcat.deskripsi as deskripsi,
    //         tbl_afkcat.cat as cat';
    //     $order_by = 'CONVERT(nilai, SIGNED) ASC';
    //     $query = $this->sql->select_table_join('tbl_afkrekap', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis', 'LEFT', $where, $order_by)->result_array();

    //     $a2 = 0;
    //     foreach ($query as $row) :
    //         if (isset($row['nilai']) && is_numeric($row['nilai'])) {
    //             $a2 += (float)$row['nilai']; // Konversi ke angka sebelum dijumlahkan
    //         }
    //     endforeach;
    //     $data['IN4ever'] = $a2;

    //     $data['SALDO4ever'] = $data['IN4ever'] + $data['OUT4ever'];

    //     $data['judul'] = "Wawasan";
    //     $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
    //     $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();
    //     $data['subview'] = "afk/insight";
    //     $this->load->view('partial', $data);
    // }

    public function insightview()
    {
        $data['tahun'] = $this->input->post('tahun');
        $data['bulan'] = $this->input->post('bulan');
        $data['tanggal'] = $this->input->post('tanggal');
        $data['jeniswaktu'] = $this->input->post('jeniswaktu');
        $data['jenis'] = $this->input->post('jenis');

        $column = 'tbl_afkmain.*,
        tbl_afkcat.deskripsi as tbl_afkcatdeskripsi,
        tbl_afkcat.io as tbl_afkcatio,
        tbl_afkcat.cat as tbl_afkcatcat';


        $where = [
            // 'YEAR(tbl_afkmain.tanggal)' => $this->input->post('tahun')
        ];
        if ($this->input->post('jenis') == "pemasukan") {
            $where += ['tbl_afkcat.desk2' => "Pemasukan"];
        } else {
            $where += ['tbl_afkmain.catid' => $this->input->post('jenis')];
        }
        if ($data['jeniswaktu'] == 'bulanan') {
            $where += ['MONTH(tbl_afkmain.tanggal)' => $this->input->post('bulan')];
            $where += ['YEAR(tbl_afkmain.tanggal)' => $this->input->post('tahun')];
        } else if ($data['jeniswaktu'] == 'tahunan') {
            $where += ['YEAR(tbl_afkmain.tanggal)' => $this->input->post('tahun')];
        } else if ($data['jeniswaktu'] == '4ever') {
        } else if ($data['jeniswaktu'] == 'harian') {
            $where += ['tbl_afkmain.tanggal' => $this->input->post('tahun') . '-' . $this->input->post('bulan') . '-' . $this->input->post('tanggal')];
        }

        $data['get'] = $this->sql->select_table_join('tbl_afkmain', $column, 'tbl_afkcat', 'tbl_afkcat.id=tbl_afkmain.catid', 'LEFT', $where)->result_array();
        // var_dump($data['get']);
        // die;
        $this->load->view('afk/insightview', $data);
    }

    public function grafik()
    {
        $data['judul'] = 'Grafik';

        $data['getmenu'] = $this->sql->select_table(
            'tbl_devmenuaf',
            ['status' => '1', 'jenis' => 'menu'],
            'urutan ASC'
        )->result_array();

        $data['getsubmenu'] = $this->sql->select_table(
            'tbl_devmenuaf',
            ['status' => '1', 'jenis' => 'submenu'],
            'urutan ASC'
        )->result_array();

        $data['subview'] = 'afk/grafik';
        $this->load->view('partial', $data);
    }


    // public function grafik_data($scale = 'bulanan')
    // {
    //     // ==========================
    //     // VALIDASI SCALE
    //     // ==========================
    //     $allowed = ['harian', 'bulanan', 'tahunan'];
    //     if (!in_array($scale, $allowed)) {
    //         show_error('Scale tidak valid');
    //     }

    //     // ==========================
    //     // RANGE WAKTU
    //     // ==========================
    //     $start = '2023-12-01';
    //     $end   = date('Y-m-d');

    //     // ==========================
    //     // KONFIGURASI PER SCALE
    //     // ==========================
    //     switch ($scale) {
    //         case 'harian':
    //             $selectWaktu = "DATE(r.waktu)";
    //             $labelAlias  = 'label';
    //             $groupBy     = "DATE(r.waktu)";
    //             break;

    //         case 'tahunan':
    //             $selectWaktu = "YEAR(r.waktu)";
    //             $labelAlias  = 'label';
    //             $groupBy     = "YEAR(r.waktu)";
    //             break;

    //         default: // bulanan
    //             $selectWaktu = "DATE_FORMAT(r.waktu,'%Y-%m')";
    //             $labelAlias  = 'label';
    //             $groupBy     = "YEAR(r.waktu), MONTH(r.waktu)";
    //     }

    //     // ==========================
    //     // BASE QUERY
    //     // ==========================
    //     $base = $this->db
    //         ->select("
    //         {$selectWaktu} AS {$labelAlias},
    //         SUM(CAST(r.nilai AS SIGNED)) AS total
    //     ")
    //         ->from('tbl_afkrekap r')
    //         ->join('tbl_afkcat c', 'c.id = r.jenis')
    //         ->where('r.jeniswaktu', $scale)
    //         ->where('c.visibility', 1)
    //         ->where('r.waktu >=', $start)
    //         ->where('r.waktu <=', $end)
    //         ->group_by($groupBy)
    //         ->order_by($groupBy, 'ASC');

    //     // ==========================
    //     // DATA IN & OUT
    //     // ==========================
    //     $in  = (clone $base)->where('c.io', 'i')->get()->result_array();
    //     $out = (clone $base)->where('c.io', 'o')->get()->result_array();

    //     // ==========================
    //     // NORMALISASI LABEL
    //     // ==========================
    //     $labels = [];
    //     $mapIn  = [];
    //     $mapOut = [];

    //     foreach ($in as $r) {
    //         $mapIn[$r['label']] = (float)$r['total'];
    //         $labels[] = $r['label'];
    //     }

    //     foreach ($out as $r) {
    //         $mapOut[$r['label']] = abs((float)$r['total']);
    //         if (!in_array($r['label'], $labels)) {
    //             $labels[] = $r['label'];
    //         }
    //     }

    //     sort($labels);

    //     // ==========================
    //     // DATASET
    //     // ==========================
    //     $dataIn = $dataOut = $dataSaldo = $dataAkumulasi = [];
    //     $running = 0;

    //     foreach ($labels as $label) {
    //         $inVal  = $mapIn[$label]  ?? 0;
    //         $outVal = $mapOut[$label] ?? 0;
    //         $saldo  = $inVal - $outVal;

    //         $running += $saldo;

    //         $dataIn[]         = $inVal;
    //         $dataOut[]        = $outVal;
    //         $dataSaldo[]      = $saldo;
    //         $dataAkumulasi[]  = $running;
    //     }

    //     // ==========================
    //     // RESPONSE JSON
    //     // ==========================
    //     $this->output
    //         ->set_content_type('application/json')
    //         ->set_output(json_encode([
    //             'scale'      => $scale,
    //             'labels'     => $labels,
    //             'in'         => $dataIn,
    //             'out'        => $dataOut,
    //             'saldo'      => $dataSaldo,
    //             'akumulasi'  => $dataAkumulasi
    //         ]));
    // }

    public function grafik_data($scale = 'bulanan')
    {
        // ==========================
        // VALIDASI SCALE
        // ==========================
        $allowed = ['harian', 'bulanan', 'tahunan'];
        if (!in_array($scale, $allowed)) {
            show_error('Scale tidak valid');
        }

        $start = '2023-12-01'; // sistem mulai
        $end   = date('Y-m-d');

        // ==========================
        // 1️⃣ DATA HARIAN (SOURCE OF TRUTH)
        // ==========================
        $dailyBase = $this->db
            ->select("
            DATE(r.waktu) AS label,
            SUM(CAST(r.nilai AS SIGNED)) AS total
        ")
            ->from('tbl_afkrekap r')
            ->join('tbl_afkcat c', 'c.id = r.jenis')
            ->where('r.jeniswaktu', 'harian')
            ->where('c.visibility', 1)
            ->where('r.waktu >=', $start)
            ->where('r.waktu <=', $end)
            ->group_by('DATE(r.waktu)')
            ->order_by('DATE(r.waktu)', 'ASC');

        $inDaily  = (clone $dailyBase)->where('c.io', 'i')->get()->result_array();
        $outDaily = (clone $dailyBase)->where('c.io', 'o')->get()->result_array();

        // ==========================
        // MAP HARIAN
        // ==========================
        $mapIn = $mapOut = [];
        $labelsDaily = [];

        foreach ($inDaily as $r) {
            $mapIn[$r['label']] = (float)$r['total'];
            $labelsDaily[] = $r['label'];
        }

        foreach ($outDaily as $r) {
            $mapOut[$r['label']] = abs((float)$r['total']);
            if (!in_array($r['label'], $labelsDaily)) {
                $labelsDaily[] = $r['label'];
            }
        }

        sort($labelsDaily);

        // ==========================
        // SALDO & AKUMULASI (DOMPET)
        // ==========================
        $dailySaldo = [];
        $dailyAkumulasi = [];
        $running = 0;

        foreach ($labelsDaily as $tgl) {
            $in  = $mapIn[$tgl]  ?? 0;
            $out = $mapOut[$tgl] ?? 0;

            $saldo = $in - $out;
            $running += $saldo;

            $dailySaldo[$tgl] = $saldo;
            $dailyAkumulasi[$tgl] = $running;
        }

        // ==========================
        // 2️⃣ DATA TAMPILAN (BULANAN / TAHUNAN)
        // ==========================
        switch ($scale) {
            case 'harian':
                $labels = $labelsDaily;
                break;

            case 'tahunan':
                $labels = array_unique(array_map(fn($d) => substr($d, 0, 4), $labelsDaily));
                break;

            default: // bulanan
                $labels = array_unique(array_map(fn($d) => substr($d, 0, 7), $labelsDaily));
        }

        sort($labels);

        $dataIn = $dataOut = $dataSaldo = $dataAkumulasi = [];

        foreach ($labels as $label) {

            // filter harian sesuai skala
            $matchedDates = array_filter($labelsDaily, function ($d) use ($label, $scale) {
                if ($scale === 'harian')  return $d === $label;
                if ($scale === 'tahunan') return substr($d, 0, 4) === $label;
                return substr($d, 0, 7) === $label; // bulanan
            });

            $in = $out = 0;
            foreach ($matchedDates as $d) {
                $in  += $mapIn[$d]  ?? 0;
                $out += $mapOut[$d] ?? 0;
            }

            $saldo = $in - $out;

            // ⬇️ AKUMULASI DIAMBIL DARI HARIAN (KUNCI)
            $lastDate = end($matchedDates);
            $akumulasi = $dailyAkumulasi[$lastDate] ?? 0;

            $dataIn[] = $in;
            $dataOut[] = $out;
            $dataSaldo[] = $saldo;
            $dataAkumulasi[] = $akumulasi;
        }

        // ==========================
        // RESPONSE
        // ==========================
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'scale'      => $scale,
                'labels'     => $labels,
                'in'         => $dataIn,
                'out'        => $dataOut,
                'saldo'      => $dataSaldo,
                'akumulasi'  => $dataAkumulasi
            ]));
    }

    public function grafik_akumulasi($scale = 'bulanan')
    {
        // ==========================
        // VALIDASI SCALE
        // ==========================
        $allowed = ['harian', 'bulanan', 'tahunan'];
        if (!in_array($scale, $allowed)) {
            show_error('Scale tidak valid');
        }

        // ==========================
        // RANGE WAKTU (SINGLE SOURCE)
        // ==========================
        $start = '2023-12-09';
        $end   = date('Y-m-d');

        // ==========================
        // KONFIGURASI PER SCALE
        // ==========================
        switch ($scale) {
            case 'harian':
                $periodeSelect = "DATE(m.tanggal)";
                $periodeGroup  = "DATE(tanggal)";
                break;

            case 'tahunan':
                $periodeSelect = "YEAR(m.tanggal)";
                $periodeGroup  = "YEAR(tanggal)";
                break;

            default: // bulanan
                $periodeSelect = "DATE_FORMAT(m.tanggal,'%Y-%m')";
                $periodeGroup  = "YEAR(tanggal), MONTH(tanggal)";
        }

        // ==========================
        // QUERY INTI (SALDO TERAKHIR PER PERIODE)
        // ==========================
        $sql = "
        SELECT
            {$periodeSelect} AS label,
            m.saldo
        FROM tbl_afkmain m
        INNER JOIN (
            SELECT
                MAX(id) AS last_id
            FROM tbl_afkmain
            WHERE tanggal BETWEEN ? AND ?
            GROUP BY {$periodeGroup}
        ) x ON x.last_id = m.id
        ORDER BY label ASC
    ";

        $rows = $this->db->query($sql, [$start, $end])->result_array();

        // ==========================
        // DATASET
        // ==========================
        $labels = [];
        $data   = [];

        foreach ($rows as $r) {
            $labels[] = $r['label'];
            $data[]   = (float) $r['saldo'];
        }

        // ==========================
        // RESPONSE JSON
        // ==========================
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'scale'     => $scale,
                'labels'    => $labels,
                'akumulasi' => $data
            ]));
    }

    public function donat()
    {
        $jeniswaktu = $this->input->post('jeniswaktu');

        $cache = $this->session->userdata('afk_insight_cache');

        if (!$cache) {
            show_error('Data insight belum tersedia');
        }

        switch ($jeniswaktu) {
            case 'harian':
                $rows  = $cache['gethari'];
                $judul = "Donat Pengeluaran Harian";
                break;

            case 'bulanan':
                $rows  = $cache['get'];
                $judul = "Donat Pengeluaran Bulanan";
                break;

            case 'tahunan':
                $rows  = $cache['gettahun'];
                $judul = "Donat Pengeluaran Tahunan";
                break;

            default: // 4ever
                $rows  = $cache['get4ever'];
                $judul = "Donat Pengeluaran 4EVER";
        }

        $this->load->view('afk/donat', [
            'rows'  => $rows,
            'judul' => $judul
        ]);
    }
}
