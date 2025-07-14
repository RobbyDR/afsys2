<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Yramarsip extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "YRAM Arsip";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        // $data['get'] = $this->sql->select_table('tbl_yramarsip', null, 'id DESC')->result_array();
        $data['get'] = $this->sql->select_table_join('tbl_yramarsip', 'tbl_yramarsip.*,tbl_yramarsipcat.nama as namayramarsipcat', 'tbl_yramarsipcat', 'tbl_yramarsipcat.id=tbl_yramarsip.jenis', 'left', array(1 => 1), 'tbl_yramarsip.id DESC')->result_array();
        $data['getcat'] = $this->sql->select_table('tbl_yramarsipcat', ['tbl_yramarsipcat.status' => '1'], 'id ASC')->result_array();

        $data['subview'] = "yramarsip/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            //insert utama
            $afiliasiid = $this->sql->insert_table('tbl_yramarsip', [
                'jenis' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))),
                'nosurat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nosurat'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'dari' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('dari'))),
                'tujuan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tujuan'))),
                'perihal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))),
                // 'file' => $nama_baru,
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);

            //upload file
            $path = './upload/yramarsip/';
            $this->load->helper('penolong_helper');

            $nama_baru = upload_file('file', $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))), $path, 'gif|jpg|png|jpeg|pdf|doc|docx', 32768, 640, 480);
            //insert di mst_files
            $mstfilesid = $this->sql->insert_table('mst_files', [
                'nama' => $nama_baru,
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))),
                'afiliasi' => 'yramarsip',
                'afiliasiid' => $afiliasiid,
                'path' => $path,
                'status' => '1'
            ]);

            //update utama berdasarkan file upload
            $data_update = ['file' => $mstfilesid];
            $this->sql->update_table('tbl_yramarsip', $data_update, ['tbl_yramarsip.id' => $afiliasiid]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramarsip");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramarsip");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table_join('tbl_yramarsip', 'tbl_yramarsip.*,tbl_yramarsipcat.nama as namayramarsipcat', 'tbl_yramarsipcat', 'tbl_yramarsipcat.id=tbl_yramarsip.jenis', 'left', ['tbl_yramarsip.id' => $this->input->post('id')], 'tbl_yramarsip.id DESC')->row_array();
            $this->load->view('yramarsip/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_yramarsip', ['id' => $this->input->post('id')])->row_array();
            $data['getcat'] = $this->sql->select_table('tbl_yramarsipcat', ['tbl_yramarsipcat.status' => '1'], 'id ASC')->result_array();
            $this->load->view('yramarsip/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit() //dikji ulang lagi
    {
        if ($this->input->post()) {
            $path = './upload/yramarsip/';
            $this->load->helper('penolong_helper');

            // Ambil ID dari input atau parameter untuk update
            $id = $this->input->post('id'); // Pastikan Anda memiliki ID untuk update
            $mstfilesid = $this->sql->select_table('tbl_yramarsip', ['id' => $id])->row_array()['file'];

            // Inisialisasi array untuk data yang akan diupdate
            $data_update = [
                'jenis' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))),
                'nosurat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('nosurat'))),
                'tanggal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tanggal'))),
                'dari' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('dari'))),
                'tujuan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tujuan'))),
                'perihal' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->input->post('status')
            ];


            $data_updatemstfiles = [
                // 'nama' => $nama_baru,
                'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))),
                'status' => $this->input->post('status')
            ];
            // $nama_baru = null;
            // Cek apakah file diupload
            if (!empty($_FILES['file']['name'])) {
                // jika ada file maka dihapus dulu
                $filename = $this->sql->select_table('mst_files', ['id' => $mstfilesid])->row_array()['nama'];
                if ($filename) delete_file($path, $filename);

                // Jika file diupload, lakukan upload
                $nama_baru = upload_file('file', $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))), $path, 'gif|jpg|png|jpeg|pdf|doc|docx', 32768, 640, 480);
                // $data_updatemstfiles['file'] = $nama_baru; // Tambahkan file baru ke array
                $data_updatemstfiles['nama'] = $nama_baru;
            }

            // Update data di database
            $this->sql->update_table('tbl_yramarsip', $data_update, ['tbl_yramarsip.id' => $id]);
            $this->sql->update_table('mst_files', $data_updatemstfiles, ['mst_files.id' => $mstfilesid]);

            // Set flashdata dan redirect
            $this->session->set_flashdata('msg', 'Berhasil update data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("yramarsip");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramarsip");
        }
    }

    public function getfile($mstfilesid = null)
    {
        if ($mstfilesid) {
            $namafile = $this->sql->select_table('mst_files', ['id' => $mstfilesid])->row_array()['nama'];
            redirect("upload/yramarsip/" . $namafile);
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("yramarsip");
        }
    }
}
