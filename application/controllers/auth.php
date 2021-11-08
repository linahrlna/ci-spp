<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_auth');
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function login()
    {
        $this->load->view('auth/login');
    }
    
    public function register()
    {
        $this->load->view('auth/register');
    }

    public function proses_login()
    {
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->form_validation->set_rules('email', 'email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->session->set_flashdata('errors', $errors);
            $this->session->set_flashdata('input', $this->input->post());
            redirect('/crud'); // LOGIN
        
        } else {

            $username = htmlspecialchars($this->input->post('email'));
            $pass = htmlspecialchars($this->input->post('password'));

            // CEK KE DATABASE BERDASARKAN EMAIL
            $cek_login = $this->m_auth->cek_login($email); 
                
            if($cek_login == FALSE)
            {
                echo '<script>alert("email yang Anda masukan salah.");window.location.href="'.base_url('auth/login').'";</script>';
            
            } else {
            
                if(password_verify($pass, $cek_login->password)){
                    // if the username and password is a match
                    $this->session->set_userdata('id', $cek_login->id);
                    $this->session->set_userdata('username', $cek_login->email);
                    $this->session->set_userdata('name', $cek_login->name);
                    
                    redirect('/crud');
                        
                } else {
                    echo '<script>alert("email atau Password yang Anda masukan salah.");window.location.href="'.base_url('auth/login').'";</script>';
                }
            }
        }
    }

    public function proses_register()
    {
        $this->load->library('form_validation');
        $this->load->library('session');

        $this->form_validation->set_rules('name', 'Nama', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|min_length[5]|max_length[15]|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $errors = $this->form_validation->error_array();
            $this->session->set_flashdata('errors', $errors);
            $this->session->set_flashdata('input', $this->input->post());
            redirect('auth/register');
        } else {
            $email = $this->input->post('email');
            $name = $this->input->post('name');
            $password = $this->input->post('password');
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'email' => $email,
                'name' => $name,
                'password' => $pass
            ];
            $insert = $this->m_auth->register("users", $data);
            if($insert){
                echo '<script>alert("Sukses! Anda berhasil melakukan register. Silahkan login untuk mengakses data.");window.location.href="'.base_url('auth/login').'";</script>';
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        echo '<script>alert("Sukses! Anda berhasil logout."); window.location.href="'.base_url('auth/login').'";
        </script>';
    }
}
