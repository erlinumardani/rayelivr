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
    
    function form()
	{
		
		$content_data = array(
			'form_title'=>'New Contact Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$phonebooks = dropdown_render($this->db->select('id,name')->get('tb_ivrmenu')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'str_in',
				'label'=>'Input',
				'type'=>'text'
			),
			array(
				'name'=>'str_out',
				'label'=>'Output',
				'type'=>'text'
			),
			array(
				'name'=>'type',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"yesno_answer")
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'form', $content_data);
	}

	function view($id)
	{

		$view_data = $this->db->where('id',$id)->get('tb_translate')->row();

		$fieldset = array(
			array(
				'name'=>'str_in',
				'label'=>'Input',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->str_in,
					'data-input_type' => 'username'
				)
			),
			array(
				'name'=>'str_out',
				'label'=>'Output',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->str_out,
				)
			),
			array(
				'name'=>'id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->id)
			),
			array(
				'name'=>'type',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->type)
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Update")
			)
		);

		$content_data = array(
			'form_title'=>'View Contact Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
	}

	function update($id)
	{

		$view_data = $this->db->where('id',$id)->get('tb_translate')->row();

		$fieldset = array(
			array(
				'name'=>'str_in',
				'label'=>'Input',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->str_in,
					'data-input_type' => 'username'
				)
			),
			array(
				'name'=>'str_out',
				'label'=>'Output',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->str_out,
				)
			),
			array(
				'name'=>'id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->id)
			),
			array(
				'name'=>'type',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->type)
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Update")
			)
		);

		$content_data = array(
			'form_title'=>'View Detail Data',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'update', $content_data);
	}

	function form_submit()
	{
		$table = "tb_ivrmenu";
		$data = $this->input->post();
		$data['updated_by'] = $this->session->userdata('user_id');
		$data['updated_at'] = date('Y-m-d H:i:s');
		$action = $data['action'];
		unset($data['action']);
		
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}


		$this->db->trans_start();
		if($action == "Update"){
			$this->db->where('id',$id)->update('tb_translate',$data);
		}else{
			$this->db->insert('tb_translate',$data);
		}

		if($this->db->trans_complete()){
			$result = array("status"=>TRUE,"message"=>"Data inserted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to insert");
		}

		echo json_encode($result);
	}

	function datalist()
    {
		$table = 'v_question_answer'; //nama tabel dari database
		$column_order = array(null, 'str_in','str_out'); //field yang ada di table user
		$column_search = array('str_in','str_out'); //field yang diizin untuk pencarian 
		$order = array('str_in' => 'asc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
			$row[] = '
				<button class="btn-sm delete btn-danger" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Delete this row" style="border-radius: 50%;"><i class="fas fa-trash"></i></button>
				<button class="btn-sm update btn-primary" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Edit this row" style="border-radius: 50%;"><i class="fas fa-edit"></i></button>
			';
            $row[] = $field->str_in;
			$row[] = $field->str_out;
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = $field->id;
 
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

	function delete(){
		
		$id = $this->input->post('id');

		if($this->db->where('id',$id)->delete('tb_translate')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
