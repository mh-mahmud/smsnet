<?php
 class Channelmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function get_list($data)
 	{ 		
		$this->db->select('channel.*,operator.full_name operator_name');		
 	 	$this->db->from('channel');
 	 	$this->db->join('operator','operator.id=channel.operator_id','left');
 	 	if($data['name']!='')
			$this->db->like('channel.name',$data['name'],'both');
        if($data['operator_id']!='')
			$this->db->where('channel.operator_id',$data['operator_id']);
        if($data['channel_type']!='')
			$this->db->where('channel.channel_type',$data['channel_type']);
        $rs=$this->db->get(); 	    
		$channels=$rs->result_array(); 	    
		return $channels;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('channel.id');	
 	 	$this->db->from('channel');
 	 	$this->db->join('operator','operator.id=channel.operator_id','left');
 	 	if($data['name']!='')
			$this->db->like('channel.name',$data['name'],'both');
        if($data['operator_id']!='')
			$this->db->where('channel.operator_id',$data['operator_id']);
        if($data['channel_type']!='')
			$this->db->where('channel.channel_type',$data['channel_type']);
        $rs=$this->db->get();	    
		$channels=$rs->num_rows();		
 		return $channels;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('channel',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('channel',$data,array('id'=>$id));	

 	}
		
	function get_channel_details($id)
 	{
 		$this->db->select('channel.*,operator.full_name operator_name');		
 	 	$this->db->from('channel');
 	 	$this->db->join('operator','operator.id=channel.operator_id','left');
 	 	$this->db->where('channel.id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('channel.*');
		$this->db->from('channel');
		$this->db->join('operator','operator.id=channel.operator_id','left');
 		$this->db->where('channel.id',$id);
 		return $this->get_row();
 	}
	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('channel',array('status'=>strtoupper($val)));	
 	}
 	
 	function del($id)
 	{
 		$this->db->delete('channel',array('id'=>$id)); 		
 	}
		
	function channel_option()
 	{
 		$this->db->select('id,name as title');
 		$this->db->from('channel');
		$this->db->order_by('name','asc');
 		return $this->get_assoc(); 
 	}
	function getOperatorOption()
 	{
 		$this->db->select('id,full_name as title');
 		$this->db->from('operator');
		$this->db->order_by('id','asc');
 		return $this->get_assoc(); 
 	}
	
 	function duplicate_channel_check($channelname,$param)
 	{
 		$this->db->where('name',$channelname);
 		$this->db->where('name <>',"$param");
 		$this->db->from('channel');
 		return $this->db->count_all_results();
 	}
 }
 
?>