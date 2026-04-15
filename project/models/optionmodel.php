<?php

class Optionmodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
		$this->admin_group_id = $this->session->userdata('user_group_id');
		
    }

    function get_country() {
        $this->db->select('id,name title');
        $this->db->from('country');
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_user() {
        $this->db->select("user.id, CONCAT(username, '(', lower(LEFT(title,1)), ')') AS title", FALSE);
        $this->db->from('user');
        $this->db->join('user_group', 'user_group.id=user.id_user_group', 'left');
        //$this->db->where('id_user_group !=', 1);
        //if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        //}
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_user_for_rate($exsisting_users = '') {
        $this->db->select("user.id, CONCAT(username, '(', lower(LEFT(title,1)), ')') AS title", FALSE);
        $this->db->from('user');
        $this->db->join('user_group', 'user_group.id=user.id_user_group', 'left');
        $this->db->where('user.created_by', $this->user_id);
        $this->db->where_not_in('user.id', explode(",", $exsisting_users));
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_user_array() {
        //$this->db->select('user.id, CONCAT(username, '.', title) AS title', FALSE);
        $this->db->select("user.id, CONCAT(username, '(', lower(LEFT(title,1)), ')') AS title", FALSE);
        $this->db->from('user');
        $this->db->join('user_group', 'user_group.id=user.id_user_group', 'left');
        $this->db->where('id_user_group !=', 1);
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        $this->db->order_by('title', 'asc');
        $rs = $this->db->get();
        return $rs->result_array();
    }

    function getOperatorOption() {
        $this->db->select('id,full_name');
        $this->db->from('operator');
        $this->db->order_by('id', 'asc');
        $rs = $this->db->get();
        $operator = $rs->result_array();
        return $operator;
    }

    function get_operator() {
        $this->db->select('id,full_name title');
        $this->db->from('operator');
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_channel() {
        $this->db->select('id,name title');
        $this->db->from('channel');
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_rate() {
        $this->db->select('id,name title');
        $this->db->from('rates');
        
        if($this->session->userdata('user_group_id') !=1) // Super admin 
            $this->db->where('created_by', $this->session->userdata('user_userid'));
        
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

}

?>