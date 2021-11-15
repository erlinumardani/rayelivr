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
		
		$menu_privilege = $this->db->get_where('menus',"JSON_CONTAINS(`privileges`, '[".$this->session->userdata('role_id')."]') and url like '%".$this->uri->segment(1)."%'")->num_rows();

        if(!$this->session->userdata('logged_in') == true || $menu_privilege < 1){
			redirect('auth');
		}
		$this->title = 'Contacts Management';
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
				'name'=>'name',
				'label'=>'Menu Name (No Space)',
				'type'=>'text',
				'custom_attributes'=>array(
					'data-input_type' => 'username'
				)
			),
			array(
				'name'=>'label',
				'label'=>'Menu Label',
				'type'=>'text'
			),
			array(
				'name'=>'narration',
				'label'=>'Menu Narration Text',
				'type'=>'textarea'
			),
			array(
				'name'=>'type',
				'label'=>'Menu Narration Type',
				'type'=>'select',
				'options'=>array('tts'=>'Text To Speech','voice'=>'Recorded Voice'),
				'default_options'=>'tts'
			),
			array(
				'name'=>'voicepath',
				'label'=>'Voice File Path',
				'type'=>'text',
				'custom_attributes'=>array(
					'disabled' => true
				)
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

		$view_data = $this->db->where('id',$id)->get('tb_ivrmenu')->row();
		$phonebooks = dropdown_render($this->db->select('id,name')->get('tb_ivrmenu')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'name',
				'label'=>'Menu Name (No Space)',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->name,
				)
			),
			array(
				'name'=>'label',
				'label'=>'Menu Label',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->label,
				)
			),
			array(
				'name'=>'narration',
				'label'=>'Menu Narration Text',
				'type'=>'textarea',
				'custom_attributes'=>array(
					'value' => $view_data->narration,
				)
			),
			array(
				'name'=>'type',
				'label'=>'Menu Narration Type',
				'type'=>'select',
				'options'=>array('tts'=>'Text To Speech','voice'=>'Recorded Voice'),
				'default_options'=>$view_data->type
			),
			array(
				'name'=>'voicepath',
				'label'=>'Voice File Path',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->voicepath,
				)
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
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

		$view_data = $this->db->where('id',$id)->get('tb_ivrmenu')->row();
		$phonebooks = dropdown_render($this->db->select('id,name')->get('tb_ivrmenu')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'name',
				'label'=>'Menu Name (No Space)',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->name,
					'data-input_type' => 'username'
				)
			),
			array(
				'name'=>'label',
				'label'=>'Menu Label',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->label,
				)
			),
			array(
				'name'=>'narration',
				'label'=>'Menu Narration Text',
				'type'=>'textarea',
				'custom_attributes'=>array(
					'value' => $view_data->narration,
				)
			),
			array(
				'name'=>'type',
				'label'=>'Menu Narration Type',
				'type'=>'select',
				'options'=>array('tts'=>'Text To Speech','voice'=>'Recorded Voice'),
				'default_options'=>$view_data->type
			),
			array(
				'name'=>'voicepath',
				'label'=>'Voice File Path',
				'type'=>'text',
				'custom_attributes'=>array(
					'value' => $view_data->voicepath,
					'disabled'=>true
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
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Update")
			)
		);

		$content_data = array(
			'form_title'=>'View Menu Detail',
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
		$action = $data['action'];
		unset($data['action']);
		
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}


		$this->db->trans_start();
		if($action == "Update"){
			$this->db->where('id',$id)->update('tb_ivrmenu',$data);
		}else{
			$this->db->insert('tb_ivrmenu',$data);
		}

		if($this->db->trans_complete()){
			$result = array("status"=>TRUE,"message"=>"Data inserted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to insert");
		}

		echo json_encode($result);
	}

	function list()
    {
		$table = 'tb_ivrmenu'; //nama tabel dari database
		$column_order = array(null, 'name','label','type'); //field yang ada di table user
		$column_search = array('name','label','type'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
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
            $row[] = $field->name;
            $row[] = $field->label;
			$row[] = $field->type;
            $row[] = $field->voicepath;
            $row[] = $field->narration;
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

		if($this->db->where('id',$id)->delete('tb_ivrmenu')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
