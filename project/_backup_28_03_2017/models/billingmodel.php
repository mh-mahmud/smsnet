<?php

class Billingmodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
        $this->user_id = $this->session->userdata('user_userid');
    }

    function get_billingReport($id, $start_date, $end_date) {
        
        $users = array();
        $where_clauses = array();
        $where = '';
        
        if($id !=''){
            array_push($users, $id);
            $users_query = "select id from sms_user where created_by = $id";
            $rs = $this->db->query($users_query);
            
            foreach ($rs->result() as $val) {
                $users[] = $val->id;
            }
        }
        
        if ($id!='')
            $where_clauses[] = "u.id=$id";
        
        if(count($where_clauses)> 0 )
            $where  = " where ".implode(" AND ", $where_clauses);
        
        $sql = "select u.id,u.username,sr.buying_rate,op.prefix,op.full_name, '$start_date' date_from, '$end_date' date_to, u.mrc_otc
				FROM sms_user u
				JOIN sms_rates r ON r.id=u.rate_id
				JOIN sms_operator_sms_rate sr ON sr.rate_id=u.rate_id 
				JOIN sms_operator op ON op.id=sr.operator_id 
                                $where
				GROUP BY u.id,sr.operator_id";

        //echo  $sql; exit;		
        $rs = $this->db->query($sql);
        $result = array();
        foreach ($rs->result() as $val) {
            $result['user_id'] = $val->id;
            $result['username'] = $val->username;
            $result['operator'] = $val->full_name;
            $result['date_from'] = $val->date_from;
            $result['date_to'] = $val->date_to;
            $result['mrc_otc'] = $val->mrc_otc;
            $result['totalSmsCountOperatorwise'] = $this->totalSmsCountOperatorwise($users, $val->prefix, $start_date, $end_date);
            $result['totalSmsOperatorwise'] = $this->totalSmsOperatorwise($users, $val->prefix, $start_date, $end_date);
            $result['buying_rate'] = $val->buying_rate;
            $result['operatorWiseTotalCost'] = $result['totalSmsCountOperatorwise'][0]->totalsmscount * $val->buying_rate;

            $new_array[] = $result;
        }

        return $new_array;
    }

    function totalSmsCountOperatorwise($user_id, $prefix, $start_date, $end_date) {
        $lastMonth = date("m", strtotime("-1 month"));
        //AND MONTH(sent_time) = $lastMonth 
        
        $users = implode(",", $user_id);

        $sql = "SELECT sum(smscount) totalsmscount
				FROM sms_outbox
				WHERE user_id in ($users)
				AND operator_prefix = $prefix
				AND status = 'Sent' and sent_time >= '$start_date' and sent_time <='$end_date' ";

        $rs = $this->db->query($sql);
		if($rs->num_rows()>0){
			$records = $rs->result();
			return $records;
		}else{
			return 0;
		}

    }
    
    function totalSmsOperatorwise($user_id, $prefix, $start_date, $end_date) {
        $lastMonth = date("m", strtotime("-1 month"));
        //AND MONTH(sent_time) = $lastMonth 
        $users = implode(",", $user_id);
        $sql = "SELECT count(id) totalsms
				FROM sms_outbox
				WHERE user_id in ($users)
				AND operator_prefix = $prefix
				AND status = 'Sent' and sent_time >= '$start_date' and sent_time <='$end_date' ";

        $rs = $this->db->query($sql);
        $records = $rs->result();
		if($rs->num_rows()>0){
			$records = $rs->result();
			return $records;
		}else{
			return 0;
		}
    }

}

?>