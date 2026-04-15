<?php

class Sendmessage extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->admin_group_id = $this->session->userdata('user_group_id');
        $this->load->model(array('sendmessagemodel'));
        $this->tpl->set_page_title('Send Message Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
        $this->user_status = $this->session->userdata('user_status');
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();
        $this->session->set_userdata('search_data', json_encode($search_data));
        $status_options = array('Sent' => 'Sent', 'Not_sent' => 'Not Sent');
        $this->assign('status_options', $status_options);

        $type_options = array('SentMessage' => 'Sent Message', 'ScheduleMessage' => 'Schedule Message');
        $this->assign('type_options', $type_options);

        $head = array('page_title' => 'Sentmessages List', 'link_title' => 'New Sentmessages', 'link_action' => 'sendmessage/composemessage', 'status_type' => 1);
        $labels = array('sentFrom' => 'Sender', 'message' => 'Message.', 'sms_type' => 'Type', 'scheduleDateTime' => 'Schedule Time', 'status' => 'Status');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->sendmessagemodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('view' => 'view', 'resend' => 'resend'));
        $this->set_pagination($config);
        $sendmessages = $this->sendmessagemodel->get_list($search_data);
        $this->assign('records', $sendmessages);
        $this->load->view('sendmessage/sendmessage_list', $head);
    }

    function excel() {
        $labels = array('sentFrom' => 'Sender', 'message' => 'Message.', 'sms_type' => 'Type', 'scheduleDateTime' => 'Schedule Time', 'status' => 'Status');
        $report_exel = $this->sendmessagemodel->get_message_list_report(json_decode($this->session->userdata('search_data'), true));
        $this->get_excel($labels, $report_exel);
    }

    function outboxMessageList($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $this->session->set_userdata('search_data', json_encode($search_data));

        $status_options = array('Failed' => 'Failed', 'Deliverd' => 'Deliverd', 'Sent' => 'Sent', 'Processing' => 'Processing', 'Queue' => 'Queue');
        $this->assign('status_options', $status_options);

        $head = array('page_title' => 'Outbox Message List', 'status_type' => 1);
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');

        $this->assign('labels', $labels);
        $config['total_rows'] = $this->sendmessagemodel->count_outbox_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', '');
        $this->set_pagination($config);
        $sendmessages = $this->sendmessagemodel->get_outbox_list($search_data);
        $this->assign('records', $sendmessages);
        $this->load->view('sendmessage/outbox_list', $head);
    }

    function outboxMessageExcel() {
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'write_time' => 'Write Time', 'sent_time' => 'Sent Time', 'last_updated' => 'Last Updated', 'status' => 'Status');
        $report_exel = $this->sendmessagemodel->get_outbox_list_report(json_decode($this->session->userdata('search_data'), true));
        $this->get_excel($labels, $report_exel);
    }

    function failedMessageList($sort_type = 'desc', $sort_on = 'id') {

        $head = array('page_title' => 'Failed Message List', 'status_type' => 1);
        $labels = array('srcmn' => 'Srcmn', 'mask' => 'Mask', 'destmn' => 'Destmn', 'message' => 'Message', 'operator_prefix' => 'Prefix', 'remarks' => 'Remarks', 'status' => 'Status');

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
        $sendmessageoutbox_id = $this->sendmessagemodel->updateOutbox($id, $data);
        $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'fail sms'));
        redirect('sendmessage/failedMessageList');
    }

    function view($id = '') {
        $sentMessageList = $this->sendmessagemodel->get_sendmessage_details($id);
        foreach ($sentMessageList as $val) {
            if (strpos($val['recipient'], ',') != true AND $val['group_id'] == '' AND $val['file'] == '') {

                $data['sentFrom'] = $val['sentFrom'];
                $data['senderID'] = $val['senderID'];
                $data['status'] = $val['status'];
                $data['IP'] = $val['IP'];
                $data['sms_type'] = $val['sms_type'];
                $data['recipient'] = rtrim($val['recipient'], ',');
                $data['message'] = $val['message'];
            }
            if (strpos($val['recipient'], ',') != false) {
                //echo 'dddddddddddddd';exit;

                $batchArray = array();
                $receipientArray = explode(',', $val['recipient']);

                $data['sentFrom'] = $val['sentFrom'];
                $data['senderID'] = $val['senderID'];
                $data['status'] = $val['status'];
                $data['IP'] = $val['IP'];
                $data['sms_type'] = $val['sms_type'];
                $data['recipient'] = $receipientArray;
                $data['message'] = $val['message'];
            }
            if ($val['group_id'] != '' OR $val['group_id'] != NULL) {
                $allRecepient = $this->sendmessagemodel->getAllGroupRecipient(explode(',', $val['group_id']));
                $batchArray = array();

                $data['sentFrom'] = $val['sentFrom'];
                $data['senderID'] = $val['senderID'];
                $data['status'] = $val['status'];
                $data['IP'] = $val['IP'];
                $data['sms_type'] = $val['sms_type'];
                $data['recipient'] = $allRecepient;
                $data['message'] = $val['message'];
            }
            if ($val['file'] != '' OR $val['file'] != NULL) {
                $ext = end(explode(".", $val['file']));
                if ($ext == 'csv') {
                    $handle = fopen(base_url() . 'upload/group_members/' . $val['file'], "r");

                    if ($val['source'] == 'WEB') {
                        while (($csvdata = fgetcsv($handle, 10000, ",")) !== FALSE) {

                            $message[] = mysql_real_escape_string($csvdata[0]);
                            $mask[] = mysql_real_escape_string($csvdata[1]);
                            $phone2 = mysql_real_escape_string($csvdata[2]);
							
							$phone[] = $phone2;
                        }
                    }
                    if ($val['source'] == 'API') {
                        while (($csvdata = fgetcsv($handle, 10000, ",")) !== FALSE) {

                            $message[] = mysql_real_escape_string($csvdata[0]);
                            $mask[] = mysql_real_escape_string($csvdata[1]);
                            $phone2 = mysql_real_escape_string($csvdata[2]);

                            $phone[] = $phone2;
                        }
                    }
                } // end of csv
				elseif($ext == 'xls' OR $ext == 'xlsx'){

					$targetPath = "./upload/group_members/".$val['file'];
					$file = $targetPath;
					$this->load->library('excel');
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
					foreach ($cell_collection as $cell) {
						$column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
						$row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
						$data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
						if ($row == 0) {
							$header[$row][$column] = $data_value;
						} else {
							$arr_data[$row][$column] = $data_value;
						}
					}
			 		
					foreach($arr_data as $val){
						
						if($val['A']!=''){
							$name = $val['A'];
						}
						if($val['A']==''){
							$name = 'Untitled';
						}
						if($val['B']!=''){
							$phone[] = $val['B'];
						}
						if($val['B']==''){
							$phone[] = 0;
						}
					}				
				}
				else {

                    $phoneList = file_get_contents(base_url() . 'upload/group_members/' . $val['file']);
                    $phoneList = mysql_real_escape_string($phoneList);

                    $phoneList = str_replace(";", ",", $phoneList);

                    $phoneList = str_replace(" ", ",", $phoneList);

                    $phoneList = strtr($phoneList, array('\r\n' => ','));

                    $phoneList = preg_replace("/[^0-9+,]/", "", $phoneList);

                    $phoneList = explode(',', $phoneList);

                    //add

                    $count = count($phoneList);

                    for ($w = 0; $w < $count; $w++) {

                        if (!empty($phoneList[$w])) {

                            $phone[] = $phoneList[$w];
                        }
                    }
                }

                $batchArray = array();

                $data['sentFrom'] = $val['sentFrom'];
                $data['senderID'] = $val['senderID'];
                $data['status'] = $val['status'];
                $data['IP'] = $val['IP'];
                $data['sms_type'] = $val['sms_type'];
                $data['recipient'] = $phone;
                $data['message'] = $val['message'];
            }
        }
        $this->assign('data', $data);
        $this->load->view('sendmessage/view_sendmessage');
    }

    function composemessage() {
        $this->tpl->set_page_title("Add new sendmessage");
        $this->load->library(array('form_validation'));
        $config1 = array(
            array(
                'field' => 'senderID',
                'label' => 'Mask',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => 'message',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'sms_type',
                'label' => 'sms_type',
                'rules' => 'trim|xss_clean'
            ),
        );

       $configpersonalized = array(
            array(
                'field' => 'senderID',
                'label' => 'Mask',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => 'message',
                'rules' => 'trim'
            ),

            array(
                'field' => 'sms_type',
                'label' => 'sms_type',
                'rules' => 'trim|xss_clean'
            ),
        );

        $config2 = array(
            array(
                'field' => 'recipient',
                'label' => 'recipient',
                'rules' => 'trim|required'
            ),
        );

        $config3 = array(
            array(
                'field' => 'group_id[]',
                'label' => 'group',
                'rules' => 'trim|required'
            ),
        );

        $config4 = array(
            array(
                'field' => 'scheduleDateTime',
                'label' => 'scheduleDateTime',
                'rules' => 'trim|xss_clean|required'
            )
        );


        if ($this->input->post('receiver') == 'number' AND $this->input->post('sms_type') == '') {
            $config = array_merge($config1, $config2);
        }

        if ($this->input->post('receiver') == 'number' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config2, $config4);
        }

        if ($this->input->post('receiver') == 'group' AND $this->input->post('sms_type') == '') {
            $config = array_merge($config1, $config3);
        }
        if ($this->input->post('receiver') == 'group' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config3, $config4);
        }

        if ($this->input->post('receiver') == 'file' AND $this->input->post('sms_type') == '') {
            $config = array_merge($configpersonalized);
        }
		if ($this->input->post('receiver') == 'file' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config4);
        }

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circ1le"></i> ', '</span>');

        
        if ($this->session->userdata('user_group_id') == 3 || $this->session->userdata('user_group_id') == 2) {
            #print_r($this->session->userdata);
            $mask_options = $this->sendmessagemodel->mask_option($this->user_id);
        } else {
            $mask_options = $this->sendmessagemodel->mask_option();
        }
        $this->assign('mask_options', $mask_options);

        $group_options = $this->sendmessagemodel->get_all_group();
        $this->assign('group_options', $group_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'New Message', 'link_title' => 'Message List', 'link_action' => 'sendmessage');
            if($this->user_status=='INACTIVE' || $this->user_status=='PENDING'){
				$this->load->view('sendmessage/block_sendmessage', $head);
			}else{
				$this->load->view('sendmessage/new_sendmessage', $head);
			}			
        } else {
            if ($this->input->post('sms_type') == '') {
                $sms_type = 'SentMessage';
            } else {
                $sms_type = 'ScheduleMessage';
            }

            $new_name = date('Ymdhis') . '_' . $_FILES["file"]["name"];
			
            if ($this->upload_image('file', $new_name)) {
                $data['user_id'] = $this->user_id;
                $data['message'] = $this->input->post('message');
                $data['senderID'] = $this->input->post('senderID');
                $data['recipient'] = $this->input->post('recipient');
                if ($this->input->post('group_id')) {
                    $data['group_id'] = rtrim(implode(',', $this->input->post('group_id')), ',');    //it will be comma separeted value
                }
                $data['date'] = date('Y-m-d h:i:s');
                $data['pages'] = $this->input->post('page');
                $data['units'] = $this->input->post('temp_count') * $this->input->post('page');
                $data['sentFrom'] = '8167';
                $data['is_unicode'] = $this->input->post('is_unicode');
                $data['IP'] = $this->get_client_ip();
                $data['sms_type'] = $sms_type;
                $data['scheduleDateTime'] = $this->input->post('scheduleDateTime');
                $data['file'] = $new_name;
                $sendmessage_id = $this->sendmessagemodel->add($data);
                if ($sendmessage_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Sms'));
                    redirect('sendmessage');
                }
            } else {
                $data['user_id'] = $this->user_id;
                $data['message'] = $this->input->post('message');
                $data['senderID'] = $this->input->post('senderID');
                $data['recipient'] = $this->input->post('recipient');
                if ($this->input->post('group_id')) {
                    $data['group_id'] = rtrim(implode(',', $this->input->post('group_id')), ',');    //it will be comma separeted value
                }

                $data['date'] = date('Y-m-d h:i:s');
                $data['pages'] = $this->input->post('page');
                $data['units'] = $this->input->post('temp_count') * $this->input->post('page');
                $data['sentFrom'] = '8167';
                $data['is_unicode'] = $this->input->post('is_unicode');
                $data['IP'] = $this->get_client_ip();
                $data['sms_type'] = $sms_type;
                $data['scheduleDateTime'] = $this->input->post('scheduleDateTime') . ' ' . $this->input->post('hour') . ':' . $this->input->post('min') . ':00';
                $sendmessage_id = $this->sendmessagemodel->add($data);
                if ($sendmessage_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Sms'));
                    redirect('sendmessage');
                }
            }
        }
    }

    function resend($id) {
        $this->tpl->set_page_title("Resend sendmessage");
        $this->load->library(array('form_validation'));
        $resend = $this->sendmessagemodel->get_record($id);       // get record
        $this->assign($resend);

        $config1 = array(
            array(
                'field' => 'senderID',
                'label' => 'Mask',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'message',
                'label' => 'message',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'sms_type',
                'label' => 'sms_type',
                'rules' => 'trim|xss_clean'
            ),
        );

        $config2 = array(
            array(
                'field' => 'recipient',
                'label' => 'recipient',
                'rules' => 'trim|required'
            ),
        );

        $config3 = array(
            array(
                'field' => 'group_id[]',
                'label' => 'group',
                'rules' => 'trim|required'
            ),
        );

        $config4 = array(
            array(
                'field' => 'scheduleDateTime',
                'label' => 'scheduleDateTime',
                'rules' => 'trim|xss_clean|required'
            )
        );


        if ($this->input->post('receiver') == 'number' AND $this->input->post('sms_type') == '') {
            $config = array_merge($config1, $config2);
        }
        if ($this->input->post('receiver') == 'number' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config2, $config4);
        }
        if ($this->input->post('receiver') == 'group' AND $this->input->post('sms_type') == '') {
            $config = array_merge($config1, $config3);
        }
        if ($this->input->post('receiver') == 'group' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config3, $config4);
        }
        if ($this->input->post('receiver') == 'file' AND $this->input->post('sms_type') == '') {
            $config = array_merge($config1);
        }
        if ($this->input->post('receiver') == 'file' AND $this->input->post('sms_type') == 'ScheduleMessage') {
            $config = array_merge($config1, $config4);
        }
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $mask_options = $this->sendmessagemodel->mask_option();
        $this->assign('mask_options', $mask_options);
        $group_options = $this->sendmessagemodel->get_all_group();
        $this->assign('group_options', $group_options);
        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'ReSend Message', 'link_title' => 'Send Message List', 'link_action' => 'sendmessage');
            $this->load->view('sendmessage/resend_sendmessage', $head);
        } else {
            if ($this->input->post('sms_type') == '') {
                $sms_type = 'SentMessage';
            } else {
                $sms_type = 'ScheduleMessage';
            }
            $new_name = date('Ymdhis') . '_' . $_FILES["file"]["name"];
            if ($this->upload_image('file', $new_name) OR $this->input->post('file1')) {
                $data['user_id'] = $this->user_id;
                $data['message'] = $this->input->post('message');
                $data['senderID'] = $this->input->post('senderID');
                $data['recipient'] = $this->input->post('recipient');
                if ($this->input->post('group_id')) {
                    $data['group_id'] = rtrim(implode(',', $this->input->post('group_id')), ',');    //it will be comma separeted value
                }
                $data['date'] = date('Y-m-d h:i:s');
                $data['pages'] = $this->input->post('page');
                $data['units'] = $this->input->post('temp_count') * $this->input->post('page');
                $data['sentFrom'] = '8167';
                $data['is_unicode'] = $this->input->post('is_unicode');
                $data['IP'] = $this->get_client_ip();
                $data['sms_type'] = $sms_type;
                $data['scheduleDateTime'] = $this->input->post('scheduleDateTime');
                if ($this->input->post('file1')) {
                    $data['file'] = $this->input->post('file1');
                } else {
                    $data['file'] = $new_name;
                }
                $sendmessage_id = $this->sendmessagemodel->add($data);
                if ($sendmessage_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Sms'));
                    redirect('sendmessage');
                }
            } else {
                $data['user_id'] = $this->user_id;
                $data['message'] = $this->input->post('message');
                $data['senderID'] = $this->input->post('senderID');
                $data['recipient'] = $this->input->post('recipient');
                if ($this->input->post('group_id')) {
                    $data['group_id'] = rtrim(implode(',', $this->input->post('group_id')), ',');    //it will be comma separeted value
                }
                $data['date'] = date('Y-m-d h:i:s');
                $data['pages'] = $this->input->post('page');
                $data['units'] = $this->input->post('temp_count') * $this->input->post('page');
                $data['sentFrom'] = '8167';
                $data['is_unicode'] = $this->input->post('is_unicode');
                $data['IP'] = $this->get_client_ip();
                $data['sms_type'] = $sms_type;
                $data['scheduleDateTime'] = $this->input->post('scheduleDateTime') . ' ' . $this->input->post('hour') . ':' . $this->input->post('min') . ':00';
                $sendmessage_id = $this->sendmessagemodel->add($data);
                if ($sendmessage_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Sms'));
                    redirect('sendmessage');
                }
            }
        }
    }

    function upload_image($field_name, $image_name) {
        $upconfig['upload_path'] = './upload/group_members/';
        $file_info = pathinfo($image_name);
        $upconfig['file_name'] = basename($image_name, '.' . $file_info['extension']);
        $upconfig['allowed_types'] = "*";
        $upconfig['max_size'] = '500000';
        $upconfig['overwrite'] = FALSE;

        $this->load->library('upload', $upconfig);

        if (!$this->upload->do_upload($field = $field_name)) {
            print $this->upload->display_errors();
            return false;
        } else {
            $updata = $this->upload->data();
            return true;
        }
    }
}

?>
