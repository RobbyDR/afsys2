<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mainkendaraan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "Kendaraan History";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        $data['get'] = $this->sql->select_table('tbl_mainkendaraan', ['tbl_mainkendaraan.status' => '1'], 'id DESC')->result_array();
        // function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
        // $data['get'] = $this->sql->select_table_join('tbl_mainkendaraan', 'tbl_mainkendaraan.*,tbl_s2matkul.nama as namamatkul', 'tbl_s2matkul', 'tbl_s2matkul.id=tbl_mainkendaraan.matkulid', 'left', array(1 => 1), 'tbl_mainkendaraan.status ASC, tbl_mainkendaraan.deadline ASC')->result_array();
        // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();

        $data['subview'] = "mainkendaraan/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_mainkendaraan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("mainkendaraan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("mainkendaraan");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_mainkendaraan', ['id' => $this->input->post('id')])->row_array();
            $this->load->view('mainkendaraan/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_mainkendaraan', ['id' => $this->input->post('id')])->row_array();
            // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();
            $this->load->view('mainkendaraan/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_mainkendaraan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_mainkendaraan.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("mainkendaraan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("mainkendaraan");
        }
    }

    public function detail()
    {
        $data['judul'] = "Kendaraan History - Detail";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        // $data['get'] = $this->sql->select_table('tbl_mainkendaraandetail', ['tbl_mainkendaraandetail.status' => '1'], 'id DESC')->result_array();
        $data['getmain'] = $this->sql->select_table('tbl_mainkendaraan', ['tbl_mainkendaraan.status' => '1'], 'id DESC')->result_array();
        // function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
        $data['get'] = $this->sql->select_table_join('tbl_mainkendaraandetail', 'tbl_mainkendaraandetail.*,tbl_mainkendaraan.nama as mainnama, tbl_mainkendaraan.tgl1 as maintgl1,', 'tbl_mainkendaraan', 'tbl_mainkendaraan.id=tbl_mainkendaraandetail.mainid', 'left', ['tbl_mainkendaraandetail.status' => '1'], 'tbl_mainkendaraandetail.status ASC, tbl_mainkendaraan.tgl1 DESC')->result_array();
        // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();

        $data['subview'] = "mainkendaraan/detail";
        $this->load->view('partial', $data);
    }

    public function tambahdetail()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_mainkendaraandetail', [
                'mainid' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('mainid'))),
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'jumlah' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jumlah'))),
                'harga' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('harga'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("mainkendaraan/detail");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("mainkendaraan/detail");
        }
    }

    public function getviewdetail()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table_join(
                'tbl_mainkendaraandetail',
                'tbl_mainkendaraandetail.*,tbl_mainkendaraan.nama as mainnama, tbl_mainkendaraan.tgl1 as maintgl1,',
                'tbl_mainkendaraan',
                'tbl_mainkendaraan.id=tbl_mainkendaraandetail.mainid',
                'left',
                ['tbl_mainkendaraandetail.id' => $this->input->post('id')]
            )->row_array();
            $this->load->view('mainkendaraan/getviewdetail', $data);
        } else {
            echo "elor";
        }
    }

    public function geteditdetail()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table_join(
                'tbl_mainkendaraandetail',
                'tbl_mainkendaraandetail.*,tbl_mainkendaraan.nama as mainnama, tbl_mainkendaraan.tgl1 as maintgl1,',
                'tbl_mainkendaraan',
                'tbl_mainkendaraan.id=tbl_mainkendaraandetail.mainid',
                'left',
                ['tbl_mainkendaraandetail.id' => $this->input->post('id')]
            )->row_array();
            $data['getmain'] = $this->sql->select_table('tbl_mainkendaraan', ['tbl_mainkendaraan.status' => '1'], 'id DESC')->result_array();
            $this->load->view('mainkendaraan/geteditdetail', $data);
        } else {
            echo "elor";
        }
    }

    public function editdetail()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_mainkendaraandetail', [
                'mainid' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('mainid'))),
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'jumlah' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jumlah'))),
                'harga' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('harga'))),
                'deskripsi' => $this->input->post('deskripsi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_mainkendaraandetail.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("mainkendaraan/detail");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("mainkendaraan/detail");
        }
    }
}
