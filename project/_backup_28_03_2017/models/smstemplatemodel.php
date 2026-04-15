<?php
 class Smstemplatemodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function get_list($data)
 	{ 		
		$customerId =  str_replace("'","",implode (",", $this->customerId()));
		$this->db->select('template.*,user.username created_by');		
 	 	$this->db->from('template');
 	 	$this->db->join('user','user.id=template.created_by','left');
 	 	if ($this->session->userdata('user_group_id') != 1) {
           $this->db->where_in('template.created_by',explode(',', $customerId));
        }
        if ($data['title'] != '')
            $this->db->where('title', $data['title']);
        $rs=$this->db->get();     
		$templates=$rs->result_array(); 	    
		return $templates;
 	}
 	
 	function count_list($data)
 	{
 		$customerId =  str_replace("'","",implode (",", $this->customerId()));
		$this->db->select('template.id');	
 	 	$this->db->from('template');
 	 	$this->db->join('user','user.id=template.created_by','left');
 	 	if ($this->session->userdata('user_group_id') != 1) {
           $this->db->where_in('template.created_by',explode(',', $customerId));
        }
        if ($data['title'] != '')
            $this->db->where('title', $data['title']);
        $rs=$this->db->get();	    
		$templates=$rs->num_rows();		
 		return $templates;
 	}
 	function customerId()
	{
		$this->db->select('a.id');
		$this->db->from('user a');
		$this->db->where('a.created_by',$this->user_id);
		$rs = $this->db->get();
		$result = $rs->result_array();
		
		$marray = array_merge(array_unique(array_map('current', $result)),array($this->user_id)); 
		return $marray;
	}
	function add($data)
 	{ 		
		$this->db->insert('template',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('template',$data,array('id'=>$id));	

 	}
		
	function get_smstemplate_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('template');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('template.*');
		$this->db->from('template');
		$this->db->where('template.id',$id);
 		return $this->get_row();
 	}
	
 	function del($id)
 	{
 		$this->db->delete('template',array('id'=>$id)); 		
 	}
	
 	function duplicate_smstemplate_check($templatename,$param)
 	{
 		$this->db->where('title',$templatename);
 		$this->db->where('title <>',"$param");
 		$this->db->where('created_by',$this->user_id);
 		$this->db->from('template');
 		return $this->db->count_all_results();
 	}
 }
 
?>