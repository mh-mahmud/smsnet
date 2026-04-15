<?php

class Sendmessagemodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
        $timezone = "Asia/Dhaka";
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set($timezone);
    }

    function get_message_list_report($data = array()) {
        $this->db->select('sentFrom,message,sms_type,scheduleDateTime,status');
        $this->db->from('sentmessages');
        $this->db->where('user_id', $this->user_id);
        if ($data['status'] != '')
            $this->db->where('status', $data['status']);
        if ($data['sms_type'] != '')
            $this->db->where('sms_type', $data['sms_type']);
        if ($data['from_date'] != '')
            $this->db->where('date >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('date <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function get_list($data) {
        $this->db->select('');
        $this->db->from('sentmessages');
        $this->db->where('user_id', $this->user_id);
        if ($data['status'] != '')
            $this->db->where('status', $data['status']);
        if ($data['sms_type'] != '')
            $this->db->where('sms_type', $data['sms_type']);
        if ($data['from_date'] != '')
            $this->db->where('date >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('date <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function count_list($data) {
        $this->db->select('sentmessages.id');
        $this->db->from('sentmessages');
        $this->db->where('user_id', $this->user_id);
        if ($data['status'] != '')
            $this->db->where('status', $data['status']);
        if ($data['sms_type'] != '')
            $this->db->where('sms_type', $data['sms_type']);
        if ($data['from_date'] != '')
            $this->db->where('date >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('date <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->num_rows();
        return $sentmessagess;
    }

    function get_mysms_list($data) {
        $this->db->select('ox.*,user.username');
        $this->db->from('outbox ox');
        $this->db->join('sentmessages', 'sentmessages.id=ox.reference_id', 'left');
        $this->db->join('user', 'user.id=sentmessages.user_id', 'left');
        $this->db->where('ox.user_id', $this->user_id);
        if ($data['message'] != '')
            $this->db->like('ox.message', $data['message'], 'both');
        if ($data['status'] != '')
            $this->db->where('ox.status', $data['status']);
        if ($data['mask'] != '')
            $this->db->where('ox.mask', $data['mask']);
        if ($data['destmn'] != '')
            $this->db->where('ox.destmn', $data['destmn']);
        if ($data['from_date'] != '')
            $this->db->where('(ox.write_time) >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('(ox.write_time) <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function count_mysms_list($data) {
        $this->db->select('ox.id');
        $this->db->from('outbox ox');
        $this->db->join('sentmessages', 'sentmessages.id=ox.reference_id', 'left');
        $this->db->join('user', 'user.id=ox.user_id', 'left');
        $this->db->where('ox.user_id', $this->user_id);
        if ($data['message'] != '')
            $this->db->like('ox.message', $data['message'], 'both');
        if ($data['status'] != '')
            $this->db->where('ox.status', $data['status']);
        if ($data['mask'] != '')
            $this->db->where('ox.mask', $data['mask']);
        if ($data['destmn'] != '')
            $this->db->where('ox.destmn', $data['destmn']);
        if ($data['from_date'] != '')
            $this->db->where('(ox.write_time) >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('(ox.write_time) <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->num_rows();
        return $sentmessagess;
    }

    function get_outbox_list($data, $user_group) {
        $this->db->select('ox.*,user.username');
        $this->db->from('outbox ox');
        //$this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->join('user', 'user.id=ox.user_id', 'inner');

        if ($user_group != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['message'] != '')
            $this->db->like('ox.message', $data['message'], 'both');
        if ($data['user_id'] > 0)
            $this->db->where('ox.user_id', $data['user_id']);
        if ($data['status'] != '')
            $this->db->where('ox.status', $data['status']);
        if ($data['mask'] != '')
            $this->db->where('ox.mask', $data['mask']);
        if ($data['destmn'] != '')
            $this->db->where('ox.destmn', $data['destmn']);
        if ($data['from_date'] != '')
            $this->db->where('ox.write_time >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('ox.write_time <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function count_outbox_list($data, $user_group) {
        $this->db->select('ox.id');
        $this->db->from('outbox ox');
        #$this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->join('user', 'user.id=ox.user_id', 'inner');
        if ($user_group != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['message'] != '')
            $this->db->like('ox.message', $data['message'], 'both');
        if ($data['user_id'] > 0)
            $this->db->where('ox.user_id', $data['user_id']);
        if ($data['status'] != '')
            $this->db->where('ox.status', $data['status']);
        if ($data['mask'] != '')
            $this->db->where('ox.mask', $data['mask']);
        if ($data['destmn'] != '')
            $this->db->where('ox.destmn', $data['destmn']);
        if ($data['from_date'] != '')
            $this->db->where('ox.write_time >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('ox.write_time <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->num_rows();
        return $sentmessagess;
    }

    function get_outbox_list_report($data = array(), $user_group) {
        $this->db->select('outbox.srcmn,outbox.mask,outbox.destmn,outbox.message,outbox.operator_prefix,outbox.write_time,outbox.sent_time,outbox.last_updated,outbox.status, outbox.smscount');
        $this->db->from('outbox');
        #$this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->join('user', 'user.id=outbox.user_id', 'inner');
        if ($user_group != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['user_id'] > 0)
            $this->db->where('outbox.user_id', $data['user_id']);
        if ($data['status'] != '')
            if ($data['status'] != '')
                $this->db->where('outbox.status', $data['status']);
        if ($data['mask'] != '')
            $this->db->where('outbox.mask', $data['mask']);
        if ($data['destmn'] != '')
            $this->db->where('outbox.destmn', $data['destmn']);
        if ($data['from_date'] != '')
            $this->db->where('outbox.write_time >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('outbox.write_time <=', $data['to_date']);
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        //[srcmn] => 8167 [mask] => AAA [destmn] => 01731296511 [message] => এখন অনেক দ্রুত এস এম এস ডেলিভারি হয়। you can try yourself! [operator_prefix] => 17 [write_time] => 2016-10-18 12:34:11 [sent_time] => 2016-10-18 12:34:13 [last_updated] => 2016-10-18 12:34:13 [status] => Sent\

        $total_sms = $rs->num_rows();
        $total_row = array('srcmn' => '', 'mask' => '', 'destmn' => '', 'message' => '', 'operator_prefix' => '', 'write_time' => '', 'sent_time' => '', 'last_updated' => 'Total SMS', 'status' => $total_sms);
        array_push($sentmessagess, $total_row);
        //print_r($sentmessagess);
        //array_push($sentmessagess, array(''$total_sms)


        return $sentmessagess;
    }

    function get_fail_outbox_list() {
        $this->db->select('outbox.*');
        $this->db->from('outbox');
        $this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->where('sentmessages.user_id', $this->user_id);
        $this->db->where('outbox.status', 'Failed');
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function get_fail_outbox_list_report() {
        $this->db->select('outbox.srcmn,outbox.mask,outbox.destmn,outbox.message,outbox.operator_prefix,outbox.write_time,outbox.sent_time,outbox.last_updated,outbox.status');
        $this->db->from('outbox');
        $this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->where('sentmessages.user_id', $this->user_id);
        $this->db->where('outbox.status', 'Failed');
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function count_fail_outbox_list() {
        $this->db->select('outbox.id');
        $this->db->from('outbox');
        $this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->where('sentmessages.user_id', $this->user_id);
        $this->db->where('outbox.status', 'Failed');
        $rs = $this->db->get();
        $sentmessagess = $rs->num_rows();
        return $sentmessagess;
    }

    function get_fail_list($id) {
        $this->db->select('outbox.*');
        $this->db->from('outbox');
        $this->db->join('sentmessages', 'sentmessages.id=outbox.reference_id', 'left');
        $this->db->where('sentmessages.user_id', $this->user_id);
        $this->db->where('outbox.id', $id);
        return $this->get_row();
    }

    function add($data) {
        $this->db->insert('sentmessages', $data);
        return $this->db->insert_id();
    }

    function edit($id = '', $data, $shop_id = '') {
        return $this->db->update('sentmessages', $data, array('id' => $id));
    }

    function updateOutbox($id, $data) {
        return $this->db->update('outbox', $data, array('id' => $id));
    }

    function edit_password($id) {
        $timezone = "Asia/Dhaka";
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set($timezone);
        $data['passwd'] = $this->input->post('new_passwd');
        return $this->db->update('sentmessages', $data, array('id' => $id));
    }

    function get_sentmessages_details($id) {
        $this->db->select('ad.sentmessagesname,ad.email,ad.mobile,ad.address,ad.status,ag.title sentmessages_type');
        $this->db->from('sentmessages ad');
        $this->db->join('sentmessages_group ag', 'ag.id = ad.id_sentmessages_group', 'left');
        $this->db->where('ad.id', $id);
        return $this->get_row();
    }

    function get_record($id) {
        $this->db->select('*');
        $this->db->from('sentmessages');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function change_status($id, $val) {
        $this->db->where('id', $id);
        $this->db->update('sentmessages', array('status' => strtoupper($val)));
    }

    function del($id) {
        $this->db->delete('sentmessages', array('id' => $id));
    }

    function fail_sms_del($id) {
        $this->db->delete('outbox', array('id' => $id));
    }

    function old_mask_option($customar = '') {
        $parent = $this->get_parent_id($this->user_id);
        $this->db->select('id,senderID title');
        $this->db->from('senderid');
        if ($customar > 0 AND $parent['id_user_group'] != 1) {
            $this->db->where('senderid.created_by', $parent['created_by']);
        } else {
            $this->db->where('senderid.created_by', $this->user_id);
        }
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function mask_option($customar = '') {
        
        $this->db->select('id,senderID title');
        $this->db->from('senderid');
        if ($customar > 0) {
            $this->db->where("FIND_IN_SET('$customar',user_id) !=", 0);
        } else {
            $this->db->where("FIND_IN_SET(1,user_id) !=", 0);
        }
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function get_parent_id($customer_id) {
        $this->db->select('a.created_by,b.id_user_group');
        $this->db->from('user a');
        $this->db->join('user b', 'b.id=a.created_by', 'left');
        $this->db->where('a.id', $customer_id);
        return $this->get_row();
    }

    function get_all_group() {
        $this->db->select('group.*,COUNT(distinct phone) total_member,group_members.group_id');
        $this->db->from('group');
        $this->db->join('group_members', 'group.id=group_members.group_id', 'left');
        $this->db->where('group.user_id', $this->user_id);
        $this->db->where('group.status', 'Active');
        $this->db->where('group_members.status', 'Active');
        $this->db->group_by('group_members.group_id');
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function duplicate_email_check($email, $param) {
        $this->db->where('email', $email);
        $this->db->where('email <>', "$param");
        $this->db->from('sentmessages');
        return $this->db->count_all_results();
    }

    function duplicate_sentmessages_check($sentmessagesname, $param) {
        $this->db->where('sentmessagesname', $sentmessagesname);
        $this->db->where('sentmessagesname <>', "$param");
        $this->db->from('sentmessages');
        return $this->db->count_all_results();
    }

    /*     * ****************** OUTBOX ******************** */

    function get_sendmessage_details($id) {
        $this->db->select('sentmessages.*,senderid.senderID mask');
        $this->db->from('sentmessages');
        //$this->db->where('sentmessages.status','Not_sent');
        //$this->db->where('sentmessages.scheduleDateTime < ', date("Y-m-d H:i:s"));
        $this->db->where('sentmessages.id', $id);
        $this->db->join('senderid', 'senderid.id=sentmessages.senderID', 'left');
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function getAllSentMessage() {
        $this->db->select('sentmessages.*,senderid.senderID mask');
        $this->db->from('sentmessages');
        $this->db->where('sentmessages.status', 'Not_sent');
        $this->db->where('sentmessages.scheduleDateTime < ', date("Y-m-d h:i:s"));
        $this->db->join('senderid', 'senderid.id=sentmessages.senderID', 'left');
        $rs = $this->db->get();
        $sentmessagess = $rs->result_array();
        return $sentmessagess;
    }

    function addOutbox($data) {
        $this->db->insert('outbox', $data);
        return $this->db->insert_id();
    }

    function addOutboxByMultipleNumber($data) {
        $this->db->insert_batch('outbox', $data);
        return $this->db->insert_id();
    }

    function updateSentMessage($id = '', $data) {
        return $this->db->update('sentmessages', $data, array('id' => $id));
    }

    function getAllGroupRecipient($group) {
        $this->db->select('phone');
        $this->db->from('group_members');
        $this->db->where_in('group_id', $group);
        $this->db->where('status', 'Active');
        $rs = $this->db->get();
        $phone = $rs->result_array();
        return $phone;
    }

}

?>