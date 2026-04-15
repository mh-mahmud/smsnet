<?php
 class countrymodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function get_list($data)
 	{ 		
		$this->db->select('');		
 	 	$this->db->from('country');
 	 	$this->db->order_by('name','ASC');
 	 	$rs=$this->db->get(); 	    
		$countrys=$rs->result_array(); 	    
		return $countrys;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('country.id');	
 	 	$this->db->from('country');
 	 	$rs=$this->db->get();	    
		$countrys=$rs->num_rows();		
 		return $countrys;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('country',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('country',$data,array('id'=>$id));	

 	}
		
	function get_country_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('country');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('country.*');
		$this->db->from('country');
		$this->db->where('country.id',$id);
 		return $this->get_row();
 	}
	
 	function del($id)
 	{
 		$this->db->delete('country',array('id'=>$id)); 		
 	}
	
 	function duplicate_country_check($countryname,$param)
 	{
 		$this->db->where('name',$countryname);
 		$this->db->where('name <>',"$param");
 		$this->db->from('country');
 		return $this->db->count_all_results();
 	}
 }
 
?>