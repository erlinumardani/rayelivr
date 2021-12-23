<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master_setting extends CI_Model {

	function __construct()
    {
        parent::__construct();
	}

	public function get_setting()
	{
		$sql	= "SELECT
					`id`, 
					`name`, 
					`value`
					FROM 
					`configs`;";
	
		$query = $this->db->query($sql);
		
		$result = $query->result_array();
		$meta	= [];
		if(is_array($result)){
			foreach($result as $keys => $rows){
				$meta = array_merge($meta, [$rows['name'] => $rows['value']]);
			}
		}
		return $meta;
	}

