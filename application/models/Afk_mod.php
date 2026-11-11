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

        // Urut menurut TANGGAL (bukan urutan input), lalu id untuk tanggal yang sama,
        // supaya transaksi sisipan bertanggal lampau tampil di posisi kronologisnya
        // dan kolom saldo terbaca runtut.
        if ($sort == 'ascid') {
            $this->db->order_by('tanggal', 'asc');
            $this->db->order_by('id', 'asc');
        } else {
            $this->db->order_by('tanggal', 'desc');
            $this->db->order_by('id', 'desc');
        }

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

    // AFK2: versi fix, tidak memicu "Undefined array key" saat tbl_afkmain masih kosong
    public function afkjurnalgetlastsaldo2()
    {
        // urut berdasarkan tanggal lalu id, supaya transaksi sisipan
        // (tanggal lampau, id besar) tidak dianggap sebagai transaksi terakhir
        $row = $this->db
            ->order_by('tanggal', 'DESC')
            ->order_by('id', 'DESC')
            ->from('tbl_afkmain')
            ->get()
            ->row_array();

        return $row['saldo'] ?? 0;
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

    // AFK2: versi fix & optimasi.
    // - Pakai 1 query UPDATE...JOIN dengan variable MySQL untuk hitung
    //   running total, GANTI loop PHP yang tadinya jalankan 1 query
    //   UPDATE per baris (bisa ribuan query kalau data sudah banyak).
    // - MySQL otomatis konversi kolom varchar ke angka (DOUBLE) saat
    //   dipakai operasi aritmatika, jadi nilai desimal tidak terpotong
    //   seperti pada (int) cast di versi lama.
    public function afkrefreshallsaldo2()
    {
        // Saldo berjalan semua akun, urut tanggal lalu id. Memakai window
        // function (urutannya dijamin), bukan variabel @running. Sumber data
        // = tbl_afkmain_detail, jadi akun baru otomatis ikut terhitung.
        $this->db->query("
            UPDATE tbl_afkmain t
            JOIN (
                SELECT m.id,
                       SUM(IFNULL(d.total, 0)) OVER (ORDER BY m.tanggal ASC, m.id ASC) AS saldo_baru
                FROM tbl_afkmain m
                LEFT JOIN (
                    SELECT main_id, SUM(nilai) AS total
                    FROM tbl_afkmain_detail
                    GROUP BY main_id
                ) d ON d.main_id = m.id
            ) x ON x.id = t.id
            SET t.saldo = x.saldo_baru
        ");
    }

    //=======REKAP
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



    // =====================================================
    // AFK2 - versi baru: generate ULANG SEMUA kategori sekaligus.
    // Dipakai SETELAH tbl_afkrekap di-TRUNCATE (lihat afkupdaterekap2()
    // di controller). Karena tabel kosong saat insert, tidak perlu
    // ON DUPLICATE KEY UPDATE lagi -> tidak butuh unique key khusus,
    // dan tidak ada resiko data dobel seperti afkupdaterekapfast().
    // =====================================================
    public function afkupdaterekapfast2()
    {
        // Sumber: tbl_afkmain + tbl_afkmain_detail (LEFT JOIN supaya transaksi
        // bernilai 0 tetap punya baris rekap, sama seperti versi lama).
        $nilai = $this->_sqlRapi('SUM(IFNULL(d.nilai, 0))');
        $from  = "FROM tbl_afkmain m LEFT JOIN tbl_afkmain_detail d ON d.main_id = m.id";

        // Harian
        $this->db->query("
            INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
            SELECT m.catid, 'harian', m.tanggal, $nilai
            $from
            GROUP BY m.tanggal, m.catid
        ");

        // Bulanan
        $this->db->query("
            INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
            SELECT m.catid, 'bulanan', DATE_FORMAT(m.tanggal, '%Y-%m-01'), $nilai
            $from
            GROUP BY YEAR(m.tanggal), MONTH(m.tanggal), m.catid
        ");

        // Tahunan
        $this->db->query("
            INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
            SELECT m.catid, 'tahunan', DATE_FORMAT(m.tanggal, '%Y-01-01'), $nilai
            $from
            GROUP BY YEAR(m.tanggal), m.catid
        ");

        // 4ever
        $this->db->query("
            INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
            SELECT m.catid, '4ever', MIN(DATE_FORMAT(m.tanggal, '%Y-01-01')), $nilai
            $from
            GROUP BY m.catid
        ");

        // visibility: reset lalu set 1 untuk kategori yang masih punya transaksi
        $this->db->query("UPDATE tbl_afkcat SET visibility = '0'");
        $this->db->query("
            UPDATE tbl_afkcat SET visibility = '1'
            WHERE id IN (SELECT DISTINCT catid FROM tbl_afkmain)
        ");
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

    // Total pemasukan (io='i') & pengeluaran (io='o') pada 1 tanggal tertentu,
    // dihitung LANGSUNG dari tbl_afkmain (bukan tbl_afkrekap yang baru
    // ter-update saat tombol refresh dipencet) supaya datanya selalu
    // real-time untuk kartu ringkasan di dashboard.
    // Catatan: nilai "out" hasilnya negatif (karena saku/bni/bnik untuk
    // kategori io='o' memang disimpan negatif sejak diinput).
    public function afkgettotalioharian($tanggal = null)
    {
        if ($tanggal === null) $tanggal = date('Y-m-d');
        return $this->afkgettotalioperiode($tanggal, $tanggal);
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

    // AFK2: saldo satu akun (saku/bni/bnik) pada AKHIR BULAN LALU.
    // Berbasis tanggal (SUM semua transaksi dengan tanggal <= hari terakhir
    // bulan lalu), bukan berbasis id, jadi transaksi sisipan bertanggal
    // lampau otomatis ikut terhitung. Kalau belum ada data, hasilnya 0.
    public function afkgetsaldo1_2($entitas)
    {
        $akhirBulanLalu = date('Y-m-t', strtotime('first day of last month'));
        return $this->afkgetsaldopertanggal($entitas, $akhirBulanLalu);
    }

    // Posisi (saldo) satu akun pada tanggal tertentu = jumlah semua transaksi
    // sampai dengan tanggal itu. Dasar untuk fitur "potret keuangan" nanti.
    public function afkgetsaldopertanggal($entitas, $tanggal)
    {
        if (!in_array($entitas, ['saku', 'bni', 'bnik'], true)) return 0;

        $row = $this->db->select_sum($entitas)
            ->where('tanggal <=', $tanggal)
            ->get('tbl_afkmain')->row_array();

        return (float) ($row[$entitas] ?? 0);
    }

    // Total pemasukan (io='i') & pengeluaran (io='o') dalam rentang tanggal
    // (inklusif), langsung dari tbl_afkmain. Kategori lain (mis. pindah uang)
    // sengaja tidak dihitung. Nilai "out" hasilnya negatif.
    public function afkgettotalioperiode($tglawal, $tglakhir)
    {
        $rows = $this->db
            ->select('tbl_afkcat.io AS io, SUM(tbl_afkmain_detail.nilai) AS total')
            ->from('tbl_afkmain')
            ->join('tbl_afkmain_detail', 'tbl_afkmain_detail.main_id = tbl_afkmain.id')
            ->join('tbl_afkcat', 'tbl_afkcat.id = tbl_afkmain.catid', 'left')
            ->where('tbl_afkmain.tanggal >=', $tglawal)
            ->where('tbl_afkmain.tanggal <=', $tglakhir)
            ->group_by('tbl_afkcat.io')
            ->get()->result_array();

        $in = 0;
        $out = 0;
        foreach ($rows as $row) {
            if ($row['io'] === 'i') $in = (float) $row['total'];
            elseif ($row['io'] === 'o') $out = (float) $row['total'];
        }
        return ['in' => $in, 'out' => $out];
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

    // =====================================================================
    // AFK2 TAHAP 2b - AKUN DINAMIS (tbl_afkakun + tbl_afkmain_detail)
    // Kolom lama saku/bni/bnik di tbl_afkmain tetap ikut diisi dari detail
    // ("jalur mundur") sampai tahap 4, supaya kode lama masih konsisten.
    // =====================================================================

    // Ekspresi SQL untuk merapikan angka DECIMAL jadi teks tanpa nol di belakang
    // koma (5000000.00 -> 5000000, 10.50 -> 10.5), sama seperti format data lama.
    private function _sqlRapi($expr)
    {
        return "IF(LOCATE('.', CAST($expr AS CHAR)) > 0,
                   TRIM(TRAILING '.' FROM TRIM(TRAILING '0' FROM CAST($expr AS CHAR))),
                   CAST($expr AS CHAR))";
    }

    // Daftar akun, urut sesuai 'urutan'. $hanyaAktif=true -> hanya yang aktif.
    public function afkgetakun2($hanyaAktif = false)
    {
        if ($hanyaAktif) $this->db->where('aktif', 1);
        return $this->db->order_by('urutan', 'ASC')->order_by('id', 'ASC')
            ->get('tbl_afkakun')->result_array();
    }

    // Saldo setiap akun (jumlah semua transaksi sampai $sampaiTanggal; null = semua).
    // Hasil: baris akun + kolom 'total' (float). Dasar fitur potret keuangan.
    public function afkgetsaldosemuaakun2($sampaiTanggal = null)
    {
        $where = '';
        $bind = [];
        if ($sampaiTanggal !== null) {
            $where = 'WHERE m.tanggal <= ?';
            $bind[] = $sampaiTanggal;
        }
        $rows = $this->db->query("
            SELECT a.*, IFNULL(s.total, 0) AS total
            FROM tbl_afkakun a
            LEFT JOIN (
                SELECT d.akun_id, SUM(d.nilai) AS total
                FROM tbl_afkmain_detail d
                JOIN tbl_afkmain m ON m.id = d.main_id
                $where
                GROUP BY d.akun_id
            ) s ON s.akun_id = a.id
            ORDER BY a.urutan ASC, a.id ASC
        ", $bind)->result_array();

        foreach ($rows as &$r) $r['total'] = (float) $r['total'];
        return $rows;
    }

    // [main_id => [akun_id => nilai]] untuk sekumpulan transaksi.
    private function _nilaiDetailMap(array $mainIds)
    {
        $map = [];
        if (!$mainIds) return $map;
        $rows = $this->db->select('main_id, akun_id, nilai')
            ->where_in('main_id', $mainIds)->get('tbl_afkmain_detail')->result_array();
        foreach ($rows as $r) $map[$r['main_id']][$r['akun_id']] = (float) $r['nilai'];
        return $map;
    }

    // Daftar jurnal (filter bulan/tahun sama seperti afkgetalldatajurnal) + 'nilai' per akun.
    public function afkgetalldatajurnal2($sort = NULL)
    {
        return $this->afkpasangnilaiakun2($this->afkgetalldatajurnal($sort)->result_array());
    }

    // Pasang 'nilai' [akun_id => nilai] ke daftar baris tbl_afkmain (butuh kolom 'id').
    public function afkpasangnilaiakun2(array $rows)
    {
        $map = $this->_nilaiDetailMap(array_column($rows, 'id'));
        foreach ($rows as &$r) $r['nilai'] = $map[$r['id']] ?? [];
        return $rows;
    }

    // Akun yang ditampilkan sebagai kolom tabel: yang aktif + akun nonaktif
    // yang masih punya nilai pada baris-baris yang ditampilkan.
    public function afkgetakununtukbaris2(array $rows)
    {
        $dipakai = [];
        foreach ($rows as $r) {
            foreach (($r['nilai'] ?? []) as $akunId => $v) if ($v != 0) $dipakai[$akunId] = true;
        }
        return array_values(array_filter(
            $this->afkgetakun2(),
            fn($a) => $a['aktif'] || isset($dipakai[$a['id']])
        ));
    }

    public function afkjurnalgetdatabyid2($id)
    {
        $row = $this->afkjurnalgetdatabyid($id);
        if ($row) {
            $map = $this->_nilaiDetailMap([$row['id']]);
            $row['nilai'] = $map[$row['id']] ?? [];
        }
        return $row;
    }

    // Input form: akun[<akun_id>] => nilai. Hanya id yang ada di tbl_afkakun.
    private function _postNilaiAkun()
    {
        $input = $this->input->post('akun', true);
        if (!is_array($input)) return [];

        $valid = array_map('strval', array_column($this->afkgetakun2(), 'id'));
        $out = [];
        foreach ($input as $akunId => $v) {
            if (!in_array((string) $akunId, $valid, true)) continue;
            $out[(int) $akunId] = is_numeric($v) ? (float) $v : 0.0;
        }
        return $out;
    }

    // Tulis ulang detail untuk akun-akun yang dikirim, lalu sinkronkan kolom lama.
    private function _simpanDetail($mainId, array $nilai)
    {
        foreach (array_keys($nilai) as $akunId) {
            $this->db->where(['main_id' => $mainId, 'akun_id' => $akunId])->delete('tbl_afkmain_detail');
        }
        $rows = [];
        foreach ($nilai as $akunId => $v) {
            $v = round($v, 2);
            if ($v != 0) $rows[] = ['main_id' => $mainId, 'akun_id' => $akunId, 'nilai' => $v];
        }
        if ($rows) $this->db->insert_batch('tbl_afkmain_detail', $rows);

        $this->_syncKolomLama($mainId);
    }

    // Isi kolom lama saku/bni/bnik dari tbl_afkmain_detail (jalur mundur).
    private function _syncKolomLama($mainId)
    {
        $kolom = function ($kode) {
            return $this->_sqlRapi("IFNULL((SELECT SUM(d.nilai) FROM tbl_afkmain_detail d
                JOIN tbl_afkakun a ON a.id = d.akun_id
                WHERE d.main_id = m.id AND a.kode = '$kode'), 0)");
        };
        $this->db->query(
            "UPDATE tbl_afkmain m SET
                m.saku = " . $kolom('saku') . ",
                m.bni  = " . $kolom('bni') . ",
                m.bnik = " . $kolom('bnik') . "
             WHERE m.id = ?",
            [(int) $mainId]
        );
    }

    private function _saldoAman($v)
    {
        return is_numeric($v) ? $v : 0;
    }

    // Tambah transaksi: tanda nilai ditentukan kategori (o = negatif, i = positif,
    // lainnya apa adanya). Mengembalikan true bila sukses (transaction).
    public function afkjurnaltambah2()
    {
        $catid = $this->input->post('catid', true);
        $status = $this->afkjurnalgetio($catid);

        $nilai = $this->_postNilaiAkun();
        foreach ($nilai as $akunId => $v) {
            if ($status == 'o') $nilai[$akunId] = -1 * abs($v);
            elseif ($status == 'i') $nilai[$akunId] = abs($v);
        }

        $data = [
            "tanggal"    => $this->input->post('tanggal', true),
            "catid"      => $catid,
            "deskripsi"  => $this->input->post('deskripsi', true),
            "deskripsi2" => $this->input->post('deskripsi2', true),
            "saku"       => '0',
            "bni"        => '0',
            "bnik"       => '0',
            "saldo"      => $this->_saldoAman($this->input->post('saldo', true))
        ];

        $this->db->trans_start();
        $this->db->insert('tbl_afkmain', $data);
        $mainId = $this->db->insert_id();
        $this->_simpanDetail($mainId, $nilai);

        $this->db->where('id', $catid);
        $this->db->update('tbl_afkcat', ["visibility" => '1']);
        $this->db->trans_complete();

        // cookie untuk kenyamanan input berikutnya (sama seperti versi lama)
        foreach (['tanggalafkjurnal' => $data['tanggal'], 'catid' => $data['catid']] as $nama => $nilaiCookie) {
            set_cookie([
                'name'     => $nama,
                'value'    => $nilaiCookie,
                'expire'   => '3600',
                'domain'   => '',
                'path'     => '/',
                'secure'   => FALSE,
                'httponly' => TRUE
            ]);
        }

        return $this->db->trans_status();
    }

    // Edit transaksi: nilai disimpan apa adanya (sama seperti versi lama).
    public function afkjurnaledit2($id)
    {
        $nilai = $this->_postNilaiAkun();

        $data = [
            "tanggal"    => $this->input->post('tanggal', true),
            "catid"      => $this->input->post('catid', true),
            "deskripsi"  => $this->input->post('deskripsi', true),
            "deskripsi2" => $this->input->post('deskripsi2', true),
            "saldo"      => $this->_saldoAman($this->input->post('saldo', true))
        ];

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('tbl_afkmain', $data);
        $this->_simpanDetail((int) $id, $nilai);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // =====================================================================
    // AFK2 - PENGELOLAAN AKUN (tbl_afkakun). Akun tidak pernah dihapus,
    // hanya dinonaktifkan, supaya histori transaksi tetap utuh.
    // =====================================================================
    public function afkakunkodeada2($kode)
    {
        return $this->db->where('kode', $kode)->count_all_results('tbl_afkakun') > 0;
    }

    public function afkakunurutanberikut2()
    {
        $row = $this->db->select_max('urutan', 'maks')->get('tbl_afkakun')->row_array();
        return (int) ($row['maks'] ?? 0) + 1;
    }

    public function afkakuntambah2($kode, $nama, $grup, $urutan)
    {
        return $this->db->insert('tbl_afkakun', [
            'kode'   => $kode,
            'nama'   => $nama,
            'grup'   => $grup,
            'urutan' => (int) $urutan,
            'aktif'  => 1,
        ]);
    }

    public function afkakunedit2($id, $nama, $grup, $urutan)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('tbl_afkakun', [
            'nama'   => $nama,
            'grup'   => $grup,
            'urutan' => (int) $urutan,
        ]);
    }

    public function afkakunaktif2($id, $aktif)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('tbl_afkakun', ['aktif' => $aktif ? 1 : 0]);
    }
}
