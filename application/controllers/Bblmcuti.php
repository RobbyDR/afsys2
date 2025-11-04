<?php
defined('BASEPATH') or exit('No direct script access allowed');

class bblmcuti extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['sql']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "Cuti";
        $data['getmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'], 'urutan ASC')->result_array();
        $data['getsubmenu'] = $this->sql->select_table('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'], 'urutan ASC')->result_array();

        $data['pegawai'] = $this->sql->select_table('mst_pegawai', ['status' => 1])->result_array();

        $data['get'] = $this->sql->select_table('tbl_bblmcuti', null, 'id DESC')->result_array();

        $data['subview'] = "bblmcuti/index";
        $this->load->view('partial', $data);
    }

    public function tambah()
    {
        if ($this->input->post()) {
            //insert utama
            $afiliasiid = $this->sql->insert_table('tbl_bblmcuti', [
                'pegawai' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pegawai'))),
                'jenis' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'tgl2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl2'))),
                'jatah' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jatah'))),
                'alamat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('alamat'))),
                'telp' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('telp'))),
                'approval1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval1'))),
                'approval2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval2'))),
                'ttdchoose1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('ttdchoose1'))),
                'ttdchoose2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('ttdchoose2'))),
                'pejabat1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pejabat1'))),
                'pejabat2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pejabat2'))),
                'alasan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('alasan'))),
                'keterangan' => $this->input->post('keterangan'),
                'status' => '1'
            ]);

            //upload file
            $path = './upload/bblmcuti/';
            $this->load->helper('penolong_helper');

            if (!empty($_FILES['ttd1']['name'])) {
                $ttd1 = upload_file('ttd1', 'ttd1qr' . $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval1'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))), $path, 'jpg|png|jpeg', 32768, 1280, 960);
            } else $ttd1 = null;
            if (!empty($_FILES['ttd2']['name'])) {
                $ttd2 = upload_file('ttd2', 'ttd2qr' . $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval2'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))), $path, 'jpg|png|jpeg', 32768, 1280, 960);
            } else $ttd2 = null;

            //update utama berdasarkan file upload
            $data_update = ['ttd1' => $ttd1, 'ttd2' => $ttd2,];
            $this->sql->update_table('tbl_bblmcuti', $data_update, ['tbl_bblmcuti.id' => $afiliasiid]);

            $this->session->set_flashdata('msg', 'Berhasil tambah data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("bblmcuti");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("bblmcuti");
        }
    }

    public function getview()
    {
        if ($this->input->post()) {

            $this->db->select('
            tbl_bblmcuti.jenis, 
            tbl_bblmcuti.alasan, 
            tbl_bblmcuti.tgl1, 
            tbl_bblmcuti.tgl2, 
            tbl_bblmcuti.jatah, 
            tbl_bblmcuti.alamat, 
            tbl_bblmcuti.telp, 
            tbl_bblmcuti.approval1, 
            tbl_bblmcuti.approval2, 
            tbl_bblmcuti.ttd1, 
            tbl_bblmcuti.ttdchoose1, 
            tbl_bblmcuti.ttdchoose2, 
            tbl_bblmcuti.keterangan, 
            tbl_bblmcuti.status, 
            pegawai.nama as pegawai, 
            atasan.nama as pejabat1, 
            pejabat.nama as pejabat2 
            ');
            $this->db->from('tbl_bblmcuti');
            $this->db->join('mst_pegawai as pegawai', 'pegawai.nip = tbl_bblmcuti.pegawai', 'left');
            $this->db->join('mst_pegawai as atasan', 'atasan.nip = tbl_bblmcuti.pejabat1', 'left');
            $this->db->join('mst_pegawai as pejabat', 'pejabat.nip = tbl_bblmcuti.pejabat2', 'left');
            $this->db->where('pegawai.status', 1);
            $this->db->where('tbl_bblmcuti.status', 1);
            $this->db->where('tbl_bblmcuti.id', $this->input->post('id'));

            $data['get'] = $this->db->get()->row_array();

            $this->load->view('bblmcuti/getview', $data);
        } else {
            echo "elor";
        }
    }

    public function getedit()
    {
        if ($this->input->post()) {
            $data['get'] = $this->sql->select_table('tbl_bblmcuti', ['id' => $this->input->post('id')])->row_array();
            $data['pegawai'] = $this->sql->select_table('mst_pegawai', ['status' => 1])->result_array();
            $this->load->view('bblmcuti/getedit', $data);
        } else {
            echo "elor";
        }
    }

    public function edit()
    {
        if ($this->input->post()) {
            $path = './upload/bblmcuti/';
            $this->load->helper('penolong_helper');

            // Ambil ID dari input atau parameter untuk update
            $id = $this->input->post('id'); // Pastikan Anda memiliki ID untuk update
            // $mstfilesid = $this->sql->select_table('tbl_bblmcuti', ['id' => $id])->row_array()['file'];

            // Inisialisasi array untuk data yang akan diupdate
            $data_update = [
                'pegawai' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pegawai'))),
                'jenis' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jenis'))),
                'tgl1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))),
                'tgl2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl2'))),
                'jatah' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('jatah'))),
                'alamat' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('alamat'))),
                'telp' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('telp'))),
                'approval1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval1'))),
                'approval2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval2'))),
                'ttdchoose1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('ttdchoose1'))),
                'ttdchoose2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('ttdchoose2'))),
                'pejabat1' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pejabat1'))),
                'pejabat2' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('pejabat2'))),
                'alasan' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('alasan'))),
                'keterangan' => $this->input->post('keterangan'),
                'status' => $this->input->post('status')
            ];


            // $data_updatemstfiles = [
            //     // 'nama' => $nama_baru,
            //     'judul' => $this->security->xss_clean($this->sql->sanitasi($this->input->post('perihal'))),
            //     'status' => $this->input->post('status')
            // ];
            // $nama_baru = null;
            // Cek apakah file diupload
            if (!empty($_FILES['ttd1']['name'])) {
                // jika ada file maka dihapus dulu
                $ttd1 = $this->sql->select_table('tbl_bblmcuti', ['id' => $id])->row_array()['ttd1'];
                if ($ttd1) delete_file($path, $ttd1);

                // Jika file diupload, lakukan upload
                $ttd1 = upload_file('ttd1', 'ttd1qr' . $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval1'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))), $path, 'jpg|png|jpeg', 32768, 1280, 960);                // $data_updatemstfiles['file'] = $nama_baru; // Tambahkan file baru ke array
                $data_update['ttd1'] = $ttd1;
            }
            if (!empty($_FILES['ttd2']['name'])) {
                // jika ada file maka dihapus dulu
                $ttd2 = $this->sql->select_table('tbl_bblmcuti', ['id' => $id])->row_array()['ttd2'];
                if ($ttd2) delete_file($path, $ttd2);

                // Jika file diupload, lakukan upload
                $ttd2 = upload_file('ttd2', 'ttd2qr' . $this->security->xss_clean($this->sql->sanitasi($this->input->post('approval1'))) . $this->security->xss_clean($this->sql->sanitasi($this->input->post('tgl1'))), $path, 'jpg|png|jpeg', 32768, 1280, 960);                // $data_updatemstfiles['file'] = $nama_baru; // Tambahkan file baru ke array
                $data_update['ttd2'] = $ttd2;
            }

            // Update data di database
            //update utama berdasarkan file upload
            $this->sql->update_table('tbl_bblmcuti', $data_update, ['tbl_bblmcuti.id' => $id]);

            // Set flashdata dan redirect
            $this->session->set_flashdata('msg', 'Berhasil update data!');
            $this->session->set_flashdata('msg_type', 'success');
            redirect("bblmcuti");
        } else {
            $this->session->set_flashdata('msg', 'Error!');
            $this->session->set_flashdata('msg_type', 'error');
            redirect("bblmcuti");
        }
    }

    public function generate($id)
    {
        $this->load->helper('penolong_helper');
        $this->db->select('
        tbl_bblmcuti.jenis, 
        tbl_bblmcuti.alasan, 
        tbl_bblmcuti.tgl1, 
        tbl_bblmcuti.tgl2, 
        tbl_bblmcuti.jatah, 
        tbl_bblmcuti.alamat, 
        tbl_bblmcuti.telp, 
        tbl_bblmcuti.approval1, 
        tbl_bblmcuti.approval2, 
        tbl_bblmcuti.ttd1 as ttdqr1, 
        tbl_bblmcuti.ttd2 as ttdqr2, 
        tbl_bblmcuti.ttdchoose1, 
        tbl_bblmcuti.ttdchoose2, 
        tbl_bblmcuti.keterangan, 
        tbl_bblmcuti.status, 
        pegawai.nama as pegawai, 
        pegawai.nip as nippegawai, 
        pegawai.jabatan as jabatanpegawai, 
        pegawai.ttd_file as ttd_filepegawai, 
        atasan.nama as pejabat1, 
        atasan.nip as nippejabat1, 
        atasan.ttd_file as ttd_filepejabat1, 
        pejabat.nama as pejabat2, 
        pejabat.nip as nippejabat2, 
        pejabat.ttd_file as ttd_filepejabat2 
        ');
        $this->db->from('tbl_bblmcuti');
        $this->db->join('mst_pegawai as pegawai', 'pegawai.nip = tbl_bblmcuti.pegawai', 'left');
        $this->db->join('mst_pegawai as atasan', 'atasan.nip = tbl_bblmcuti.pejabat1', 'left');
        $this->db->join('mst_pegawai as pejabat', 'pejabat.nip = tbl_bblmcuti.pejabat2', 'left');
        $this->db->where('pegawai.status', 1);
        $this->db->where('tbl_bblmcuti.status', 1);
        $this->db->where('tbl_bblmcuti.id', $id);

        $data['get'] = $this->db->get()->row_array();

        $data['masakerja'] = get_masakerja($data['get']['nippegawai']);

        $this->load->model('Cuti_mod');
        $data['jumlahcuti'] = $this->Cuti_mod->get_jumlahcuti($data['get']['tgl1'], $data['get']['tgl2']);
        $this->load->view('bblmcuti/generate', $data);
    }
}
