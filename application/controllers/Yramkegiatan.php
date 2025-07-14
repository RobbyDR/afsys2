<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Yramkegiatan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "YRAM Kegiatan";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        $data['get'] = $this->sql->select_table('tbl_yramkegiatan', null, 'id DESC')->result_array();
        // function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
        // $data['get'] = $this->sql->select_table_join('tbl_yramkegiatan', 'tbl_yramkegiatan.*,tbl_s2matkul.nama as namamatkul', 'tbl_s2matkul', 'tbl_s2matkul.id=tbl_yramkegiatan.matkulid', 'left', array(1 => 1), 'tbl_yramkegiatan.status ASC, tbl_yramkegiatan.deadline ASC')->result_array();
        // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();

        $data['subview'] = "yramkegiatan/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_yramkegiatan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deskripsi'))),
                'keterangan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('keterangan'))),
                'status' => '1'
            ]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramkegiatan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkegiatan");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramkegiatan', ['id' => $this->input->post('id')])->row_array();
            $this->load->view('yramkegiatan/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramkegiatan', ['id' => $this->input->post('id')])->row_array();
            // $data['getmatkulid'] = $this->sql->select_table('tbl_s2matkul', ['tbl_s2matkul.status' => '1'], 'id ASC')->result_array();
            $this->load->view('yramkegiatan/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_yramkegiatan', [
                'nama' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nama'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'deskripsi' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('deskripsi'))),
                'keterangan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('keterangan'))),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_yramkegiatan.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramkegiatan");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkegiatan");
        }
    }

    public function album($id)
    {
        $data['judul'] = "Album Kegiatan YRAM";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        $data['getutama'] = $this->sql->select_table('tbl_yramkegiatan', ['id' => $id, 'status' => '1'])->row_array();

        $data['get'] = $this->sql->select_table('mst_files', [
            'afiliasi' => 'yramkegiatan',
            'afiliasiid' => $id
        ])->result_array();

        $data['subview'] = "yramkegiatan/album";
        $this->load->view('partial', $data);
    }

    public function tambahalbum()
    {
        if ($this->input->post()) {
            $afiliasiid = $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')));
            $judul = $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul')));
            $path = './upload/yramkegiatan/';
            $this->load->helper('penolong_helper');

            $nama_baru = upload_file('attachment', null, $path, 'gif|jpg|png|jpeg|pdf|doc|docx', 32768, 640, 480);

            if ($nama_baru) {
                $id = $this->sql->insert_table('mst_files', [
                    'nama' => $nama_baru,
                    'judul' => $judul,
                    'afiliasi' => 'yramkegiatan',
                    'afiliasiid' => $afiliasiid,
                    'path' => $path,
                    'status' => '1'
                ]);
                // $this->session->set_flashdata('msg', 'Berhasil: ' . $id);
                // $this->session->set_flashdata('msg_type', 'success');
                $this->session->set_flashdata('msg', 'Berhasil upload file!');
                $this->session->set_flashdata('msg_type', 'success');
            } else {
                $this->session->set_flashdata('msg', 'Upload file gagal untuk ID: ' . $afiliasiid);
                $this->session->set_flashdata('msg_type', 'error');
            }

            redirect("yramkegiatan/album/" . $afiliasiid);
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkegiatan");
        }
    }

    public function geteditalbum()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('mst_files', ['mst_files.id' => $this->input->post('id')], 'id ASC')->row_array();
            $data['utamaid'] = $this->input->post('utamaid');
            $this->load->view('yramkegiatan/geteditalbum', $data);
        } else {
            echo "elor";
        }
    }

    public function editalbum()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('mst_files', [
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul')))
            ], ['mst_files.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramkegiatan/album/" . $this->input->post('utamaid'));
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramkegiatan");
        }
    }

    public function delalbum()
    {
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $path = $this->input->post('path');
        if (isset($id) && isset($nama) && isset($path)) {

            $this->load->helper('penolong_helper');
            if (delete_file($path, $nama)) {
                $this->sql->delete_table('mst_files', ['mst_files.id' => $id]);
                // echo 'File deleted successfully';
            } else {
                // echo 'File not found or could not be deleted';
            }

            $this->session->set_flashdata('msg', 'Berhasil dihapus!');
            $this->session->set_flashdata('msg_type', 'success');

            echo "ok";
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            echo "error";
        }
    }
}
