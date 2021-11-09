<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Utama extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
    }

    function index()
    {
        $this->load->view('welcome_spp');
    }

}
