<?php

defined('BASEPATH') or exit('No direct script access allowed');

class utama extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        $this->load->view('welcome_spp');
    }

}
