<?php

class Ratemodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
    }

    function get_rate_details($id) {
        return $this->db->select('')
                        ->from('rates')
                        ->where('id', $id)
                        ->get()
                        ->row();
    }

    function get_operator_rate_details($rate_id) {
        return $this->db->select('operator.full_name, operator_sms_rate.operator_id,operator_sms_rate.buying_rate, operator_sms_rate.selling_rate')
                        ->from('operator_sms_rate')
                        ->join('operator', 'operator.id=operator_sms_rate.operator_id', 'inner')
                        ->where('rate_id', $rate_id)
                        ->group_by('operator_id', 'ASC')
                        ->get()
                        ->result_array();
    }

    function get_all_defind_users() {
        $this->db->select('users');
        $this->db->from('rates');
        $this->db->where('created_by', $this->user_id);
        $rs = $this->db->get();
        $users = $rs->result_array();
        return $users;
    }

    function get_list($data) {
        $this->db->select('');
        $this->db->from('rates');
        if ($this->session->userdata('user_group_id') != 1) {
            $this->db->where('created_by', $this->user_id);
        }
        if ($data['name'] != '')
            $this->db->like('name', $data['name'], 'both');
        $rs = $this->db->get();
        $users = $rs->result_array();
        return $users;
    }

    function count_list($data) {
        $this->db->select('id');
        $this->db->from('rates');
        if ($this->session->userdata('user_group_id') != 1) {
            $this->db->where('created_by', $this->user_id);
        }
        if ($data['name'] != '')
            $this->db->like('name', $data['name'], 'both');
        $rs = $this->db->get();
        $users = $rs->num_rows();
        return $users;
    }

    function rateadd($data) {
        $this->db->insert('rates', $data);
        return $this->db->insert_id();
    }

    function update_rate($rate_id, $data) {
        return $this->db->update('rates', $data, array('id' => $rate_id));
    }

    function rateupdate($id, $data) {
        return $this->db->update('rates', $data, array('id' => $id));
    }

    function deleterate($id) {
        $this->db->delete('operator', array('id' => $id));
    }

    function del($id) {
        $this->db->delete('rates', array('id' => $id));
    }

    function deldefaultrate($rate_id) {
        $this->db->where('rate_id', $rate_id);
        $this->db->delete('operator_sms_rate');
    }

    function delsmsprice($get_rate_user) {
        $this->db->where_in('user_id', explode(",", $get_rate_user));
        $this->db->delete('smsprice');
    }

    function get_rate_user_by_rate($id) {
        $this->db->select('users');
        $this->db->from('rates');
        $this->db->where('id', $id);
        return $this->get_row();
    }

}

?>