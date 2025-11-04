<?php

class Cuti_mod extends CI_model
{
    public function get_jumlahcuti($tgl1, $tgl2)
    {
        $start = new DateTime($tgl1);
        $end = new DateTime($tgl2);
        $end->modify('+1 day');

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);

        // Ambil daftar hari libur dari database
        $CI = &get_instance();
        $CI->load->database();
        $query = $CI->db->select('tanggal')->from('tbl_harilibur')->where('status', 1)->get()->result();

        // Konversi ke array tanggal libur
        $liburNasional = array_map(function ($row) {
            return $row->tanggal;
        }, $query);

        $jumlahCuti = 0;

        foreach ($dateRange as $date) {
            $day = $date->format('N'); // 1 = Senin ... 7 = Minggu
            $tanggal = $date->format('Y-m-d');

            if ($day < 6 && !in_array($tanggal, $liburNasional)) {
                $jumlahCuti++;
            }
        }

        return $jumlahCuti;
    }
}
