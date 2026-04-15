<?php

class Apimodel extends Bindu_Model {
   
    function check_user($data) {
        $this->db->select('id,username');
        $this->db->from('user');
        $this->db->where('username', $data['username']);
        //$this->db->where('passwd', $data['passwd']);
        $this->db->where('APIKEY', $data['api_key']);
        $this->db->where('SECRETKEY', $data['api_secret']);
        $query = $this->db->get();
        return $query->row_array();
    }
	
	function add($data)
 	{ 		
		$this->db->insert('sentmessages',$data);
 		return $this->db->insert_id();
 	}
}

?>
