<?php
 class Groupmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}

 	function get_list($data)
 	{ 		
		$this->db->select('*');		
 	 	$this->db->from('group');
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['name']!='')
			$this->db->like('name',$data['name'],'both');
        $rs=$this->db->get(); 	    
		$groups=$rs->result_array(); 	    
		return $groups;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('group.id');	
 	 	$this->db->from('group');
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['name']!='')
			$this->db->like('name',$data['name'],'both');
        $rs=$this->db->get();	    
		$groups=$rs->num_rows();		
 		return $groups;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('group',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('group',$data,array('id'=>$id));	

 	}
		
	function get_group_details($id)
 	{
 		$this->db->select('group.name as groupName,description ,group_members.name,phone');		
 	 	$this->db->from('group');
 	 	$this->db->join('group_members','group_members.group_id=group.id','right');
 	 	$this->db->where('group.id',$id);
		//return $this->get_row();
		$rs=$this->db->get(); 	    
		$groups=$rs->result_array(); 	    
		return $groups;
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('group');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}
	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('group',array('status'=>strtoupper($val)));	
 	}
 	function change_member_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('group_members',array('status'=>strtoupper($val)));	
 	}
 	
 	function del($id)
 	{
 		$this->db->delete('group',array('id'=>$id)); 		
 	}
		
	function group_option()
 	{
 		$this->db->select('id,name as title');
 		$this->db->from('group');
		$this->db->where('user_id',$this->user_id);
 	 	$this->db->order_by('name','asc');
 		return $this->get_assoc(); 
 	}
	
 	function duplicate_group_check($groupname,$param)
 	{
 		$this->db->where('name',$groupname);
 		$this->db->where('name <>',"$param");
		$this->db->where('user_id',$this->user_id);
 		$this->db->from('group');
 		return $this->db->count_all_results();
 	}
	
	/************************* Contact ***************************/

	function get_member_list_report($data)
 	{ 		
		$this->db->select('group_members.name,group.name groupname,group_members.phone');	
 	 	$this->db->from('group_members');
 	 	$this->db->join('group','group.id=group_members.group_id','left');
 	 	$this->db->where('group_members.created_by',$this->user_id);
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['name']!='')
			$this->db->like('group_members.name',$data['name'],'both');
        if($data['phone']!='')
			$this->db->where('group_members.phone',$data['phone']);
        $rs=$this->db->get(); 	    
		$groups=$rs->result_array(); 	    
		return $groups;
 	}
 	function get_memeber_list($data)
 	{ 		
		$this->db->select('group_members.*,group.name groupname');		
 	 	$this->db->from('group_members');
 	 	$this->db->join('group','group.id=group_members.group_id','left');
 	 	$this->db->where('group_members.created_by',$this->user_id);
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['name']!='')
			$this->db->like('group_members.name',$data['name'],'both');
        if($data['phone']!='')
			$this->db->where('group_members.phone',$data['phone']);
        $rs=$this->db->get(); 	    
		$groups=$rs->result_array(); 	    
		return $groups;
 	}
 	
 	function member_count_list($data)
 	{
 		$this->db->select('group_members.id');	
 	 	$this->db->from('group_members');
 	 	$this->db->join('group','group.id=group_members.group_id','left');
 	 	$this->db->where('group_members.created_by',$this->user_id);
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['name']!='')
			$this->db->like('group_members.name',$data['name'],'both');
        if($data['phone']!='')
			$this->db->where('group_members.phone',$data['phone']);
        $rs=$this->db->get();	    
		$groups=$rs->num_rows();		
 		return $groups;
 	}
 	
	function addMember($data)
 	{ 		
		$this->db->insert('group_members',$data);
 		return $this->db->insert_id();
 	}
	
 	function editMember($id='',$data)
 	{
		return $this->db->update('group_members',$data,array('id'=>$id));	

 	}
		
	function get_group_member_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('group_members');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record_member($id)
 	{
 		$this->db->select('*');
		$this->db->from('group_members');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}

 	function delMember($id)
 	{
 		$this->db->delete('group_members',array('id'=>$id)); 		
 	}
 }
 
?>