<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public function __construct(){
		parent::__construct();
		
		//$menu_privilege = $this->db->get_where('menus',"JSON_CONTAINS(`privileges`, '[".$this->session->userdata('role_id')."]') and url like '%".$this->uri->segment(1)."%'")->num_rows();

        if(!$this->session->userdata('logged_in') == true){
			redirect('auth');
		}
		$this->title = 'Number Answer Management';
    }
    
	function index()
	{
		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);
		
		page_view($this->title, 'data', $content_data);
    }

	function datalist()
    {
		$table = 'v_keylog'; //nama tabel dari database
		$column_order = array(null, 'uniqueid','src','created_at','node','keypress','descriptions'); //field yang ada di table user
		$column_search = array('uniqueid','src','created_at','node','keypress','descriptions'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->uniqueid;
            $row[] = $field->src;
			$row[] = $field->created_at;
			$row[] = $field->node;
			$row[] = $field->keypress;
			$row[] = $field->descriptions;
			if(!empty($field->recordingfile)){
				$row[] = '<audio controls><source src="'.base_url().'recording/'.$field->recordingfile.'" type="audio/wav">Your browser does not support the audio element.</audio>';
			}else{
				$row[] = '';
			}
			
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable_model->count_all($table),
            "recordsFiltered" => $this->datatable_model->count_filtered($table, $column_order, $column_search, $order),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}
}
