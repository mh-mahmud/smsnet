<?php

class Walletmodel extends Bindu_Model {

    function __construct() {
        parent::__construct();
	$this->admin_group_id = $this->session->userdata('user_group_id');
    }

    function get_list($data) {
        $this->db->select('dh.*,user.username');
        $this->db->from('deposit_history dh');
        $this->db->join('user', 'user.id=dh.user_id', 'left');
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['username'] != '')
            $this->db->like('user.username', $data['username'], 'both');
        if ($data['from_date'] != '')
            $this->db->where('DATE(dh.created) >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('DATE(dh.created) <=', $data['to_date']);
        
        $this->db->order_by('created', 'desc');
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $deposit_historys = $rs->result_array();
            return $deposit_historys;
        } else {
            return array();
        }
    }

    function count_list($data) {
        $this->db->select('dh.id');
        $this->db->from('deposit_history dh');
        $this->db->join('user', 'user.id=dh.user_id', 'left');
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['username'] != '')
            $this->db->like('user.username', $data['username'], 'both');
        if ($data['from_date'] != '')
            $this->db->where('DATE(dh.created) >=', $data['from_date']);
        if ($data['to_date'] != '')
            $this->db->where('DATE(dh.created) <=', $data['to_date']);
        $rs = $this->db->get();
        $deposit_historys = $rs->num_rows();
        return $deposit_historys;
    }

	//28-02-17
    function get_walletHistory_list($data=array()) {
        $this->db->select('dh.*,u.username');
        $this->db->from('deposit_history dh');
        $this->db->join('user u', 'u.id=dh.depositor_user_id', 'left');
        $this->db->where('dh.user_id', $this->user_id);
        $this->db->where('dh.status', 'Approved');
		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $deposit_historys = $rs->result_array();
            return $deposit_historys;
        } else {
            return array();
        }
    }
	function get_walletHistory_summery($dat=array()) {
        $this->db->select('dh.*,MIN(created) fromdate, MAX(created) todate, sum(deposit_amount) total_received_amount ');
        $this->db->from('deposit_history dh');
        $this->db->where('dh.user_id', $this->user_id);
        $this->db->where('dh.curency', 'BDT');
        $this->db->where('dh.status', 'Approved');
		$rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $deposit_historys = $rs->result_array();
            return $deposit_historys;
        } else {
            return array();
        }
    }

    function count_walletHistory_list($data=array()) {
        $this->db->select('dh.id');
        $this->db->from('deposit_history dh');
        $this->db->join('user u', 'u.id=dh.depositor_user_id', 'left');
        $this->db->where('dh.user_id', $this->user_id);
		$this->db->where('dh.status', 'Approved');
		$rs = $this->db->get();
        $deposit_historys = $rs->num_rows();
        return $deposit_historys;
    }
	
	function get_depositHistory_list($data=array()) {
        $this->db->select('dh.*,u.username');
        $this->db->from('deposit_history dh');
        $this->db->join('user u', 'u.id=dh.user_id', 'left');
        $this->db->where('dh.depositor_user_id', $this->user_id);
        $this->db->where('dh.status', 'Approved');
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $deposit_historys = $rs->result_array();
            return $deposit_historys;
        } else {
            return array();
        }
    }
	function get_depositHistory_summery($dat=array()) {
        $this->db->select('dh.*,MIN(approved_date) fromdate, MAX(approved_date) todate, sum(deposit_amount) total_received_amount ');
        $this->db->from('deposit_history dh');
        $this->db->where('dh.depositor_user_id', $this->user_id); 
		$this->db->where('dh.status', 'Approved');
        $this->db->where('dh.curency', 'BDT');
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            $deposit_historys = $rs->result_array();
            return $deposit_historys;
        } else {
            return array();
        }
    }

    function count_depositHistory_list($data=array()) {
        $this->db->select('dh.id');
        $this->db->from('deposit_history dh');
        $this->db->join('user u', 'u.id=dh.user_id', 'left');
        $this->db->where('dh.depositor_user_id', $this->user_id);
		$this->db->where('dh.status', 'Approved');
        $rs = $this->db->get();
        $deposit_historys = $rs->num_rows();
        return $deposit_historys;
    }


    function get_wallet_list($data) {
        $this->db->select('user_wallet.*,user.username');
        $this->db->from('user_wallet');
        $this->db->join('user', 'user.id=user_wallet.user_id', 'left');
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['username'] != '')
            $this->db->like('user.username', $data['username'], 'both');
        
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) {
            return $rs->result_array();
        } else {
            return array();
        }
    }

    function count_wallet_list($data) {
        $this->db->select('user_wallet.id');
        $this->db->from('user_wallet');
        $this->db->join('user', 'user.id=user_wallet.user_id', 'left');
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        if ($data['username'] != '')
            $this->db->like('user.username', $data['username'], 'both');
        $rs = $this->db->get();
        $deposit_historys = $rs->num_rows();
        return $deposit_historys;
    }

    function add($data) {
        $this->db->insert('deposit_history', $data);
        return $this->db->insert_id();
    }

    function edit($id = '', $data) {
        return $this->db->update('deposit_history', $data, array('id' => $id));
    }

    function get_record($id) {
        $this->db->select('*');
        $this->db->from('deposit_history');
        $this->db->where('id', $id);
        return $this->get_row();
    }

    function del($id) {
        $this->db->delete('deposit_history', array('id' => $id));
    }

    function user_options() {
        $this->db->select('user.id,CONCAT(username, "(", lower(LEFT(title,1)), ")") AS title', FALSE);
        $this->db->from('user');
        $this->db->join('user_group', 'user_group.id=user.id_user_group', 'left');
        $this->db->where('user.id_user_group !=', 1);
        if ($this->admin_group_id != 1) {
            $this->db->where('user.created_by', $this->user_id);
        }
        $this->db->order_by('title', 'asc');
        return $this->get_assoc();
    }

    function wallet_update($user_id, $amount) {
        if ($this->existingUser($user_id) > 0) {
            //echo $amount; exit;
            $this->db->where('user_id', $user_id);
            $this->db->set('credite_balance', '`credite_balance` + "' . $amount . '"', FALSE); // to increment
            $this->db->update('user_wallet');
            return true;
        } else {
            $data['user_id'] = $user_id;
            $data['credite_balance'] = $amount;
            $this->db->insert('user_wallet', $data);
            return $this->db->insert_id;
        }
    }

    function admin_wallet_update($amount) {
        if ($this->session->userdata('id_user_group') != 1) {
            $this->db->where('user_id', $this->user_id);
            $this->db->set('credite_balance', '`credite_balance` - "' . $amount . '"', FALSE); // to decrement
            $this->db->update('user_wallet');
            return 'update';
        } else {
            return 'success';
        }
    }

    function existingUser($user_id) {
        $this->db->select('id');
        $this->db->from('user_wallet');
        $this->db->where('user_id', $user_id);
        $rs = $this->db->get('');
        if ($rs->num_rows() > 0)
            return 1;
        else
            return 0;
    }

    function userCreditCheck($str, $param) {
        $this->db->select('');
        $this->db->from('user_wallet');
        if ($this->admin_group_id != 1) {
            $this->db->where('user_id', $this->user_id);
        }
        $rs = $this->db->get();
        $result = $rs->result_array();
        if ($result[0]['credite_balance'] < $str) {
            if ($this->admin_group_id == 1) {
                return 0;
            } else {
                return 1;
            }
        } else
            return 0;
    }
	function change_deposite_status($id, $val) {
            
        if($val=='Approved'){
			$this->db->where(array('id'=> $id, 'status !='=> 'Approved'));
			$this->db->update('deposit_history', array('status' => strtoupper($val),'approved_date' => $this->current_date()));
			if($this->db->affected_rows()>0){
				$userRecored = $this->get_user_data($id);
				$this->wallet_update($userRecored[0]['user_id'], $userRecored[0]['deposit_amount']);
				return true;
			}
		}else{
			return false;
		}
		
    }
	function get_user_data($id)
	{
		$this->db->select('*');
        $this->db->from('deposit_history');
        $this->db->where('id', $id);
        $rs = $this->db->get('');
        if ($rs->num_rows() > 0){
			return $rs->result_array();
		}else{
			return 0;
		}
	}
}

?>