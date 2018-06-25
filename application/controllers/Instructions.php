<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instructions extends CI_Controller {
    
	public function index()
	{
		$this->load->view('instructions');
	}
}
