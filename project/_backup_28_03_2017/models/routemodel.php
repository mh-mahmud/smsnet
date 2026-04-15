<?php
 class Routemodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function get_list($data)
 	{ 		
		$this->db->select('routes.*,user.username user, operator.full_name operator, operator.prefix prefix, channel.name channel,country.phonecode country_code');		
 	 	$this->db->from('routes');
 	 	$this->db->join('user','user.id=routes.user_id','left');
 	 	$this->db->join('operator','operator.id=routes.operator_id','left');
 	 	$this->db->join('channel','channel.id=routes.channel_id','left');
 	 	$this->db->join('country','country.id=operator.country_id','left');
 	 	if($data['user_id']>0)
			$this->db->where('routes.user_id',$data['user_id']);
        if($data['operator_id']>0)
			$this->db->where('routes.operator_id',$data['operator_id']);
        if($data['channel_id']>0)
			$this->db->where('routes.channel_id',$data['channel_id']);
        $rs=$this->db->get(); 	    
		$routess=$rs->result_array(); 	    
		return $routess;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('routes.id');	
 	 	$this->db->from('routes');
 	 	$this->db->join('user','user.id=routes.user_id','left');
 	 	$this->db->join('operator','operator.id=routes.operator_id','left');
 	 	$this->db->join('channel','channel.id=routes.channel_id','left');
 	 	if($data['user_id']>0)
			$this->db->where('user_id',$data['user_id']);
        if($data['operator_id']>0)
			$this->db->where('routes.operator_id',$data['operator_id']);
        if($data['channel_id']>0)
			$this->db->where('channel_id',$data['channel_id']);
        $rs=$this->db->get();	    
		$routess=$rs->num_rows();		
 		return $routess;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('routes',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('routes',$data,array('id'=>$id));	

 	}
	
		
	function get_routes_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('routes');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('routes');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}
	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('routes',array('status'=>strtoupper($val)));	
 	}
 	
 	function del($id)
 	{
 		$this->db->delete('routes',array('id'=>$id)); 		
 	}
		
	function routes_option()
 	{
 		$this->db->select('id,name as title');
 		$this->db->from('routes');
		$this->db->order_by('name','asc');
 		return $this->get_assoc(); 
 	}
	
 	function duplicate_routes_check($routesname,$param)
 	{
 		$this->db->where('short_name',$routesname);
 		$this->db->where('short_name <>',"$param");
 		$this->db->from('routes');
 		return $this->db->count_all_results();
 	}
 }
 
?>