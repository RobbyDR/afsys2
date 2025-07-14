<?php
defined('BASEPATH') or exit('No direct script access allowed');

class S2kicktugas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index($tab = null)
    {
        $data['judul'] = "S2 Kick Tugas";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        if ($tab == null) {
            $data['tab'] = $this->sql->select_table('mst_sekolah', ['active' => 1])->row_array()['kode'];
        } else {
            $data['tab'] = $tab;
        }

        $this->db->select('
            tbl_s2kicktugas.*, 
            tbl_s2matkul.nama as namamatkul,
            mst_sekolah.kode
            ');
        $this->db->from('tbl_s2kicktugas');
        $this->db->join('tbl_s2matkul', 'tbl_s2matkul.id = tbl_s2kicktugas.matkulid', 'left');
        $this->db->join('mst_sekolah', 'mst_sekolah.kode = tbl_s2matkul.sekolah_kode', 'left');
        // where array(1 => 1) bisa diabaikan, karena tidak berdampak
        $this->db->where('mst_sekolah.kode', $data['tab']);
        $this->db->order_by('tbl_s2kicktugas.status', 'ASC');
        $this->db->order_by('tbl_s2kicktugas.deadline', 'ASC');

        $data['get'] = $this->db->get()->result_array();

        $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();
        $data['getschool'] = $this->sql->select_table('mst_sekolah', ['mst_sekolah.status' => '1'], 'id ASC')->result_array();
        $data['getschoolbytab'] = $this->sql->select_table('mst_sekolah', ['mst_sekolah.status' => '1', 'mst_sekolah.kode' => $tab], 'id ASC')->row_array();

        $this->db->where('tbl_s2kicktugas.status', '2');
        $this->db->join('tbl_s2matkul', 'tbl_s2matkul.id = tbl_s2kicktugas.matkulid', 'left');
        $this->db->join('mst_sekolah', 'mst_sekolah.kode = tbl_s2matkul.sekolah_kode', 'left');
        $this->db->where('mst_sekolah.kode', $data['tab']);
        $data['progress'] = $this->db->get('tbl_s2kicktugas')->num_rows();

        $this->db->join('tbl_s2matkul', 'tbl_s2matkul.id = tbl_s2kicktugas.matkulid', 'left');
        $this->db->join('mst_sekolah', 'mst_sekolah.kode = tbl_s2matkul.sekolah_kode', 'left');
        $this->db->where('mst_sekolah.kode', $data['tab']);
        $data['progresstot'] = $this->db->get('tbl_s2kicktugas')->num_rows();


        $data['subview'] = "s2kicktugas/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_s2kicktugas', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'matkulid' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('matkulid'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'deadline' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deadline'))),
                'selesai' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('selesai'))),
                'deskripsi' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deskripsi'))),
                'keterangan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('keterangan'))),
                'status' => '1'
            ]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("S2kicktugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("S2kicktugas");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_s2kicktugas', ['id' => $this->input->post('id')])->row_array();
            $this->load->view('s2kicktugas/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_s2kicktugas', ['id' => $this->input->post('id')])->row_array();

            //---
            $this->load->helper('penolong_helper');
            // Ambil semua matkul aktif
            // $this->db->where('status', '1');
            // $this->db->order_by('id', 'ASC');
            // $getmatkulid = $this->db->get('tbl_s2matkul')->result_array();

            // // Cek apakah matkul lama ($get['matkulid']) ada di list aktif
            // $exists = false;
            // foreach ($getmatkulid as $row) {
            //     if ($row['id'] == $data['get']['matkulid']) {
            //         $exists = true;
            //         break;
            //     }
            // }

            // // Kalau belum ada, tambahkan secara manual
            // if (!$exists && !empty($data['get']['matkulid'])) {
            //     $matkul_lama = $this->db
            //         ->where('id', $data['get']['matkulid'])
            //         ->get('tbl_s2matkul')
            //         ->row_array();

            //     if ($matkul_lama) {
            //         $getmatkulid[] = $matkul_lama; // tambahkan ke list
            //     }
            // }

            // $data['getmatkulid'] = $getmatkulid;
            $data['getmatkulid'] = get_dropdown_with_preserved_value(
                $this->db,
                'tbl_s2matkul',
                ['status' => '1'],
                $data['get']['matkulid'],       // preserve value
                'id ASC'
            );
            //---


            $this->load->view('s2kicktugas/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_s2kicktugas', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'matkulid' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('matkulid'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'deadline' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deadline'))),
                'selesai' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('selesai'))),
                'deskripsi' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deskripsi'))),
                'keterangan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('keterangan'))),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_s2kicktugas.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("S2kicktugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("S2kicktugas");
        }
    }
}
