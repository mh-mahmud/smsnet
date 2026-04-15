<?php

 class Usergroupmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 
 	}
 	
 	function group_option()
 	{
 		$this->db->select('id,title');
 		$this->db->from('user_group');
		if($this->session->userdata('user_group_id')==2)
		{
			$this->db->where('id !=',1);
			$this->db->where('id !=',2);
		}
		$this->db->where('status','Active');
		$this->db->order_by('id','asc');
 		return $this->get_assoc(); 
 	}
 	
 }
?>
