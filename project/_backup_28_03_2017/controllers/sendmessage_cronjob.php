<?php
 class Sendmessage_cronjob extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('sendmessagemodel'));
		$this->tpl->set_page_title('Send Message Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

	/**************** SMS SENT TO OUTBOX ****************/
	
	function sms_sent_outbox()
	{
		$sentMessageList = 	$this->sendmessagemodel->getAllSentMessage();
		foreach($sentMessageList as $val){
			if (strpos($val['recipient'], ',') != true AND $val['group_id'] =='' AND $val['file'] =='') {
				$data['srcmn'] = $val['sentFrom'];
				$data['mask'] = $val['mask'];
				$data['destmn'] = preg_replace('/\s+/', '', trim($val['recipient'],','));
				$data['message'] = $val['message'];
				$data['operator_prefix'] = substr($data['destmn'], 0, 3);
				$data['status'] = 'Queue';
				$data['write_time'] = date('Y-m-d h:i:s');
				$data['sent_time'] = '';
				$data['ton'] = '';
				$data['npi'] = '';
				$data['message_type'] = 'text';
				$data['esm_class'] = '';
				$data['data_coding'] = '';
				$data['reference_id'] = $val['id'];
				$data['last_updated'] = date('Y-m-d h:i:s');
				$data['schedule_time'] = $val['scheduleDateTime'];
				$sendmessageoutbox_id=$this->sendmessagemodel->addOutbox($data);
				$udata['status'] = 'Sent';
				$this->sendmessagemodel->updateSentMessage($val['id'],$udata);
			}
			if (strpos($val['recipient'], ',') != false) {
				//echo 'dddddddddddddd';exit;
				
				$batchArray = array();
				$receipientArray = explode(',', $val['recipient']);
				foreach($receipientArray as $rval){
					$data['srcmn'] = $val['sentFrom'];
					$data['mask'] = $val['mask'];
					$data['destmn'] = preg_replace('/\s+/', '', trim(trim($rval,',')));
					$data['message'] = $val['message'];
					$data['operator_prefix'] = substr($data['destmn'], 0, 3);
					$data['status'] = 'Queue';
					$data['write_time'] = date('Y-m-d h:i:s');
					$data['sent_time'] = '';
					$data['ton'] = '';
					$data['npi'] = '';
					$data['message_type'] = 'text';
					$data['esm_class'] = '';
					$data['data_coding'] = '';
					$data['reference_id'] = $val['id'];
					$data['last_updated'] = date('Y-m-d h:i:s');
					$data['schedule_time'] = $val['scheduleDateTime'];
					$batchArray[] = $data;
				}
		
				$sendmessageoutbox_id=$this->sendmessagemodel->addOutboxByMultipleNumber($batchArray);
				$udata['status'] = 'Sent';
				$this->sendmessagemodel->updateSentMessage($val['id'],$udata);
				
			}
			if ($val['group_id'] !='' OR $val['group_id'] !=NULL) {
				$allRecepient = $this->sendmessagemodel->getAllGroupRecipient(explode(',', $val['group_id']));
				$batchArray = array();
				foreach($allRecepient as $rval){
					$data['srcmn'] = $val['sentFrom'];
					$data['mask'] = $val['mask'];
					$data['destmn'] = preg_replace('/\s+/', '', trim($rval['phone'],','));
					$data['message'] = $val['message'];
					$data['operator_prefix'] = substr($data['destmn'], 0, 3);
					$data['status'] = 'Queue';
					$data['write_time'] = date('Y-m-d h:i:s');
					$data['sent_time'] = '';
					$data['ton'] = '';
					$data['npi'] = '';
					$data['message_type'] = 'text';
					$data['esm_class'] = '';
					$data['data_coding'] = '';
					$data['reference_id'] = $val['id'];
					$data['last_updated'] = date('Y-m-d h:i:s');
					$data['schedule_time'] = $val['scheduleDateTime'];
					$batchArray[] = $data;
				}
		
				$sendmessageoutbox_id=$this->sendmessagemodel->addOutboxByMultipleNumber($batchArray);
				$udata['status'] = 'Sent';
				$this->sendmessagemodel->updateSentMessage($val['id'],$udata);				
			}
			if ($val['file'] !='' OR $val['file'] !=NULL) {
				$ext = end(explode(".", $val['file']));
				if($ext == 'csv') {
					$handle = fopen(base_url().'upload/group_members/'.$val['file'], "r");

					if($val['source']=='WEB'){
						while (($csvdata = fgetcsv($handle, 10000, ",")) !== FALSE) {

							$name = mysql_real_escape_string($csvdata[0]);

							$phone2 = mysql_real_escape_string($csvdata[1]);

							$phone[]=$phone2;
						}
					}
					if($val['source']=='API'){
						while (($csvdata = fgetcsv($handle, 10000, ",")) !== FALSE) {

							$message[] = mysql_real_escape_string($csvdata[0]);
							$mask[] = mysql_real_escape_string($csvdata[1]);							
							$phone2 = mysql_real_escape_string($csvdata[2]);

							$phone[]=$phone2;
						}
					}
					
				} // end of csv

				else {

					$phoneList = file_get_contents(base_url().'upload/group_members/'.$val['file']);
					$phoneList = mysql_real_escape_string($phoneList);

					$phoneList = str_replace(";", ",", $phoneList);

					$phoneList = str_replace(" ", ",", $phoneList);

					$phoneList = strtr ($phoneList, array ('\r\n' => ','));
					
					$phoneList = preg_replace("/[^0-9+,]/", "", $phoneList );
					
					$phoneList = explode(',',$phoneList);

					//add

					$count = count($phoneList);

					for($w=0; $w<$count; $w++){

						if(!empty($phoneList[$w])) {

							$phone[] = $phoneList[$w];							
						}
					}
			} 
			
				$batchArray = array();
				if($val['source']=='WEB'){
					foreach($phone as $key=>$rval){
						$data['srcmn'] = $val['sentFrom'];
						$data['mask'] = $val['mask'];
						$data['destmn'] = preg_replace('/\s+/', '', trim($rval,','));
						$data['message'] = $val['message'];
						$data['operator_prefix'] = substr($data['destmn'], 0, 3);
						$data['status'] = 'Queue';
						$data['write_time'] = date('Y-m-d h:i:s');
						$data['sent_time'] = '';
						$data['ton'] = '';
						$data['npi'] = '';
						$data['message_type'] = 'text';
						$data['esm_class'] = '';
						$data['data_coding'] = '';
						$data['reference_id'] = $val['id'];
						$data['last_updated'] = date('Y-m-d h:i:s');
						$data['schedule_time'] = $val['scheduleDateTime'];
						$batchArray[] = $data;
					}
				}
				if($val['source']=='API'){
					foreach($phone as $key=>$rval){
						$data['srcmn'] = $val['sentFrom'];
						$data['mask'] = $mask[$key];//$val['mask'];
						$data['destmn'] = preg_replace('/\s+/', '', trim($rval,','));
						$data['message'] = $message[$key];//$val['message'];
						$data['operator_prefix'] = substr($data['destmn'], 0, 3);
						$data['status'] = 'Queue';
						$data['write_time'] = date('Y-m-d h:i:s');
						$data['sent_time'] = '';
						$data['ton'] = '';
						$data['npi'] = '';
						$data['message_type'] = 'text';
						$data['esm_class'] = '';
						$data['data_coding'] = '';
						$data['reference_id'] = $val['id'];
						$data['last_updated'] = date('Y-m-d h:i:s');
						$data['schedule_time'] = $val['scheduleDateTime'];
						$batchArray[] = $data;
					}
				}
		
				$sendmessageoutbox_id=$this->sendmessagemodel->addOutboxByMultipleNumber($batchArray);
				$udata['status'] = 'Sent';
				$this->sendmessagemodel->updateSentMessage($val['id'],$udata);	
			}
		}
		$this->session->set_flashdata('message',$this->tpl->set_message('add','Sms to Outbox'));
		//redirect('sendmessage');
	}

 }
?>
