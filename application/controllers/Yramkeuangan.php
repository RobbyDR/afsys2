<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Yramkeuangan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "YRAM Keuangan";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        $data['get'] = $this->sql->select_table('tbl_yramkeuangan', null, 'id DESC')->result_array();
        // function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
        // $data['get'] = $this->sql->select_table_join('tbl_yramkeuangan', 'tbl_yramkeuangan.*,tbl_s2matkul.nama as namamatkul', 'tbl_s2matkul', 'tbl_s2matkul.id=tbl_yramkeuangan.matkulid', 'left', array(1 => 1), 'tbl_yramkeuangan.status ASC, tbl_yramkeuangan.deadline ASC')->result_array();
        // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();

        $data['subview'] = "yramkeuangan/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_yramkeuangan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramkeuangan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkeuangan");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramkeuangan', ['id' => $this->input->post('id')])->row_array();
            $this->load->view('yramkeuangan/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramkeuangan', ['id' => $this->input->post('id')])->row_array();
            // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();
            $this->load->view('yramkeuangan/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_yramkeuangan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_yramkeuangan.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramkeuangan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkeuangan");
        }
    }
}
