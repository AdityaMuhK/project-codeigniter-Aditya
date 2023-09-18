<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('m_model');
    }

    public function index() {
        $this->load->view('auth/login');
    }
    
    public function aksi_login() {
        $email      = $this->input->post('email', true);
        $password   = $this->input->post('password', true);
        $user       = $this->m_model->get_admin_by_email($email);

        if (!empty($user) && md5($password) === $user['password']) {
            $data = [
                'logged_in' => TRUE,
                'email'     => $user['email'],
                'username'  => $user['username'],
                'role'      => $user['role'],
                'id'        => $user['id'],
            ];
            $this->session->set_userdata($data);

            if ($this->session->userdata('role') == 'admin') {
                redirect(base_url() . "admin");
            } else {
                redirect(base_url() . "auth");
            }
        } else {
            redirect(base_url() . "auth");
        }
    }

    public function register() {
        $this->load->view('auth/register');
    }

    public function aksi_register() {
        $email    = $this->input->post('email', true);
        $username = $this->input->post('username', true);
        $password = md5($this->input->post('password', true));

        $data = [
            'email'    => $email,
            'username' => $username,
            'password' => $password,
            'role'     => 'admin',
        ];

        $success = $this->m_model->register_admin($data);

        if ($success) {
            redirect(base_url() . "auth");
        } else {
            redirect(base_url() . "auth/register");
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(base_url('auth'));
    }
}
