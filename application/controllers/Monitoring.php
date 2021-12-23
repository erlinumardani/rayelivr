<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Monitoring extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

        if(!$this->session->userdata('logged_in') == true){
			redirect('auth');
		}
		$this->title = 'Dashboard';
	}


	public function index()
	{
		$content_data	= array(
						'base_url' => base_url(),
						'page' => $this->uri->segment(1),
						);
						
		page_view($this->title, 'view', $content_data);
	}
}