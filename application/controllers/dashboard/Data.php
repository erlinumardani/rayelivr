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

	function getdata_grafik_monthly($filter){

		if($filter == null){
			$where = 'node is null';
		}else{
			$where = 'node = "'.$filter.'"';
		}

		$data = $this->db->select("month(row_date) as month, count(1) as total")
			->from('tb_keylog')
			->where('year(row_date) = year(now()) and '.$where)
			->group_by('month(row_date)')
			->get()->result();

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

		$kodepos = $this->getdata_grafik_monthly('MenuCariKodePos');
		$kec_kel = $this->getdata_grafik_monthly('MenuKodePosBasedOnKota');
		$notelp = $this->getdata_grafik_monthly('MenuNoTelp');
		$nama_alamat = $this->getdata_grafik_monthly('MenuQuestionNamaAlamat');

		$content_data = array(
			'kodepos' => $kodepos,
			'kec_kel' => $kec_kel,
			'notelp' => $notelp,
			'nama_alamat' => $nama_alamat,
		);
		
		echo json_encode($content_data);
	}

	function getdata_summary()
	{

		$all = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog')->row()->total;
		$conventional = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog',array('node'=>'MainMenu','keypress'=>'1'))->row()->total;
		$digital = $this->db->select("count(uniqueid) as total")->get_where('tb_keylog',array('node'=>'MainMenu','keypress'=>'2'))->row()->total;
		$cust_repeatcall = $this->db->select("count(uniqueid) as total")->group_by('uniqueid')->get_where('tb_keylog')->row()->total;

		$content_data = array(
			'all' => number_format($all),
			'conventional' => number_format($conventional),
			'digital' => number_format($digital),
			'cust_repeatcall' => number_format($cust_repeatcall),
		);
		
		echo json_encode($content_data);
	}
	
}
