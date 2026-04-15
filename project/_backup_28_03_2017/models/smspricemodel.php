<?php
 class Smspricemodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 	}
 	 	 	
 	function get_list()
 	{ 		
		$this->db->select('smsprice.id,reseller_cost_per_unit,customer_cost_per_unit,smsprice.status,smsprice.created,op.full_name operator');		
 	 	$this->db->from('smsprice');
 	 	$this->db->join('operator op', 'op.id = smsprice.operator_id', 'left');
 	 	$this->db->where('smsprice.created_by',$this->user_id);
 	 	$rs=$this->db->get(); 	    
		$smsprices=$rs->result_array(); 	    
		return $smsprices;
 	}
 	
 	function count_list()
 	{
 		$this->db->select('smsprice.id');	
 	 	$this->db->from('smsprice');
 	 	$this->db->join('operator op', 'op.id = smsprice.operator_id', 'left');
 	 	$this->db->where('smsprice.created_by',$this->user_id);
 	 	$rs=$this->db->get();	    
		$smsprices=$rs->num_rows();		
 		return $smsprices;
 	}
 	
	function add($data)
 	{ 		
		$this->db->insert('smsprice',$data);
 		return $this->db->insert_id();
 	}
	
 	function edit($id='',$data,$shop_id='')
 	{
		return $this->db->update('smsprice',$data,array('id'=>$id));	

 	}
	function get_record($id)
 	{
 		$this->db->select('*');
		$this->db->from('smsprice');
 		$this->db->where('id',$id);
 		return $this->get_row();
 	}
	
 	function change_status($id,$val)
 	{
 	   $this->db->where('id',$id);
 	   $this->db->update('smsprice',array('status'=>strtoupper($val)));	
 	}
 	
 	function del($id)
 	{
 		$this->db->delete('smsprice',array('id'=>$id)); 		
 	}
		
	function duplicateOperatorCheck($operator_id,$param)
 	{
 		$this->db->where('created_by',$this->user_id);
 		$this->db->where('operator_id',$operator_id);
 		$this->db->where('operator_id <>',"$param");
 		$this->db->from('smsprice');
 		return $this->db->count_all_results();
 	}
 }
 
?>