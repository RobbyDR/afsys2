<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Beranda_mod']);
    }
    // ============================== MENU UTAMA
    public function index()
    {
        $data['judul'] = "Beranda";
        $data['getmenu'] = $this->Beranda_mod->getAllData('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'menu'])->result_array();
        $data['getsubmenu'] = $this->Beranda_mod->getAllData('tbl_devmenuaf', ['tbl_devmenuaf.status' => '1', 'tbl_devmenuaf.jenis' => 'submenu'])->result_array();
        $data['subview'] = "beranda/index";
        $this->load->view('partial', $data);
    }
}
