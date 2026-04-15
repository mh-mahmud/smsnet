<?php
 class Testmodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 		
 	}
 	 	 	
 	function getAllSenderId()
 	{ 		
		$this->db->select('senderID');		
 	 	$this->db->from('sentmessages');
 	 	$rs=$this->db->get(); 
		if($rs->num_rows()>0){
			$deposit_historys=$rs->result_array(); 	    
			return $deposit_historys;
		}else{
			return array();
		}	    

 	}

 	function updateAllSenderId($id)
 	{
		$data['senderID'] = $this->db->query("call get_new_id(1)");
		return $this->db->update('sentmessages',$data,array('id'=>$id));	

 	}
 }
 
?>