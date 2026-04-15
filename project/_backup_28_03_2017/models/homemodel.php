<?php
 class Homemodel extends Bindu_Model
 {
 	function __construct()
 	{
 		parent::__construct(); 	
		$this->user_id = $this->session->userdata('user_userid');
 	}

	function get_creditBalance()
	{
		$this->db->select('credite_balance');
		$this->db->from('user_wallet');
		$this->db->where('user_id',$this->user_id);
		return $this->get_row();
	}
	function get_reseller()
	{
		$this->db->select('');
		$this->db->from('user');
		$this->db->where('id_user_group',2);
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	function get_customer()
	{
		$this->db->select('');
		$this->db->from('user');
		$this->db->where('id_user_group',3);
		if($this->session->userdata('user_group_id')!=1){
			$this->db->where('created_by',$this->user_id);
		}
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	function get_sentMessage()
	{
		$this->db->select('');
		$this->db->from('outbox');
		$this->db->join('sentmessages','sentmessages.id=outbox.reference_id','left');
		$this->db->where('outbox.status','Sent');
		if($this->session->userdata('user_group_id')!=1){
			$this->db->where('sentmessages.user_id',$this->user_id);
		}
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	function get_failedMessage()
	{
		$this->db->select('');
		$this->db->from('outbox');
		$this->db->join('sentmessages','sentmessages.id=outbox.reference_id','left');
		$this->db->where('outbox.status','Failed');
		if($this->session->userdata('user_group_id')!=1){
			$this->db->where('sentmessages.user_id',$this->user_id);
		}
		$rs = $this->db->get();
		return $rs->num_rows();
	}
	function get_recentBillList()
	{
		/* $this->db->select('user_wallet.*,user.username');		
 	 	$this->db->from('user_wallet');
 	 	$this->db->join('user','user.id=user_wallet.user_id','left');
 	 	 */
		$this->db->select('dh.*,user.username,user.email');		
 	 	$this->db->from('deposit_history dh');
 	 	$this->db->join('user','user.id=dh.user_id','left');
 	 	$this->db->where('user.created_by',$this->user_id);
 	 	$this->db->limit(10);
 	 	$this->db->order_by('id','DESC');
		$rs=$this->db->get();
		if($rs->num_rows()>0){
			return $rs->result_array(); 		 
		}else{
			return array();
		}
/* 		$this->db->select('deposit_history.*,SUM(deposit_amount) total,user.username,user.email',false);
		$this->db->from('deposit_history');
		$this->db->join('user','user.id=deposit_history.user_id','left');
		$this->db->where('depositor_user_id',$this->user_id);
		$this->db->where('deposit_history.status','Due');
		$this->db->limit(10);
		$this->db->group_by('deposit_history.user_id','ASC');
		$this->db->order_by('deposit_history.id','ASC');
		$rs = $this->db->get();
		return $rs->result_array(); */
	}
	
	function get_recentSmsRequest()
	{
		$this->db->select('');
		$this->db->from('sentmessages');
		$this->db->where('user_id',$this->user_id);
		$this->db->limit(10);
		$rs = $this->db->get();
		return $rs->result_array();
	}
	
 	function get_smsreport()
	{
		$this->db->select('count(*) total,outbox.status');
		$this->db->from('outbox');
		$this->db->join('user','user.id=outbox.user_id','left');
		if($this->session->userdata('user_group_id')==2){
			$this->db->where('user.created_by',$this->user_id);
		}
		if($this->session->userdata('user_group_id')==3){
			$this->db->where('user.id',$this->user_id);
		}
		$this->db->group_by('outbox.status');
		$rs = $this->db->get();
        $records = $rs->result_array();
        return $records;
        
	} 	
	function get_statuswiseSmsReport()
	{
		//echo $this->user_id; exit;
		$this->db->select('outbox.user_id,user.username');
		$this->db->from('outbox');
		$this->db->join('user','user.id=outbox.user_id','left');
		if($this->session->userdata('user_group_id')==2){
			$this->db->where('user.created_by',$this->user_id);
		}
		if($this->session->userdata('user_group_id')==3){
			$this->db->where('user.id',$this->user_id);
		}
		$this->db->group_by('user.id');
		$rs = $this->db->get();
        $result = array();
        foreach ($rs->result() as $val) {
            $result['user_id'] = $val->user_id;
            $result['username'] = $val->username;
            
			$result['total_Sent'] = $this->totalSmsStatus($val->user_id,'Sent');
			$result['total_Deliverd'] = $this->totalSmsStatus($val->user_id,'Deliverd');
			$result['total_Processing'] = $this->totalSmsStatus($val->user_id,'Processing');
			$result['total_Queue'] = $this->totalSmsStatus($val->user_id,'Queue');
			$result['total_Failed'] = $this->totalSmsStatus($val->user_id,'Failed');

			$new_array[] = $result;
        }

        return $new_array;
	}	
	
	function totalSmsStatus($user_id,$type)
    {
		$this->db->select('count(*) total');
		$this->db->from('outbox');
		$this->db->where('user_id',$user_id);
		$this->db->where('outbox.status',$type);
		$rs = $this->db->get();
        $records = $rs->result_array();
        return $records;
    }  
    function get_walletHistoryReport()
    {
		$sql = " 
        SELECT user_id, username,sum(TotalCredite) AS TotalCredite, sum(TotalDeposit) AS TotalDeposit, sum(borrow) as TotalTransfer
		FROM
				(					
					SELECT d.user_id,u.username,0 AS TotalCredite, sum(d.deposit_amount) TotalDeposit, 0 as borrow
					From sms_deposit_history d 
					join sms_user u on u.id=d.user_id
					GROUP BY d.user_id ASC

				UNION

				SELECT c.user_id,u.username,sum(c.credite_balance) TotalCredite, 0 AS TotalDeposit, 0 as borrow
					From sms_user_wallet c
					join sms_user u on u.id=c.user_id
					GROUP BY u.id ASC

				UNION

				SELECT a.depositor_user_id,u.username,0 as TotalCredite, 0 AS TotalDeposit, sum(a.deposit_amount) as borrow
					From sms_deposit_history a
					join sms_user u on u.id=a.depositor_user_id
					GROUP BY a.depositor_user_id ASC

					) x
					GROUP BY user_id ASC
        ";
        
        
        
       $qryRef = $this->db->query($sql);

        if($qryRef->num_rows()>0):
			
				return $qryRef->result_array();			
        else:
            return array();
        endif;
    }  
 }
 
?>