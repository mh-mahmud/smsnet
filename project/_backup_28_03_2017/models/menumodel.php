<?php
 class Menumodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct();
 	}
 
 	function get_home_menulist($id='')
 	{
 		$user_group_id = $this->session->userdata('user_group_id');
		$this->db->select('id,title menu_title,module_link,icon,icon_color,order,status,user_group_id');
 		$this->db->from('menu');		
		$this->db->where('parent_id',0);
		$this->db->where('id !=',9);
		$this->db->where('status','Publish');		
		$this->db->where("FIND_IN_SET('$user_group_id',user_group_id) !=", 0);		
		$this->db->order_by('order');
		return $this->get_assoc();
 	}
	
	function get_list($id='')
 	{
 		$this->db->select('id,title menu_title,module_link,icon,icon_color,order,status,user_group_id');
 		$this->db->from('menu');
		if($id){
			$this->db->where('parent_id',$id);
		}else{
			$this->db->where('parent_id',0);
		}
		$this->db->order_by('order');
		return $this->get_assoc();
 	}
	
	function get_all_menu()
 	{
 		$this->db->select('id,title menu_title,module_link,order,status,user_group_id');
 		$this->db->from('menu');
		$this->db->order_by('order');
		return $this->get_assoc();
 	}
	
	function add($data)
	{		
 		$this->db->insert('menu',$data);
 		return $this->db->insert_id();
 	}
 	
	 	
 	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('menu');
 		$this->db->where('id',$id);
 	   	return $this->get_row(); 	   	
 	}

 	
 	function count_list($id='')
 	{
 		$this->db->select("count(id) num");
		$this->db->from('menu');
		if($id){
			$this->db->where('parent_id',$id);
		}else{
			$this->db->where('parent_id',0);
		}
 		return $this->get_one();
 	}
 	 	
		
	function count_child($id)
	{
		$this->db->select("count(id) num");
		$this->db->from('menu');
		$this->db->where('parent_id',$id);
 		return $this->get_one();
	}
	
	function update_menu($id,$data)
	{
		$this->db->where('id',$id);
		$this->db->update('menu',$data);
	}
	
	function update_menu_permission($data)
	{
		$this->db->update_batch('menu',$data,'id'); 
	}
 	
 	function del($id)
 	{
 		$this->db->delete('menu',array('id'=>$id));
 	}
 	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('menu',array('status'=>strtoupper($val)));	
 	}
		
 	function get_menu_details($id)
 	{
 		$this->db->select('*');		
 	 	$this->db->from('menu');
 	 	$this->db->where('id',$id);
 		return $this->get_row();
 	}
	
	
	function get_menu_list($id='',$user_group='')
 	{
 		$this->db->select('*');
 		$this->db->from('menu');
		if($id){
			$this->db->where('parent_id',$id);
			$this->db->where("FIND_IN_SET('$user_group',user_group_id) !=",0);
		}else{
			//$this->db->where('parent_id',0);
			$this->db->where("FIND_IN_SET('$user_group',user_group_id) !=",0);
		}
		$this->db->where('status','PUBLISH');
		$this->db->order_by('order');
		return $this->get_assoc();
 	}
	
	
	function get_active_menu_id($url)
	{
		$this->db->select('id,parent_id,user_group_id');		
 	 	$this->db->from('menu');
 	 	$this->db->like('module_link',$url);
		$this->db->order_by('order');
 		return $this->get_row();
	}	
	
 }
 
?>