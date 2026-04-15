<?php
 class Ajaxmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct();
		$this->store_id = $this->session->userdata('user_userid');
 	}

	function get_category($brand_id)
	{
		$this->db->select('id,category_name title');
		$this->db->from('category_list');
		//$this->db->where('store_id',$this->store_id);
		$this->db->where("FIND_IN_SET('$brand_id',brand_id) !=",0);
		$this->db->order_by('category_name','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	function get_subcategory($category_id)
	{
		$this->db->select('id,subcategory_name title');
		$this->db->from('subcategory_list');
		//$this->db->where('store_id',$this->store_id);
		$this->db->where("FIND_IN_SET('$category_id',category_id) !=",0);
		$this->db->order_by('subcategory_name','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	
	function get_product($category_id)
	{
		$this->db->select('id,product_name title');
		$this->db->from('product_list');
		$this->db->where('store_id',$this->store_id);
		$this->db->where('category_id',$category_id);
		$this->db->order_by('product_name','asc');
		$query = $this->db->get();
		$result = $query->result_array();
		return $result;
	}
	

 }

?>