<?php
 class Maskmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function old_get_list($data)
 	{ 		
		$this->db->select('*');		
 	 	$this->db->from('senderid');
 	 	$this->db->where('created_by',$this->user_id);
 	 	$rs=$this->db->get(); 	    
		$senderids=$rs->result_array(); 	    
		return $senderids;
 	}
 	
 	function old_count_list($data)
 	{
 		$this->db->select('senderid.id');	
 	 	$this->db->from('senderid');
 	 	$this->db->where('created_by',$this->user_id);
		$rs=$this->db->get();	    
		$senderids=$rs->num_rows();		
 		return $senderids;
 	}
	function get_list($data)
 	{ 		
		$this->db->select('*');		
 	 	$this->db->from('senderid');
 	 	//$this->db->where('created_by',$this->user_id);
 	 	$rs=$this->db->get(); 	    
		$senderids=$rs->result_array(); 	    
		return $senderids;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('senderid.id');	
 	 	$this->db->from('senderid');
 	 	//$this->db->where('created_by',$this->user_id);
		$rs=$this->db->get();	    
		$senderids=$rs->num_rows();		
 		return $senderids;
 	}
	
	function get_assign_list($data)
 	{ 		
		$this->db->select('*');		
 	 	$this->db->from('senderid');
		$this->db->where('created_by',$this->user_id);
		$rs=$this->db->get();

		$result = array();
        foreach ($rs->result() as $val) {
            $result['id'] = $val->id;
            $result['senderID'] = $val->senderID;
            $result['user_id'] = $val->user_id;
            
			$result['all_users'] = $this->all_users($val->user_id);
			$new_array[] = $result;
        }

        return $new_array;
 	}
 	function all_users($user_id)
	{
		$this->db->select('user.username,user_group.title');		
 	 	$this->db->from('user');
 	 	$this->db->join('user_group','user.id_user_group=user_group.id','left');
		$this->db->where_in('user.id',explode(',', $user_id));
		$rs=$this->db->get();
		return $rs->result_array();
	}
	
 	function count_assign_list($data)
 	{
 		$this->db->select('id');	
 	 	$this->db->from('senderid');
 	 	$this->db->where('created_by',$this->user_id);
		$rs=$this->db->get();	    
		$senderids=$rs->num_rows();		
 		return $senderids;
 	}
 	
	function old_add($data)
 	{ 		
		$this->db->insert('senderid',$data);
 		return $this->db->insert_id();
 	}
	
	function add($data)
 	{ 		
		$this->db->insert('senderid',$data);
 		return $this->db->insert_id();
 	}
	
 	function old_edit($id='',$data)
 	{
		return $this->db->update('senderid',$data,array('id'=>$id));	

 	}
	function edit($id='',$data)
 	{
		return $this->db->update('senderid',$data,array('id'=>$id));	

 	}
	function edit_assign($id='',$data)
 	{
		return $this->db->update('senderid',$data,array('id'=>$id));	

 	}
		
	function get_senderid_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('senderid');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('senderid');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}
		
 	function del($id)
 	{
 		$this->db->delete('senderid',array('id'=>$id)); 		
 	}
		
	function duplicate_mask_check($senderidname,$param)
 	{
 		$this->db->where('senderID',$senderidname);
 		$this->db->where('senderID <>',"$param");
 		$this->db->from('senderid');
		$this->db->where('created_by',$this->user_id);
 		return $this->db->count_all_results();
 	}
 }
 
?>