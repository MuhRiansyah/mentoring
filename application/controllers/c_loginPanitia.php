<?php
/*
 * menangani proses login panitia
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_LoginPanitia extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model('m_panitia');
    }

    function index(){
        $data=array(
            'title'=>'Login Page'
        );
        $this->load->view('admin/v_login',$data);
    }

    function ceklogin() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        //query  database
        $result = $this->m_panitia->login($username, $password);
        
        if($result) {
            $sess_array = array();
            foreach($result as $row) {
                $result2 = $this->m_panitia->getNama($row->id_panitia);
                foreach ($result2 as $row2) {
                    $nama = $row2->nama_mahasiswa;
                }
                //membuat session
                $sess_array = array(
                    'id_akun' => $row->id_akun,
                    'id_panitia' => $row->id_panitia,
                    'USERNAME' => $row->username,
                    'NAMA' => $nama,
                    'login_panitia'=>true,
                );
                $this->session->set_userdata($sess_array);
                redirect('admin','refresh');
            }
            return TRUE;
        } else {
            //if form validate false
            $this->session->set_flashdata('notif','username atau password yang anda masukan salah');
            redirect('panitia/login','refresh');
            return FALSE;
        }
    }

    function logout() {
        $this->session->unset_userdata('id_akun');
        $this->session->unset_userdata('id_panitia');
        $this->session->unset_userdata('USERNAME');
        $this->session->unset_userdata('NAMA');
        $this->session->unset_userdata('login_panitia');
        $this->session->set_flashdata('notif','THANK YOU FOR LOGIN IN THIS APP');
        redirect('panitia/login');
    }
}
