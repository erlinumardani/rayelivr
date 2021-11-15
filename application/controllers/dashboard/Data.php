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
		$this->title = 'Dashboard';
		$this->role_id = $this->session->userdata('role_id');
		$this->tenant_id = $this->session->userdata('tenant_id');
		$this->user_id = $this->session->userdata('user_id');
		$this->username = $this->session->userdata('username');
    }
    
	function index()
	{

		if($this->role_id=="3"){
			$limit = 0;
			$total_sms = 0;
			$sms_otomatis = 0;
			$contacts = 0;
			$sms_all = 0;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
			
		}else{
			$limit = 0;
			$total_sms = 0;
			$sms_otomatis = 0;
			$contacts = 0;
			$sms_all = 0;
		
			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
		}

		$y_telkomsel = 0;
		$y_indosat = 0;
		$y_xl = 0;
		$y_axis = 0;
		$y_smartfren = 0;
		$y_three = 0;
		$y_other = 0;

		/* foreach ($this->getdata_all_provider() as $value) {
			
			switch ($value->provider) {
				case 'Telkomsel':
					$y_telkomsel = round($value->total/$sms_all * 100);
					break;
				case 'Indosat':
					$y_indosat = round($value->total/$sms_all * 100);
					break;
				case 'XL':
					$y_xl = round($value->total/$sms_all * 100);
					break;
				case 'AXIS':
					$y_axis = round($value->total/$sms_all * 100);
					break;
				case 'Smartfren':
					$y_smartfren = round($value->total/$sms_all * 100);
					break;
				case 'Three':
					$y_three = round($value->total/$sms_all * 100);
					break;
				case null:
					$y_other = round($value->total/$sms_all * 100);
					break;
				
				default:
					# code...
					break;
			}

		} */

		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'limit' => number_format($limit),
			'total_sms' => number_format($total_sms),
			'limit_persent' => $limit_persent,
			'sms_otomatis' => $sms_otomatis,
			'contacts' => $contacts,
			'y_telkomsel' => 0,
			'y_indosat' => 0,
			'y_xl' => 0,
			'y_axis' => 0,
			'y_smartfren' => 0,
			'y_three' => 0,
			'y_other' => 0
		);
		
		page_view($this->title, 'view', $content_data);
	}
	
	function report()
	{
		$content_data = array(
			'base_url' => base_url(),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'page' => $this->uri->segment(1)
		);
		
		page_view('Report', 'data', $content_data);
	}

	function getdata_monthly($month,$provider){

		if($this->role_id=="3"){
			$data = $this->db->select("count(*) as total")
			->get_where('v_tb_keylog','sender = "'.$this->username.'" and month(created_at) = "'.$month.'" and provider = "'.$provider.'"')
			->row()->total;
		}else{
			$data = $this->db->select("count(*) as total")
			->get_where('v_tb_keylog','month(created_at) = "'.$month.'" and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}

		return $data;
	}

	function getdata_provider($provider){

		if($provider == null){
			$where = 'provider is null';
		}else{
			$where = 'provider = "'.$provider.'"';
		}

		if($this->role_id=="3"){
			$data = $this->db->select("month(created_at) as month, count(1) as total")
			->from('v_tb_keylog')
			->where('year(created_at) = year(now()) and sender = "'.$this->username.'" AND '.$where)
			->group_by('month(created_at)')
			->get()->result();
		}else{
			$data = $this->db->select("month(created_at) as month, count(1) as total")
			->from('v_tb_keylog')
			->where('year(created_at) = year(now()) and tenant_id = "'.$this->tenant_id.'" AND '.$where)
			->group_by('month(created_at)')
			->get()->result();
		}

		$result = array(0,0,0,0,0,0,0,0,0,0,0,0);

		foreach ($data as $value) {
			
			switch ($value->month) {
				case '1':
					$result[0] = $value->total;
					break;
				case '2':
					$result[1] = $value->total;
					break;
				case '3':
					$result[2] = $value->total;
					break;
				case '4':
					$result[3] = $value->total;
					break;
				case '5':
					$result[4] = $value->total;
					break;
				case '6':
					$result[5] = $value->total;
					break;
				case '7':
					$result[6] = $value->total;
					break;
				case '8':
					$result[7] = $value->total;
					break;
				case '9':
					$result[8] = $value->total;
					break;
				case '10':
					$result[9] = $value->total;
					break;
				case '11':
					$result[10] = $value->total;
					break;
				case '12':
					$result[11] = $value->total;
					break;
				
				default:
					# code...
					break;
			}

		}

		return $result;
	}

	function getdata_all_provider(){

		if($this->role_id=="3"){
			$data = $this->db->select("provider, count(1) as total")
			->from('v_tb_keylog')
			->where('year(created_at) = year(now()) and sender = "'.$this->username.'"')
			->group_by('provider')
			->get()->result();
			
		}else{
			$data = $this->db->select("provider, count(1) as total")
			->from('v_tb_keylog')
			->where('year(created_at) = year(now()) and tenant_id = "'.$this->tenant_id.'"')
			->group_by('provider')
			->get()->result();
		}

		return $data;
	}

	function getdata_grafik()
	{

		$gm_telkomsel = $this->getdata_provider('Telkomsel');
		$gm_indosat = $this->getdata_provider('Indosat');
		$gm_xl = $this->getdata_provider('XL');
		$gm_axis = $this->getdata_provider('AXIS');
		$gm_smartfren = $this->getdata_provider('Smartfren');
		$gm_three = $this->getdata_provider('Three');
		$gm_other = $this->getdata_provider(null);

		$content_data = array(
			'gm_telkomsel' => $gm_telkomsel,
			'gm_indosat' => $gm_indosat,
			'gm_xl' => $gm_xl,
			'gm_axis' => $gm_axis,
			'gm_smartfren' => $gm_smartfren,
			'gm_three' => $gm_three,
			'gm_other' => $gm_other,
		);
		
		echo json_encode($content_data);
	}

	function getdata_summary()
	{

		if($this->role_id=="3"){
			$total_call = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_sent = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and updated_by = "'.$this->user_id.'"')->row()->total;
		}else{
			$total_sms_received = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_sent = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog','month(created_at) = month(now()) and tenant_id = "'.$this->tenant_id.'"')->row()->total;
		}

		$content_data = array(
			'total_sms_received' => number_format($total_sms_received),
			'total_sms_sent' => number_format($total_sms_sent),
			'total_sms_sending' => number_format($total_sms_sending),
			'total_sms_failed' => number_format($total_sms_failed),
		);
		
		echo json_encode($content_data);
	}
	
}
