<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_render 
{
		
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('master_setting');
		
		$this->userlevel	= $this->CI->session->userdata('userlevel');
		$system				= array("base_url" => base_url());
		$this->settings		= array_merge($system,$this->CI->master_setting->get_setting());

	}
	
	public function set_layout($layout,$data=NULL)
	{
			
		return array("page_content" => $this->CI->parser->parse($layout,$data,true));
	}
	
	public function set_menu($data=NULL,$active_menu=NULL,$level=NULL)
	{
	
		$menu = $this->CI->master_setting->get_menu( strtolower($this->userlevel), strtolower($this->skill) );

		
		if($this->userlevel=='agent' && $this->skill=='outbound'){
			$prodMenu	= $this->CI->master_setting->get_prod_menu();
			$menu		= array_merge($prodMenu,$menu);

			//print_r($menu);
		}

		if($this->userlevel=='agent' && $this->skill=='inbound'){
			$ticketMenu	= $this->CI->master_setting->get_ticket_menu();
			$menu		= array_merge($ticketMenu,$menu);
		}
		
		$menus = array('id' => array(), 'parents' => array());
		
		foreach ($menu as $menu_key)
		{
			$menus['id'][$menu_key['id']] = $menu_key;
			$menus['parents'][$menu_key['parent']][] = $menu_key['id'];
		}

		// print_r($menus);



		
		$this->menus	= array("menus" => $menu);
		$file_photo		= 'assets/img/profile/'.$this->CI->session->userdata('photo');
		$file_type		= get_mime_by_extension($file_photo);
		
		if($file_type=='image/jpeg' && file_exists($file_photo))
		{
			$photo = $this->CI->session->userdata('photo');
		}
		elseif($file_type=='image/png' && file_exists($file_photo))
		{
			$photo = $this->CI->session->userdata('photo');
		}
		else
		{
			$photo = 'default.jpg';
			$this->CI->session->set_userdata(array("photo"=>$photo));
		}
		
		$photos = array( "photos" => $photo );
		
		$data	= array(
						"photos"	=> $photo,
						"menus"		=> $menus
						);
		
		return array("menu" => $this->CI->parser->parse("master/menus",array_merge($data,
																					$this->settings, 
																					array("active_menu"=>$active_menu),
																					$this->CI->session->userdata()
																					),true) ) ;
		
	}

	public function page($class_name,$page,$data=array(),$is_backend=FALSE)
	{

		if($is_backend)
		{
			$menu			= $this->set_menu($class_name,'',$this->userlevel);
			$master_page	= "master/portal";
		}
		else
		{
			$menu			= array();
			$master_page	= "master/page";
		}
		
		$layout		= $this->set_layout($page,array_merge(array(
																"thispage"	 => base_url().$page, 
																"thisparent" => $class_name,
																),
																$this->settings,
																$data));
										
		return $this->CI->parser->parse($master_page, array_merge($this->settings,$layout,$menu));
	}

	public function content($page,$data=array(),$path=null)
	{
		return $this->CI->parser->parse(strtolower($page),array_merge(
																	$this->settings,
																	array(
																			"thisparent"	=> base_url($path),
																			"parent_page"	=> $path,
																			"thispage"		=> $page,
																			"parentpage"	=> str_replace('/','',ucwords($path)),
																			"pageview"		=> str_replace('/','',ucwords($page)),
																		),
																	$data
																	));
		
	}
	
}
