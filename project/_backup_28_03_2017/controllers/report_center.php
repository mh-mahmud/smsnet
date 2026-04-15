<?php

class Report_center extends Bindu_Controller {

    function __construct() {

        ini_set('memory_limit', '-1');

        parent::__construct();
        $this->load->model(array('sendmessagemodel', 'optionmodel', 'billingmodel'));
        $this->load->model(array('homemodel'));
        $this->tpl->set_page_title('Send Message Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
    }

    function mysms($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $status_options = array('Failed' => 'Failed', 'Deliverd' => 'Deliverd', 'Sent' => 'Sent', 'Processing' => 'Processing', 'Queue' => 'Queue');
        $this->assign('status_options', $status_options);

        $head = array('page_title' => 'My Message List', 'status_type' => 1);

        $labels = array('message' => 'Message', 'mask' => 'Mask', 'destmn' => 'Destmn', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');

        //$labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');

        $this->assign('labels', $labels);
        $config['total_rows'] = $this->sendmessagemodel->count_mysms_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', '');
        $this->set_pagination($config);
        $sendmessages = $this->sendmessagemodel->get_mysms_list($search_data);

        $this->assign('records', $sendmessages);
        $this->load->view('sendmessage/mysms_list', $head);
    }

    function outboxMessageList($sort_type = 'desc', $sort_on = 'write_time') {
        $this->load->library('search');
        $search_data = $this->search->data_search();
        $user_group_id = $this->session->userdata('user_group_id');

        $this->session->set_userdata('outbox-search', json_encode($search_data));

        $user_options = $this->optionmodel->get_user();
        $this->assign('user_options', $user_options);

        $status_options = array('Failed' => 'Failed', 'Deliverd' => 'Deliverd', 'Sent' => 'Sent', 'Processing' => 'Processing', 'Queue' => 'Queue');
        $this->assign('status_options', $status_options);

        $head = array('page_title' => 'Outbox Message List', 'status_type' => 1);

        if ($this->admin_group_id == 1) {
            $labels = array('username' => 'Username', 'message' => 'Message', 'mask' => 'Mask', 'destmn' => 'Destmn', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'remarks' => 'Remarks', 'status' => 'Status', 'smscount'=>'SMSCount');
        } else {
            $labels = array('username' => 'Username', 'message' => 'Message', 'mask' => 'Mask', 'destmn' => 'Destmn', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status', 'smscount'=>'SMSCount');
        }

        //$labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');

        $this->assign('labels', $labels);
        $config['total_rows'] = $this->sendmessagemodel->count_outbox_list($search_data, $user_group_id);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', '');
        $this->set_pagination($config);
        $sendmessages = $this->sendmessagemodel->get_outbox_list($search_data, $user_group_id);
        $this->session->set_userdata('outbox-search', json_encode($search_data));

        $this->assign('records', $sendmessages);
        $this->assign('user_group', $user_group_id);
        $this->load->view('sendmessage/outbox_list', $head);
    }

    function outboxMessageExcel() {
        $search_data = json_decode($this->session->userdata('outbox-search'), true);
        $user_group_id = $this->session->userdata('user_group_id');
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status', 'smscount'=>'SMSCount');
        $report_exel = $this->sendmessagemodel->get_outbox_list_report($search_data, $user_group_id);
        
        $this->get_excel($labels, $report_exel);
    }

    function failedMessageList($sort_type = 'desc', $sort_on = 'id') {

        $head = array('page_title' => 'Failed Message List', 'status_type' => 1);
        //$labels=array('srcmn'=>'Srcmn','mask'=>'Mask','destmn'=>'Destmn','message'=>'Message',	'operator_prefix'=>'Prefix','write_time'=>'Write Time','sent_time'=>'Sent Time','last_updated'=>'Last Updated','status'=>'Status');  
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'remarks' => 'Remarks','write_time'=>'Write Time','status' => 'Status', 'nosms'=>'No Of SMS');

        $this->assign('labels', $labels);
        $config['total_rows'] = $this->sendmessagemodel->count_fail_outbox_list();
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('outboxsmsresend' => 'outboxsmsresend'));
        $this->set_pagination($config);
        $sendmessages = $this->sendmessagemodel->get_fail_outbox_list();
        $this->assign('records', $sendmessages);
        $this->load->view('sendmessage/fail_outbox_list', $head);
    }

    function failMessageExcel() {
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');
        $report_exel = $this->sendmessagemodel->get_fail_outbox_list_report();
        $this->get_excel($labels, $report_exel);
    }

    function outboxsmsresend($id) {
        $failmessages = $this->sendmessagemodel->get_fail_list($id);

        $data['status'] = 'Queue';
        /* $data['srcmn'] = $failmessages['srcmn'];
          $data['mask'] = $failmessages['mask'];
          $data['destmn'] = $failmessages['destmn'];
          $data['message'] = $failmessages['message'];
          $data['operator_prefix'] = substr($failmessages['destmn'], 0, 3);
          $data['status'] = 'Queue';
          $data['write_time'] = date('Y-m-d h:i:s');
          $data['sent_time'] = '';
          $data['ton'] = '';
          $data['npi'] = '';
          $data['message_type'] = 'text';
          $data['esm_class'] = '';
          $data['data_coding'] = '';
          $data['reference_id'] = $failmessages['reference_id'];
          $data['last_updated'] = date('Y-m-d h:i:s'); */
        //$data['schedule_time'] = $val['scheduleDateTime'];
        //$sendmessageoutbox_id=$this->sendmessagemodel->addOutbox($data);
        $sendmessageoutbox_id = $this->sendmessagemodel->updateOutbox($id, $data);
        /* if($sendmessageoutbox_id){
          $this->sendmessagemodel->fail_sms_del($id);
          }
         */
        $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'fail sms'));
        redirect('report_center/failedMessageList');
    }

    function statuswisemessage() {
        $statuswiseSmsReport = $this->homemodel->get_statuswiseSmsReport();
        $this->assign('statuswiseSmsReport', $statuswiseSmsReport);
        $this->load->view('sendmessage/statuswisemessage');
    }
    function wallet_history() {
        $walletHistoryReport = $this->homemodel->get_walletHistoryReport();
        
        $this->assign('walletHistoryReport', $walletHistoryReport);
        $this->load->view('wallet/walletHistoryReport');
    }
    
    function sms_detail() {
        $this->load->library('search');
        $search_data = $this->search->data_search();
        
        $this->session->set_userdata('sms_detail-search', json_encode($search_data));
        $user_options = $this->optionmodel->get_user();
        $this->assign('user_options', $user_options);
        
        $head = array('page_title' => 'SMS Detail Record', 'status_type' => 1);
        
        if($search_data['user_id'] =='')
            $search_data['user_id'] = 1;

        if($search_data['from_date'] ==''){
            $search_data['from_date'] = date('Y-m-d 00:00:00', strtotime('today'));
        }
        
        if($search_data['to_date'] == '')
            $search_data['to_date'] = date('Y-m-d 23:59:59', strtotime('today'));
        
        $billingReport = $this->billingmodel->get_billingReport($search_data['user_id'], $search_data['from_date'], $search_data['to_date']);
        $this->assign('billingReport', $billingReport);
        $this->load->view('reports/sms_detail', $head);
    }
}

?>
