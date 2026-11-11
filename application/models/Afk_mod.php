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

    // AFK2: versi fix, tidak memicu "Undefined array key" saat tbl_afkmain masih kosong
    public function afkjurnalgetlastsaldo2()
    {
        $row = $this->db
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
        $this->db->query("SET @running := 0");
        $this->db->query("
            UPDATE tbl_afkmain t
            JOIN (
                SELECT id, (@running := @running + saku + bni + bnik) AS saldo_baru
                FROM tbl_afkmain
                ORDER BY id ASC
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
        // === 1️⃣ Harian (semua kategori) ===
        $sql_harian = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'harian' AS jeniswaktu,
            tanggal AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        GROUP BY tanggal, catid
    ";
        $this->db->query($sql_harian);

        // === 2️⃣ Bulanan (semua kategori) ===
        $sql_bulanan = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'bulanan' AS jeniswaktu,
            DATE_FORMAT(tanggal, '%Y-%m-01') AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        GROUP BY YEAR(tanggal), MONTH(tanggal), catid
    ";
        $this->db->query($sql_bulanan);

        // === 3️⃣ Tahunan (semua kategori) ===
        $sql_tahunan = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT 
            catid AS jenis,
            'tahunan' AS jeniswaktu,
            DATE_FORMAT(tanggal, '%Y-01-01') AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        GROUP BY YEAR(tanggal), catid
    ";
        $this->db->query($sql_tahunan);

        // === 4️⃣ 4EVER (semua kategori) ===
        $sql_4ever = "
        INSERT INTO tbl_afkrekap (jenis, jeniswaktu, waktu, nilai)
        SELECT
            catid AS jenis,
            '4ever' AS jeniswaktu,
            MIN(DATE_FORMAT(tanggal, '%Y-01-01')) AS waktu,
            SUM(saku + bni + bnik) AS nilai
        FROM tbl_afkmain
        GROUP BY catid
    ";
        $this->db->query($sql_4ever);

        // Reset dulu semua ke 0, baru set 1 utk kategori yang benar-benar
        // masih punya transaksi. Ini supaya kategori yang datanya sudah
        // dihapus semua tidak "nyangkut" tampil terus (visibility lama
        // cuma di-set ke 1 saat insert, tidak pernah direset ke 0).
        $this->db->query("UPDATE tbl_afkcat SET visibility = '0'");

        $sql_visibility = "
        UPDATE tbl_afkcat
        SET visibility = '1'
        WHERE id IN (SELECT DISTINCT catid FROM tbl_afkmain)
    ";
        $this->db->query($sql_visibility);
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

    // AFK2: versi fix - kalau bulan sebelumnya belum ada data sama sekali
    // (misal baru mulai pakai aplikasi), langsung return 0 daripada
    // memicu "Undefined array key 'id'".
    public function afkgetsaldo1_2($entitas)
    {
        $tanggal = date('Y-m-d');
        $tanggal1 = new DateTime($tanggal);
        $tanggal1->sub(new DateInterval('P1M'));
        $tahun = $tanggal1->format('Y');
        $bulan = $tanggal1->format('m');

        $where = ['MONTH(tanggal)' => $bulan, 'YEAR(tanggal)' => $tahun];
        $query = $this->db->select('*')
            ->from('tbl_afkmain')
            ->where($where)
            ->order_by('id', 'desc')
            ->get()->row_array();

        if (empty($query)) {
            return 0;
        }

        $id = $query['id'];
        $this->db->select_sum($entitas);
        $this->db->where('id <=', $id);
        $result = $this->db->get('tbl_afkmain')->row_array();

        return $result[$entitas] ?? 0;
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
