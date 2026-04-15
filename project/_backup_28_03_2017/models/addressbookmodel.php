<?php
 class Addressbookmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 		$this->user_id = $this->session->userdata('user_userid');
 	}
 	 	 	
 	function get_list($data)
 	{ 		
		$this->db->select('*');		
 	 	$this->db->from('addressbooks');
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['title']!='')
			$this->db->like('title',$data['title'],'both');
        $rs=$this->db->get(); 	    
		$addressbookss=$rs->result_array(); 	    
		return $addressbookss;
 	}
 	
 	function count_list($data)
 	{
 		$this->db->select('addressbooks.id');	
 	 	$this->db->from('addressbooks');
 	 	$this->db->where('user_id',$this->user_id);
 	 	if($data['title']!='')
			$this->db->like('title',$data['title'],'both');
        $rs=$this->db->get();	    
		$addressbookss=$rs->num_rows();		
 		return $addressbookss;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('addressbooks',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data)
 	{
		return $this->db->update('addressbooks',$data,array('id'=>$id));	

 	}
		
	function get_addressbooks_details($id)
 	{
 		$this->db->select('');		
 	 	$this->db->from('addressbooks');
 	 	$this->db->where('id',$id);
		return $this->get_row();
 	}	
 	
 	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('addressbooks');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}
	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('addressbooks',array('status'=>strtoupper($val)));	
 	}
 	
 	function del($id)
 	{
 		$this->db->delete('addressbooks',array('id'=>$id)); 		
 	}
		
	function addressbooks_option()
 	{
 		$this->db->select('id,title');
 		$this->db->from('addressbooks');
		$this->db->order_by('title','asc');
 		return $this->get_assoc(); 
 	}
	
 	function duplicate_addressbooks_check($addressbooksname,$param)
 	{
 		$this->db->where('title',$addressbooksname);
 		$this->db->where('title <>',"$param");
 		$this->db->from('addressbooks');
 		return $this->db->count_all_results();
 	}
 }
 
?>