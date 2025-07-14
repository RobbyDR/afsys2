<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Yramtugas extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "YRAM Penugasan";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        // $data['get'] = $this->sql->select_table('tbl_yramtugas', null, 'id DESC')->result_array();
        // function select_table_join($table, $column, $join_table, $join_on, $join, $where = array(1 => 1), $order_by = null)
        $data['get'] = $this->sql->select_table_join('tbl_yramtugas', 'tbl_yramtugas.*,tbl_yramsdm.nama as namapegawai', 'tbl_yramsdm', 'tbl_yramsdm.nip=tbl_yramtugas.nip', 'left', array(1 => 1), 'tbl_yramtugas.status ASC, tbl_yramtugas.id ASC')->result_array();
        $data['getnip'] = $this->sql->select_table('tbl_yramsdm', ['tbl_yramsdm.status' => '1'], 'id ASC')->result_array();

        // $this->session->set_flashdata('msg', 'Anda luar biasa!');
        // $this->session->set_flashdata('msg_type', 'success');

        $data['subview'] = "yramtugas/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            $id = $this->sql->insert_table('tbl_yramtugas', [
                'nip' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nip'))),
                'no' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('no'))),
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'tempat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tempat'))),
                'isi' => $this->input->post('isi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);
            $this->session->set_flashdata('msg', 'Berhasil menambah data!');
            $this->session->set_flashdata('msg_type', 'success');

            redirect("yramtugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramtugas");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['getdatadukung'] = $this->sql->select_table('mst_files', [
                'afiliasiid' => $this->input->post('id'),
                'afiliasi' => 'yramtugas',
                'status' => '1'
            ])->result_array();
            $data['get'] = $this->sql->select_table_join('tbl_yramtugas', 'tbl_yramtugas.*,tbl_yramsdm.nama as namapegawai', 'tbl_yramsdm', 'tbl_yramsdm.nip=tbl_yramtugas.nip', 'left', ['tbl_yramtugas.id' => $this->input->post('id')], 'tbl_yramtugas.status ASC, tbl_yramtugas.id ASC')->row_array();
            $this->load->view('yramtugas/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramtugas', ['id' => $this->input->post('id')])->row_array();
            $data['getnip'] = $this->sql->select_table('tbl_yramsdm', ['tbl_yramsdm.status' => '1'], 'id ASC')->result_array();
            $this->load->view('yramtugas/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('tbl_yramtugas', [
                'nip' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nip'))),
                'no' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('no'))),
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'tempat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tempat'))),
                'isi' => $this->input->post('isi'),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('status')))
            ], ['tbl_yramtugas.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramtugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramtugas");
        }
    }

    public function rename()
    {
        if ($this->input->post()) {
            $id = $this->sql->update_table('mst_files', [
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul')))
            ], ['mst_files.id' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')))]);

            $this->session->set_flashdata('msg', 'Berhasil mengubah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramtugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramtugas");
        }
    }


    public function getupload()
    {
        if ($this->input->post()) {
            // $data['get'] = $this->sql->select_table('tbl_yramtugas', ['id' => $this->input->post('id')])->row_array();
            $data['get'] = $this->sql->select_table_join('tbl_yramtugas', 'tbl_yramtugas.*,tbl_yramsdm.nama as namapegawai', 'tbl_yramsdm', 'tbl_yramsdm.nip=tbl_yramtugas.nip', 'left', ['tbl_yramtugas.id' => $this->input->post('id')], 'tbl_yramtugas.status ASC, tbl_yramtugas.id ASC')->row_array();
            // $data['getnip'] = $this->sql->select_table('tbl_yramsdm', ['tbl_yramsdm.status' => '1'], 'id ASC')->result_array();
            $this->load->view('yramtugas/getupload', $data);
        } else {
            echo "elor";
        }
    }

    public function getrename()
    {
        if ($this->input->post()) {
            // $data['get'] = $this->sql->select_table('tbl_yramtugas', ['id' => $this->input->post('id')])->row_array();
            // $data['get'] = $this->sql->select_table_join('tbl_yramtugas', 'tbl_yramtugas.*,tbl_yramsdm.nama as namapegawai', 'tbl_yramsdm', 'tbl_yramsdm.nip=tbl_yramtugas.nip', 'left', ['tbl_yramtugas.id' => $this->input->post('id')], 'tbl_yramtugas.status ASC, tbl_yramtugas.id ASC')->row_array();
            // $data['getnip'] = $this->sql->select_table('tbl_yramsdm', ['tbl_yramsdm.status' => '1'], 'id ASC')->result_array();
            $data['get'] = $this->sql->select_table('mst_files', ['mst_files.id' => $this->input->post('id')], 'id ASC')->row_array();
            // var_dump($data['get']);
            $this->load->view('yramtugas/getrename', $data);
        } else {
            echo "elor";
        }
    }

    public function upload()
    {
        if ($this->input->post()) {
            $afiliasiid = $this->security->xss_clean($this->sql->sanitasi($this->input->post('id')));
            $judul = $this->security->xss_clean($this->sql->sanitasi($this->input->post('judul')));
            $path = './upload/yramtugas/';
            $this->load->helper('penolong_helper');

            $nama_baru = upload_file('attachment', $id, $path, 'gif|jpg|png|jpeg|pdf|doc|docx', 32768, 640, 480);
            // var_dump($this->input->post());
            // var_dump($nama_baru);
            // die;

            if ($nama_baru) {
                // Update database dengan nama file baru
                // $this->db->where(['id' => $id]);
                // $this->db->update('mst_files', [
                //     'nama' => $nama_baru,
                //     'afiliasi' => 'yramtugas',
                //     'afiliasiid' => $nama_baru,
                //     'path' => $path,
                //     'status' => '1'
                // ]);
                $id = $this->sql->insert_table('mst_files', [
                    'nama' => $nama_baru,
                    'judul' => $judul,
                    'afiliasi' => 'yramtugas',
                    'afiliasiid' => $afiliasiid,
                    'path' => $path,
                    'status' => '1'
                ]);
                // $this->session->set_flashdata('msg', 'Berhasil: ' . $id);
                // $this->session->set_flashdata('msg_type', 'success');
                $this->session->set_flashdata('msg', 'Berhasil upload file!');
                $this->session->set_flashdata('msg_type', 'success');
            } else {
                // Menangani jika upload gagal (opsional)

                $this->session->set_flashdata('msg', 'Upload file gagal untuk ID: ' . $afiliasiid);
                $this->session->set_flashdata('msg_type', 'error');
            }

            redirect("yramtugas");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramtugas");
        }
    }

    public function delfile()
    {
        // var_dump($this->input->post());
        $id = $this->input->post('id');
        $nama = $this->input->post('nama');
        $path = $this->input->post('path');
        if (isset($id) && isset($nama) && isset($path)) {
            //     // $id = $this->input->post('id');

            //     // $this->ithelpdesk_mod->del($id);

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
