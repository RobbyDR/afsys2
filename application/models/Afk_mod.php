<?php

class Afk_mod extends CI_model
{
    public function afkgetalldatajurnal($sort = NULL)
    {
        $this->db->select('tbl_afkmain.*,
        tbl_afkcat.deskripsi as tbl_afkcatdeskripsi,
        tbl_afkcat.io as tbl_afkcatio,
        tbl_afkcat.cat as tbl_afkcatcat');

        $this->db->join('tbl_afkcat', 'tbl_afkcat.id=tbl_afkmain.catid');

        $bulanselect = $this->input->post('bulanselect');
        $tahunselect = $this->input->post('tahunselect');
        if (!$bulanselect && !$tahunselect) {
            $tahunselect = date("Y");
            $bulanselect = date("m");
        }
        $this->db->where('YEAR(tanggal)', $tahunselect);
        $this->db->where('MONTH(tanggal)', $bulanselect);

        if ($sort == 'ascid') $this->db->order_by('id', 'asc');
        else $this->db->order_by('id', 'desc');

        $this->db->from('tbl_afkmain');
        return $this->db->get();
    }

    public function afkgettanggal($status, $table)
    {
        if ($status == 'lama' || $status == 'baru') {
            if ($status == 'lama') {
                $this->db->select_min('tanggal', 'tanggalter');
            } else if ($status == 'baru') {
                $this->db->select_max('tanggal', 'tanggalter');
            }
            $this->db->from($table);
            return $this->db->get()->row()->tanggalter;
        }
    }

    public function afkgettanggal2($status, $pilihbulan, $pilihtahun)
    {
        if ($status == 'lama' || $status == 'baru') {
            if ($status == 'lama') {
                $this->db->select_min('waktu', 'tanggalter');
            } else if ($status == 'baru') {
                $this->db->select_max('waktu', 'tanggalter');
            }

            $where = [
                'YEAR(waktu)' => $pilihtahun,
                'MONTH(waktu)' => $pilihbulan,
                'jeniswaktu' => 'harian'
            ];
            $this->db->where($where)->from('tbl_afkrekap');
            return $this->db->get()->row()->tanggalter;
        }
    }

    public function afkgetbulan($status, $pilihtahun)
    {
        if ($status == 'lama' || $status == 'baru') {
            if ($status == 'lama') {
                $this->db->select_min('waktu', 'tanggalter');
            } else if ($status == 'baru') {
                $this->db->select_max('waktu', 'tanggalter');
            }

            $where = [
                'YEAR(waktu)' => $pilihtahun,
                'jeniswaktu' => 'bulanan'
            ];
            $this->db->where($where)->from('tbl_afkrekap');
            return $this->db->get()->row()->tanggalter;
        }
    }

    public function afkgetallcatdata($sort = null, $visibility = null)
    {
        $this->db->select('*');
        $this->db->from('tbl_afkcat');
        if ($sort == null) $this->db->order_by('id', 'asc');
        else if ($sort == 'ascdeskripsi') $this->db->order_by('deskripsi', 'asc');
        else if ($sort == 'descvisibility') $this->db->order_by('visibility', 'desc');
        if ($visibility) $this->db->where('visibility', $visibility);
        return $this->db->get();
    }

    public function afkjurnalgetlastsaldo()
    {
        return $this->db
            ->order_by('id', 'DESC')
            ->from('tbl_afkmain')
            ->get()
            ->row_array()['saldo'];
    }

    public function afkjurnalgetio($id)
    {
        return $this->db->get_where('tbl_afkcat', ['id' => $id])->row_array()['io'];
    }

    public function afkjurnalgetdeskripsi($id)
    {
        return $this->db->get_where('tbl_afkcat', ['id' => $id])->row_array()['deskripsi'];
    }

    public function afkjurnaltambah()
    {
        $catid = $this->input->post('catid', true);
        $status = $this->afkjurnalgetio($catid);
        if ($status == "o") {
            $saku = -1 * abs($this->input->post('saku', true));
            $bni = -1 * abs($this->input->post('bni', true));
            $bnik = -1 * abs($this->input->post('bnik', true));
        } else if ($status == "i") {
            $saku = abs($this->input->post('saku', true));
            $bni = abs($this->input->post('bni', true));
            $bnik = abs($this->input->post('bnik', true));
        } else {
            $saku = $this->input->post('saku', true);
            $bni = $this->input->post('bni', true);
            $bnik = $this->input->post('bnik', true);
        }
        $data = [
            "tanggal" => $this->input->post('tanggal', true),
            "catid" => $catid,
            "deskripsi" => $this->input->post('deskripsi', true),
            "deskripsi2" => $this->input->post('deskripsi2', true),
            "saku" => $saku,
            "bni" => $bni,
            "bnik" => $bnik,
            "saldo" => $this->input->post('saldo', true)
        ];

        $this->db->insert('tbl_afkmain', $data);

        //update visibility
        $this->db->where('id', $catid);
        $this->db->update('tbl_afkcat', ["visibility" => '1']);

        // set cookie data untuk kenyamanan saat input data berikutnya
        $cookie_tanggalafkjurnal = [ //tanggal
            'name'   => 'tanggalafkjurnal',
            'value'  => $data['tanggal'],
            'expire' => '3600',
            'domain' => '',
            'path'   => '/',
            'secure' => FALSE,
            'httponly' => TRUE
        ];
        set_cookie($cookie_tanggalafkjurnal);

        $cookie_catid = [ //catid
            'name'   => 'catid',
            'value'  => $data['catid'],
            'expire' => '3600',
            'domain' => '',
            'path'   => '/',
            'secure' => FALSE,
            'httponly' => TRUE
        ];
        set_cookie($cookie_catid);
    }

    public function afkjurnalgetdatabyid($id)
    {
        return $this->db->get_where('tbl_afkmain', ['id' => $id])->row_array();
    }

    public function afkjurnaledit($id)
    {
        $catid = $this->input->post('catid', true);
        // $status = $this->afkjurnalgetio($catid);
        // if ($status == "o") {
        //     $saku = -1 * abs($this->input->post('saku', true));
        //     $bni = -1 * abs($this->input->post('bni', true));
        //     $bnik = -1 * abs($this->input->post('bnik', true));
        // } else if ($status == "i") {
        //     $saku = abs($this->input->post('saku', true));
        //     $bni = abs($this->input->post('bni', true));
        //     $bnik = abs($this->input->post('bnik', true));
        // } else {
        $saku = $this->input->post('saku', true);
        $bni = $this->input->post('bni', true);
        $bnik = $this->input->post('bnik', true);
        // }
        $data = [
            "tanggal" => $this->input->post('tanggal', true),
            "catid" => $catid,
            "deskripsi" => $this->input->post('deskripsi', true),
            "deskripsi2" => $this->input->post('deskripsi2', true),
            "saku" => $saku,
            "bni" => $bni,
            "bnik" => $bnik,
            "saldo" => $this->input->post('saldo', true)
        ];

        $this->db->where('id', $id);
        $this->db->update('tbl_afkmain', $data);
    }

    public function afkrefreshallsaldo()
    {
        //query dulu
        $query = $this->db->select('*')->from('tbl_afkmain')->order_by('id', 'asc')->get()->result_array();

        //foreach
        $saldo = 0;
        foreach ($query as $row) {
            // var_dump($row);
            // echo '<br>';
            $saldo = $saldo + (int)$row['saku'] + (int)$row['bni'] + (int)$row['bnik'];

            //update ke tabel
            $data = [
                "saldo" => $saldo
            ];

            $this->db->where('id', $row['id']);
            $this->db->update('tbl_afkmain', $data);
        }
        // die;
    }

    public function afkrefreshallsaldobulanan($bulan, $tahun)
    {
        //query dulu
        $query = $this->db->select('*')->from('tbl_afkmain')->where(['MONTH(tanggal)' => $bulan, 'YEAR(tanggal)' => $tahun])->order_by('id', 'asc')->get()->result_array();

        //foreach
        $saldo = 0;
        foreach ($query as $row) {
            // var_dump($row);
            // echo '<br>';
            $saldo = $saldo + (int)$row['saku'] + (int)$row['bni'] + (int)$row['bnik'];

            //update ke tabel
            $data = [
                "saldo" => $saldo
            ];

            $this->db->where('id', $row['id']);
            $this->db->update('tbl_afkmain', $data);
        }
        // die;
    }

    //=======REKAP
    // ADA YANG FAST, INI TIDAK DIPAKAI LAGI
    // public function afkupdaterekap($catid)
    // {
    //     //tentukan jenisnya

    //     //cari tanggal terlama
    //     $this->db->select_min('tanggal', 'tanggalterlama');
    //     $this->db->from('tbl_afkmain');
    //     $query_min = $this->db->get();
    //     $result_min = $query_min->row();
    //     $tglterlama = $result_min->tanggalterlama;

    //     //cari tanggal terbaru
    //     $this->db->select_max('tanggal', 'tanggalterbaru');
    //     $this->db->from('tbl_afkmain');
    //     $query_max = $this->db->get();
    //     $result_max = $query_max->row();
    //     $tglterbaru = $result_max->tanggalterbaru;

    //     // var_dump($tglterbaru);
    //     // die;

    //     //FUNGSI HARIAN
    //     //looping harian
    //     for ($waktu = strtotime($tglterlama); $waktu <= strtotime($tglterbaru) + 86400; $waktu += 86400) {
    //         $tanggal = date("Y-m-d", $waktu);
    //         // echo ($tanggal . ' ');
    //         // echo ($catid . ' ');
    //         $querytbl_afkmain = $this->db->select('saku,bni,bnik')->from('tbl_afkmain')->where(['tanggal' => $tanggal, 'catid' => $catid])->get()->result_array();
    //         $value = 0;
    //         foreach ($querytbl_afkmain as $row) {
    //             // if ($catid == "omset") $value += $row['hj'];
    //             // else if ($catid == "laba") $value += $row['laba'];
    //             $value += $row['saku'] + $row['bni'] + $row['bnik'];
    //         }
    //         // cek pada tanggal tersebut ada jenis, tipewaktu dan waktu yang dimaksud
    //         $where = [
    //             'jenis' => $catid,
    //             'jeniswaktu' => 'harian',
    //             'waktu' => $tanggal
    //         ];
    //         $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
    //         if ($querytbl_afkrekap) {
    //             // jika ada, update

    //             $data = [
    //                 "nilai" => $value
    //             ];

    //             $this->db->where('id', $querytbl_afkrekap['id']);
    //             $this->db->update('tbl_afkrekap', $data);
    //         } else {
    //             // jika tidak ada dan nilai tidaksama dengan 0, insert
    //             if ($value != 0) {
    //                 $data = [
    //                     "jenis" => $catid,
    //                     "jeniswaktu" => "harian",
    //                     "waktu" => $tanggal,
    //                     "nilai" => $value
    //                 ];

    //                 $this->db->insert('tbl_afkrekap', $data);
    //                 // echo ($value);
    //             }
    //         }

    //         // echo '<br>';
    //     }


    //     // FUNGSI BULANAN
    //     // analisa $tglterlama dan terbaru
    //     $tglsatuterlamaDate = new DateTime($tglterlama);
    //     $tglsatuterlamaDate->modify('first day of this month');
    //     $tglsatuterbaruDate = new DateTime($tglterbaru);
    //     $tglsatuterbaruDate->modify('first day of this month');

    //     //looping bulanan
    //     while ($tglsatuterlamaDate <= $tglsatuterbaruDate) {
    //         // Lakukan sesuatu dengan tanggal saat ini
    //         $where = [
    //             'jenis' => $catid,
    //             'jeniswaktu' => 'harian',
    //             'YEAR(waktu)' => $tglsatuterlamaDate->format('Y'),
    //             'MONTH(waktu)' => $tglsatuterlamaDate->format('m')
    //         ];
    //         $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->result_array();

    //         //lakukan penjumlahan harga jual
    //         $value1 = 0;
    //         foreach ($querytbl_afkrekap as $row) {
    //             $value1 += $row['nilai'];
    //         }
    //         $bulan1 = $tglsatuterlamaDate->format('Y-m-d');

    //         //lakukan insert/update jika tidak ada atau ada datanya di tbl_afkrekap
    //         $where = [
    //             'jenis' => $catid,
    //             'jeniswaktu' => 'bulanan',
    //             'waktu' => $bulan1
    //         ];
    //         $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
    //         if ($querytbl_afkrekap) {
    //             // jika ada, update

    //             $data = [
    //                 "nilai" => $value1
    //             ];

    //             $this->db->where('id', $querytbl_afkrekap['id']);
    //             $this->db->update('tbl_afkrekap', $data);
    //         } else {
    //             // jika tidak ada dan value tidaksama dengan 0, insert
    //             if ($value1 != 0) {
    //                 $data = [
    //                     "jenis" => $catid,
    //                     "jeniswaktu" => "bulanan",
    //                     "waktu" => $bulan1,
    //                     "nilai" => $value1
    //                 ];

    //                 $this->db->insert('tbl_afkrekap', $data);
    //             }
    //         }

    //         // Tambahkan satu bulan ke tanggal saat ini
    //         $tglsatuterlamaDate->add(new DateInterval('P1M'));
    //     }
    //     // die;

    //     //FUNGSI TAHUNAN
    //     // cek dan edit $tglterlama dan terbaru menjad 1/1/yyyy
    //     $tglsatublnsatuterlamaDate = new DateTime($tglterlama);
    //     $tglsatublnsatuterlamaDate->setDate($tglsatublnsatuterlamaDate->format('Y'), 1, 1);
    //     $tglsatublnsatuterbaruDate = new DateTime($tglterbaru);
    //     $tglsatublnsatuterbaruDate->setDate($tglsatublnsatuterbaruDate->format('Y'), 1, 1);
    //     $tahunterlama = $tglsatublnsatuterlamaDate->format('Y');
    //     $tahunterbaru = $tglsatublnsatuterbaruDate->format('Y');
    //     // var_dump($tahunterbaru);
    //     // die;
    //     //looping tahunan
    //     for ($tahun = $tahunterlama; $tahun <= $tahunterbaru; $tahun++) {
    //         // query dari tbl_afkrekap
    //         $where = [
    //             'jenis' => $catid,
    //             'jeniswaktu' => 'bulanan',
    //             'YEAR(waktu)' => $tahun
    //         ];
    //         $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->result_array();

    //         //akumulasikan
    //         $value2 = 0;
    //         foreach ($querytbl_afkrekap as $row) {
    //             $value2 += $row['nilai'];
    //         }

    //         //lakukan insert/update jika tidak ada atau ada datanya di tbl_afkrekap
    //         $tanggal2 = new DateTime($tahun . '-01-01');
    //         $tanggal2a = $tanggal2->format('Y-m-d');
    //         $where = [
    //             'jenis' => $catid,
    //             'jeniswaktu' => 'tahunan',
    //             'waktu' => $tanggal2a
    //         ];

    //         $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
    //         if ($querytbl_afkrekap) {
    //             // jika ada, update

    //             $data = [
    //                 "nilai" => $value2
    //             ];

    //             $this->db->where('id', $querytbl_afkrekap['id']);
    //             $this->db->update('tbl_afkrekap', $data);
    //         } else {
    //             // jika tidak ada, insert
    //             if ($value2 != 0) {
    //                 $data = [
    //                     "jenis" => $catid,
    //                     "jeniswaktu" => "tahunan",
    //                     "waktu" => $tanggal2a,
    //                     "nilai" => $value2
    //                 ];

    //                 $this->db->insert('tbl_afkrekap', $data);

    //                 //ubah status visibility catid di tbl_afkcat menjadi 1
    //                 $this->db->where('id', $catid);
    //                 $this->db->update('tbl_afkcat', ["visibility" => "1"]);
    //             }
    //         }
    //     }
    // }

    public function afkupdaterekapfast($catid)
    {
        // === 1️⃣ Harian ===
        $sql_harian = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'harian' AS jeniswaktu,
            tanggal AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        WHERE catid = ?
        GROUP BY tanggal, catid
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)
    ";
        $this->db->query($sql_harian, [$catid]);

        // === 2️⃣ Bulanan ===
        $sql_bulanan = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'bulanan' AS jeniswaktu,
            DATE_FORMAT(tanggal, '%Y-%m-01') AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        WHERE catid = ?
        GROUP BY YEAR(tanggal), MONTH(tanggal), catid
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)
    ";
        $this->db->query($sql_bulanan, [$catid]);

        // === 3️⃣ Tahunan ===
        $sql_tahunan = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'tahunan' AS jeniswaktu,
            DATE_FORMAT(tanggal, '%Y-01-01') AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        WHERE catid = ?
        GROUP BY YEAR(tanggal), catid
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)
    ";
        $this->db->query($sql_tahunan, [$catid]);

        // === 4️⃣ 4EVER ===
        // total dari seluruh data (akumulatif sepanjang masa)
        $sql_4ever = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT
            catid AS jenis,
            '4ever' AS jeniswaktu,
            MIN(DATE_FORMAT(tanggal, '%Y-01-01')) AS waktu,  -- waktu awal data pertama
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        WHERE catid = ?
        GROUP BY catid
        ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)
    ";
        $this->db->query($sql_4ever, [$catid]);
    }



    public function afkupdaterekapbulanan($catid, $tanggalparam, $range = null)
    {
        $tglterlama1 = new DateTime($tanggalparam);
        if (!$range) { //tanggal terlama adalah 1
            $tglterlama1->modify('first day of this month');
        } else {
            // $tglterlama1->modify('-{$range} days');
            $tglterlama1->modify('-7 days');
        }
        $tglterlama = $tglterlama1->format('Y-m-d');

        //tanggal terbaru adalah $tanggalparam
        $tglterbaru = $tanggalparam;

        //FUNGSI HARIAN
        //looping harian
        for ($waktu = strtotime($tglterlama); $waktu <= strtotime($tglterbaru); $waktu += 86400) {
            $tanggal = date("Y-m-d", $waktu);
            $querytbl_afkmain = $this->db->select('saku,bni,bnik')->from('tbl_afkmain')->where(['tanggal' => $tanggal, 'catid' => $catid])->get()->result_array();
            $value = 0;
            foreach ($querytbl_afkmain as $row) {
                // if ($catid == "omset") $value += $row['hj'];
                // else if ($catid == "laba") $value += $row['laba'];
                $value += $row['saku'] + $row['bni'] + $row['bnik'];
            }
            // cek pada tanggal tersebut ada jenis, tipewaktu dan waktu yang dimaksud
            $where = [
                'jenis' => $catid,
                'jeniswaktu' => 'harian',
                'waktu' => $tanggal
            ];
            $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
            if ($querytbl_afkrekap) {
                // jika ada, update

                $data = [
                    "nilai" => $value
                ];

                $this->db->where('id', $querytbl_afkrekap['id']);
                $this->db->update('tbl_afkrekap', $data);
            } else {
                // jika tidak ada dan nilai tidaksama dengan 0, insert
                if ($value != 0) {
                    $data = [
                        "jenis" => $catid,
                        "jeniswaktu" => "harian",
                        "waktu" => $tanggal,
                        "nilai" => $value
                    ];

                    $this->db->insert('tbl_afkrekap', $data);
                }
            }
        }

        // FUNGSI BULANAN
        // analisa $tglterlama dan terbaru
        $tglsatuterlamaDate = new DateTime($tglterlama);
        $tglsatuterlamaDate->modify('first day of this month');
        $tglsatuterbaruDate = new DateTime($tglterbaru);
        $tglsatuterbaruDate->modify('first day of this month');

        //looping bulanan
        while ($tglsatuterlamaDate <= $tglsatuterbaruDate) {
            // Lakukan sesuatu dengan tanggal saat ini
            $where = [
                'jenis' => $catid,
                'jeniswaktu' => 'harian',
                'YEAR(waktu)' => $tglsatuterlamaDate->format('Y'),
                'MONTH(waktu)' => $tglsatuterlamaDate->format('m')
            ];
            $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->result_array();

            //lakukan penjumlahan nilai
            $value1 = 0;
            foreach ($querytbl_afkrekap as $row) {
                $value1 += $row['nilai'];
            }
            $bulan1 = $tglsatuterlamaDate->format('Y-m-d');

            //lakukan insert/update jika tidak ada atau ada datanya di tbl_afkrekap
            $where = [
                'jenis' => $catid,
                'jeniswaktu' => 'bulanan',
                'waktu' => $bulan1
            ];
            $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
            if ($querytbl_afkrekap) {
                // jika ada, update

                $data = [
                    "nilai" => $value1
                ];

                $this->db->where('id', $querytbl_afkrekap['id']);
                $this->db->update('tbl_afkrekap', $data);
            } else {
                // jika tidak ada dan value tidaksama dengan 0, insert
                if ($value1 != 0) {
                    $data = [
                        "jenis" => $catid,
                        "jeniswaktu" => "bulanan",
                        "waktu" => $bulan1,
                        "nilai" => $value1
                    ];

                    $this->db->insert('tbl_afkrekap', $data);
                }
            }

            // Tambahkan satu bulan ke tanggal saat ini
            $tglsatuterlamaDate->add(new DateInterval('P1M'));
        }
        // die;

        //FUNGSI TAHUNAN
        // cek dan edit $tglterlama dan terbaru menjad 1/1/yyyy
        $tglsatublnsatuterlamaDate = new DateTime($tglterlama);
        $tglsatublnsatuterlamaDate->setDate($tglsatublnsatuterlamaDate->format('Y'), 1, 1);
        $tglsatublnsatuterbaruDate = new DateTime($tglterbaru);
        $tglsatublnsatuterbaruDate->setDate($tglsatublnsatuterbaruDate->format('Y'), 1, 1);
        $tahunterlama = $tglsatublnsatuterlamaDate->format('Y');
        $tahunterbaru = $tglsatublnsatuterbaruDate->format('Y');
        // var_dump($tahunterbaru);
        // die;
        //looping tahunan
        for ($tahun = $tahunterlama; $tahun <= $tahunterbaru; $tahun++) {
            // query dari tbl_afkrekap
            $where = [
                'jenis' => $catid,
                'jeniswaktu' => 'bulanan',
                'YEAR(waktu)' => $tahun
            ];
            $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->result_array();

            //akumulasikan
            $value2 = 0;
            foreach ($querytbl_afkrekap as $row) {
                $value2 += $row['nilai'];
            }

            //lakukan insert/update jika tidak ada atau ada datanya di tbl_afkrekap
            $tanggal2 = new DateTime($tahun . '-01-01');
            $tanggal2a = $tanggal2->format('Y-m-d');
            $where = [
                'jenis' => $catid,
                'jeniswaktu' => 'tahunan',
                'waktu' =>  $tanggal2a
            ];

            $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
            if ($querytbl_afkrekap) {
                // jika ada, update

                $data = [
                    "nilai" => $value2
                ];

                $this->db->where('id', $querytbl_afkrekap['id']);
                $this->db->update('tbl_afkrekap', $data);
            } else {
                // jika tidak ada, insert
                if ($value2 != 0) {
                    $data = [
                        "jenis" => $catid,
                        "jeniswaktu" => "tahunan",
                        "waktu" => $tanggal2a,
                        "nilai" => $value2
                    ];

                    $this->db->insert('tbl_afkrekap', $data);
                }
            }
        }

        // UPDATE 4EVER
        $where = [
            'jenis' => $catid,
            'jeniswaktu' => 'tahunan'
        ];
        $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->result_array();

        $value3 = 0;
        foreach ($querytbl_afkrekap as $row) :
            $value3 += $row['nilai'];
        endforeach;

        //lakukan insert/update jika tidak ada atau ada datanya di tbl_afkrekap
        $tanggal3 = new DateTime($tahun . '-01-01');
        $tanggal3a = $tanggal3->format('Y-m-d');
        $where = [
            'jenis' => $catid,
            'jeniswaktu' => '4ever'
        ];

        $querytbl_afkrekap = $this->db->select('*')->from('tbl_afkrekap')->where($where)->get()->row_array();
        if ($querytbl_afkrekap) {
            // jika ada, update

            $data = [
                "nilai" => $value3
            ];

            $this->db->where('id', $querytbl_afkrekap['id']);
            $this->db->update('tbl_afkrekap', $data);
        } else {
            // jika tidak ada, insert
            if ($value3 != 0) {
                $data = [
                    "jenis" => $catid,
                    "jeniswaktu" => "4ever",
                    "waktu" => $tanggal3a,
                    "nilai" => $value3
                ];

                $this->db->insert('tbl_afkrekap', $data);
            }
        }
    }

    //====REKAP
    public function afkgetalldatarekap($jeniswaktu, $waktu = null)
    {
        $this->db->select('tbl_afkrekap.*,
        tbl_afkcat.deskripsi as tbl_afkcatdeskripsi,
        tbl_afkcat.io as tbl_afkcatio,
        tbl_afkcat.cat as tbl_afkcatcat');

        $this->db->join('tbl_afkcat', 'tbl_afkcat.id=tbl_afkrekap.jenis');

        // if ($jeniswaktu == 'harian') {
        // }
        // $bulanselect = $this->input->post('bulanselect');
        // $tahunselect = $this->input->post('tahunselect');
        // if (!$bulanselect && !$tahunselect) {
        //     $tahunselect = date("Y");
        //     $bulanselect = date("m");
        // }
        // $this->db->where('YEAR(tanggal)', $tahunselect);
        // $this->db->where('MONTH(tanggal)', $bulanselect);
        // $this->db->where('YEAR(waktu)', date('Y'));
        // $this->db->where('MONTH(waktu)', date('m'));
        // $this->db->where('jeniswaktu', $jeniswaktu);

        // if ($jeniswaktu == "harian") {
        //     $this->db->where('jeniswaktu', $jeniswaktu);
        //     $this->db->where('waktu', $waktu);
        // }
        if ($jeniswaktu == "harian") {
            $this->db->where('jeniswaktu', $jeniswaktu);
            if ($waktu) $this->db->where('waktu', $waktu);
        }

        // if ($sort == 'ascid') $this->db->order_by('id', 'asc');
        // else $this->db->order_by('id', 'desc');

        $this->db->from('tbl_afkrekap');
        return $this->db->get();
    }

    // public function afkgetdatatanggalselect()
    // {
    //     $this->db->select('*');

    //     // $bulanselect = $this->input->post('bulanselect');
    //     // $tahunselect = $this->input->post('tahunselect');
    //     // if (!$bulanselect && !$tahunselect) {
    //     //     $tahunselect = date("Y");
    //     //     $bulanselect = date("m");
    //     // }
    //     // $this->db->where('YEAR(tanggal)', $tahunselect);
    //     // $this->db->where('MONTH(tanggal)', $bulanselect);

    //     if ($sort == 'ascid') $this->db->order_by('id', 'asc');
    //     else $this->db->order_by('id', 'desc');

    //     $this->db->from('tbl_wbappob');
    //     return $this->db->get();
    // }

    public function afkgetdatarekapnilai($jenis, $jeniswaktu, $waktu)
    {
        $where = [
            'jenis' => $jenis,
            'jeniswaktu' => $jeniswaktu,
            'waktu' => $waktu
        ];
        return $this->db->select('nilai')->get_where('tbl_afkrekap', $where);
    }

    public function afkgetsaldoskr($entitas)
    {
        //saku, bni, bnik
        $query = $this->db->select($entitas)->from('tbl_afkmain')->get()->result_array();
        $saldo = 0;
        foreach ($query as $row) {
            $saldo += $row[$entitas];
        }

        return $saldo;
    }

    public function afkgetsaldo1($entitas)
    {
        //cari tanggal hari ini
        $tanggal = date('Y-m-d');
        //kurangi sebulan sebelumnya
        $tanggal1 = new DateTime($tanggal);
        $tanggal1->sub(new DateInterval('P1M'));
        //ambil bulan dan tahunnya
        $tahun = $tanggal1->format('Y');
        $bulan = $tanggal1->format('m');
        //query db sesuai bulan dan tahun dengan sort desc
        $where = ['MONTH(tanggal)' => $bulan, 'YEAR(tanggal)' => $tahun];
        $query = $this->db->select('*')
            ->from('tbl_afkmain')
            ->where($where)
            ->order_by('id', 'desc')
            ->get()->row_array();
        // ambil data pertama khususnya id
        $id = $query['id'];
        // var_dump($query);
        // die;
        $this->db->select_sum($entitas);
        $this->db->where('id <=', $id);
        $query = $this->db->get('tbl_afkmain')->result_array()[0][$entitas];
        // var_dump($query);
        // die;
        return $query;
    }

    public function afkgetdeltastate($dataskr, $datadulu)
    {
        $state = '';
        if ($dataskr < $datadulu) {
            $state = '
            <span class="badge bg-danger">
            <span data-feather="arrow-down" class="align-text-bottom"></span>
            </span>
            ';
        } else if ($dataskr > $datadulu) {
            $state = '
            <span class="badge bg-success">
            <span data-feather="arrow-up" class="align-text-bottom"></span>
            </span>
            ';
        } else {
            $state = '
            <span class="badge bg-info">
            <span data-feather="repeat" class="align-text-bottom"></span>
            </span>
            ';
        }

        return $state;
    }

    public function afkgetalldatajurnalbyid($tahunselect = null, $bulanselect = null, $catid = null, $sort = NULL)
    {
        $this->db->select('tbl_afkmain.*,
        tbl_afkcat.deskripsi as tbl_afkcatdeskripsi,
        tbl_afkcat.io as tbl_afkcatio,
        tbl_afkcat.cat as tbl_afkcatcat');

        $this->db->join('tbl_afkcat', 'tbl_afkcat.id=tbl_afkmain.catid');

        // $bulanselect = $this->input->post('bulanselect');
        // $tahunselect = $this->input->post('tahunselect');
        // if (!$bulanselect && !$tahunselect) {
        //     $tahunselect = date("Y");
        //     $bulanselect = date("m");
        // }
        if ($tahunselect) $this->db->where('YEAR(tanggal)', $tahunselect);
        if ($bulanselect) $this->db->where('MONTH(tanggal)', $bulanselect);
        $this->db->where('catid', $catid);

        if ($sort == 'ascid') $this->db->order_by('id', 'asc');
        else $this->db->order_by('id', 'desc');

        $this->db->from('tbl_afkmain');
        return $this->db->get();
    }
}
