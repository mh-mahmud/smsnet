<?php

class Operatormodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
        $this->user_id = $this->session->userdata('user_userid');
    }

    function get_list($data) {
        $this->db->select('*');
        $this->db->from('operator');
        if ($data['full_name'] != '')
            $this->db->like('full_name', $data['full_name'], 'both');
        if ($data['short_name'] != '')
            $this->db->where('short_name', $data['short_name']);
        if ($data['prefix'] != '')
            $this->db->where('prefix', $data['prefix']);
        $rs = $this->db->get();
        $operators = $rs->result_array();
        return $operators;
    }

    function count_list($data) {
        $this->db->select('operator.id');
        $this->db->from('operator');
        if ($data['full_name'] != '')
            $this->db->like('full_name', $data['full_name'], 'both');
        if ($data['short_name'] != '')
            $this->db->where('short_name', $data['short_name']);
        if ($data['prefix'] != '')
            $this->db->where('prefix', $data['prefix']);
        $rs = $this->db->get();
        $operators = $rs->num_rows();
        return $operators;
    }

    function add($data) {
        $this->db->insert('operator', $data);
        return $this->db->insert_id();
    }

    function addAdminDefaultPrice($data) {
        $this->db->insert('operator_sms_rate', $data);
        return $this->db->insert_id();
    }

    function addDefaultPrice($data) {
        //echo '<pre>';print_r($data); exit;
        $this->db->where(array('operator_id' => $data['operator_id'], 'rate_id' => $data['rate_id']));
        $q = $this->db->get('operator_sms_rate');
        if ($q->num_rows() > 0) {
            $this->db->update('operator_sms_rate', $data, array('operator_id' => $data['operator_id'], 'rate_id' => $data['rate_id']));
        } else {
            $this->db->insert('operator_sms_rate', $data);
        }

        //$this->db->insert('operator_sms_rate',$data);
        return $this->db->insert_id();
    }

    function deleterate($id) {
        $this->db->delete('operator_sms_rate', array('rate_id' => $id));
    }

    function deleteSmsPrice($id) {
        $this->db->delete('smsprice', array('user_id' => $id));
    }

    function addSmsPrice($data) {
        $this->db->insert('smsprice', $data);
        return $this->db->insert_id();
    }

    function addUnittPrice($data) {
        $this->db->insert('smsprice', $data);
        return $this->db->insert_id();
    }

    function edit($id = '', $data) {
        return $this->db->update('operator', $data, array('id' => $id));
    }

    function get_operator_details($id) {
        $this->db->select('');
        $this->db->from('operator');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function get_record($id) {
        $this->db->select('*');
        $this->db->from('operator');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function get_buying_rate_operatorid($opid) {
        $this->db->select('selling_rate');
        $this->db->from('operator_sms_rate');
        $this->db->where('operator_id', $opid);
        $this->db->where('created_by', $this->user_id);
        $rs = $this->db->get();
        $operators = $rs->result_array();
        return $operators[0]['selling_rate'];
    }

    function change_status($id, $val) {
        $this->db->where('id', $id);
        $this->db->update('operator', array('status' => strtoupper($val)));
    }

    function del($id) {
        $this->db->delete('operator', array('id' => $id));
    }

    function operator_option() {
        $this->db->select('id,name as title');
        $this->db->from('operator');
        $this->db->order_by('name', 'asc');
        return $this->get_assoc();
    }

    function duplicate_operator_check($operatorname, $param) {
        $this->db->where('short_name', $operatorname);
        $this->db->where('short_name <>', "$param");
        $this->db->from('operator');
        return $this->db->count_all_results();
    }

    function getOperatorSmsRate($user_type) {
        $this->db->select('operator.id,operator.full_name,operator_sms_rate.buying_rate,operator_sms_rate.selling_rate');
        $this->db->from('operator');
        $this->db->join('operator_sms_rate', 'operator_sms_rate.operator_id=operator.id', 'left outer');

        if ($user_type == '2' || $user_type == '3') {
            $this->db->where(array('operator_sms_rate.rate_id' => $this->session->userdata('rateid')));
            $rs = $this->db->get();
        } else {

            $this->db->where(array('operator_sms_rate.rate_id' => 0));
            $rs = $this->db->get();

            $query = 'INSERT INTO sms_operator_sms_rate( operator_id, created_by, created )  SELECT id as operator_id, ' . $this->user_id . ' as created_by, now() as created FROM `sms_operator`';

            if ($rs->num_rows() == 0) {
                $this->db->query('INSERT INTO sms_operator_sms_rate( operator_id, created_by, created )  SELECT id as operator_id, ' . $this->user_id . ' as created_by, now() as created FROM `sms_operator`');

                // agian selecting data 
                $this->db->select('operator.id,operator.full_name,operator_sms_rate.buying_rate,operator_sms_rate.selling_rate');
                $this->db->from('operator');
                $this->db->join('operator_sms_rate', 'operator_sms_rate.operator_id=operator.id', 'left outer');
                $this->db->where(array('operator_sms_rate.rate_id' => 0));
                $rs = $this->db->get();
            }
        }


        $operators = $rs->result_array();
        return $operators;
    }

    function getOperatorUnitCost($id) {
        $this->db->select('operator.id,operator_id,unit_cost,operator.full_name');
        $this->db->from('smsprice');
        $this->db->join('operator', 'operator.id=smsprice.operator_id', 'left');
        $this->db->where('smsprice.user_id', $id);
        $rs = $this->db->get();
        $operators = $rs->result_array();
        return $operators;
    }

    function get_buying_rate($opid) {
        $this->db->select('selling_rate');
        $this->db->from('operator_sms_rate');
        $this->db->where('created_by', $this->session->userdata('parent_id'));
        $this->db->where('operator_id', $opid);
        $rs = $this->db->get();
        $operators = $rs->result_array();
        return $operators;
    }

    // default rate update for super admin
    function updatesmsrate() {
        $nameArray = $this->input->post('name');

        foreach ($nameArray as $key => $val) {

            $data['rate_id'] = $this->session->userdata('rateid');
            $data['operator_id'] = $this->input->post('operator_id' . $key);
            $data['buying_rate'] = $this->input->post('buying_rate' . $key);
            $data['selling_rate'] = $this->input->post('selling_rate' . $key);
            $data['created_by'] = $this->user_id;
            $data['created'] = date('Y-m-d');

            $this->db->where(array('operator_id' => $data['operator_id'], 'rate_id' => $this->session->userdata('rateid')));
            $q = $this->db->get('operator_sms_rate');

            if ($q->num_rows() > 0) {
                $this->db->update('operator_sms_rate', $data, array('operator_id' => $data['operator_id'], 'rate_id' => $this->session->userdata('rateid')));
            } else {
                $this->db->insert('operator_sms_rate', $data);
            }
        }
        return 'success';
    }

    function updatesUnitCost($id) {
        $nameArray = $this->input->post('name');
        foreach ($nameArray as $key => $val) {
            $datas['operator_id'] = $this->input->post('operator_id' . $key);
            $datas['unit_cost'] = $this->input->post('unit_cost' . $key);
            $datas['default_status'] = 'modified';
            $datas['created_by'] = $this->user_id;
            $datas['created'] = date('Y-m-d');
            $this->db->update('smsprice', $datas, array('user_id' => $id, 'operator_id' => $datas['operator_id']));
        }

        return 'success';
    }

    function existingoperation($operator_id) {
        $this->db->select('id');
        $this->db->from('operator_sms_rate');
        $this->db->where('operator_id', $operator_id);
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}

?>