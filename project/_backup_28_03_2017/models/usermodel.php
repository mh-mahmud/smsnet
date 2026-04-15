<?php

class Usermodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
    }

    function get_listw() {
        $this->db->select('id');
        $this->db->from('user');
        //$this->db->where('id !=',1);
        $rs = $this->db->get();
        $users = $rs->result_array();
        return $users;
    }

    function get_list($data) {
        $this->db->select('user.id,username,user.address,mobile,ag.title user_type, rt.name rate_name, user.status');
        $this->db->from('user');
        $this->db->join('user_group ag', 'ag.id = user.id_user_group', 'left');
        $this->db->join('rates rt', 'rt.id = user.rate_id', 'left');
        $this->db->where('user.id_user_group !=', 1);
        
        if ($this->session->userdata('user_group_id') != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }

        if ($data['user_id'] != '')
            $this->db->where('user.id', $data['user_id']);
        if ($data['mobile'] != '')
            $this->db->where('user.mobile', $data['mobile']);
        if ($data['id_user_group'] != '')
            $this->db->where('user.id_user_group', $data['id_user_group']);
        if ($data['status'] != '')
            $this->db->where('user.status', $data['status']);
        $rs = $this->db->get();
        $users = $rs->result_array();
        return $users;
    }

    function count_list($data) {
        $this->db->select('user.id');
        $this->db->from('user');
        $this->db->join('user_group ag', 'ag.id = id_user_group', 'left');
        $this->db->where('user.id_user_group !=', 1);
        if ($this->session->userdata('user_group_id') != 1) {
            $this->db->where('created_by', $this->user_id);
        }
        if ($data['user_id'] != '')
            $this->db->where('user.id', $data['user_id']);
        if ($data['mobile'] != '')
            $this->db->where('user.mobile', $data['mobile']);
        if ($data['id_user_group'] != '')
            $this->db->where('user.id_user_group', $data['id_user_group']);
        if ($data['status'] != '')
            $this->db->where('user.status', $data['status']);
        $rs = $this->db->get();
        $users = $rs->num_rows();
        return $users;
    }

    function add($data) {
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    function edit($id = '', $data, $shop_id = '') {
        return $this->db->update('user', $data, array('id' => $id));
    }

    function edit_password($id) {
        $timezone = "Asia/Dhaka";
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set($timezone);
        $data['passwd'] = $this->input->post('new_passwd');
        return $this->db->update('user', $data, array('id' => $id));
    }

    function get_user_details($id) {
        $this->db->select('ad.username,ad.email,ad.mobile,ad.address,ad.status,ag.title user_type');
        $this->db->from('user ad');
        $this->db->join('user_group ag', 'ag.id = ad.id_user_group', 'left');
        $this->db->where('ad.id', $id);
        return $this->get_row();
    }

    function get_record($id) {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function change_status($id, $val) {
        $this->db->where('id', $id);
        $this->db->update('user', array('status' => strtoupper($val)));
    }

    function del($id) {
        $this->db->delete('user', array('id' => $id));
    }

    function user_option() {
        $this->db->select('id,username title');
        $this->db->from('user');
        $this->db->where('id_user_group >', 1);
        $this->db->order_by('username', 'asc');
        return $this->get_assoc();
    }

    function duplicate_email_check($email, $param) {
        $this->db->where('email', $email);
        $this->db->where('email <>', "$param");
        $this->db->from('user');
        return $this->db->count_all_results();
    }

    function duplicate_user_check($username, $param) {
        $this->db->where('username', $username);
        $this->db->where('username <>', "$param");
        $this->db->from('user');
        return $this->db->count_all_results();
    }

    function access_check($id) {
        $this->db->select('APIKEY,SECRETKEY');
        $this->db->from('user');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function get_list_report($data) {
        $this->db->select('user.username,mobile,address,ag.title user_type,user.status');
        $this->db->from('user');
        $this->db->join('user_group ag', 'ag.id = user.id_user_group', 'left');
        $this->db->where('user.id_user_group !=', 1);
        if ($this->session->userdata('user_group_id') != 1) {
            $this->db->where('created_by', $this->user_id);
        }
        if ($data['username'] != '')
            $this->db->like('user.username', $data['username'], 'both');
        if ($data['mobile'] != '')
            $this->db->where('user.mobile', $data['mobile']);
        if ($data['id_user_group'] != '')
            $this->db->where('user.id_user_group', $data['id_user_group']);
        if ($data['status'] != '')
            $this->db->where('user.status', $data['status']);
        $rs = $this->db->get();
        $users = $rs->result_array();
        return $users;
    }

}

?>