<?php

class Group extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('groupmodel'));
        $this->tpl->set_page_title('Group Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Group List', 'link_title' => 'New Group', 'link_action' => 'group/add', 'status_type' => 1);
        $labels = array('name' => 'Name', 'description' => 'Description', 'status' => 'Status');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->groupmodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('view' => 'view', 'edit' => 'edit', 'del' => 'del', 'sms' => 'sms'));
        $this->set_pagination($config);
        $groups = $this->groupmodel->get_list($search_data);
        $this->assign('records', $groups);
        $this->load->view('group/group_list', $head);
    }

    function view($id = '') {
        if ($id == '') {
            redirect('group');
        }
        $this->tpl->set_page_title("View Group information");

        $group = $this->groupmodel->get_group_details($id);       // get record

        $this->assign('group', $group);


        $head = array('page_title' => 'Group Details', 'link_title' => 'Group List', 'link_action' => 'group');
        $this->load->view('group/view_group', $head);
    }

    function Add() {
        $this->tpl->set_page_title("Add new group");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required|xss_clean|callback_duplicate_group_check'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Group Details', 'link_title' => 'Group List', 'link_action' => 'group');
            $this->load->view('group/new_group', $head);
        } else {
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $data['user_id'] = $this->user_id;
            $data['created_by'] = $this->user_id;
            $data['created'] = date('Y-m-d');
            $group_id = $this->groupmodel->add($data);
            if ($group_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Group'));
                redirect('group');
            }
        }
    }

    function edit($id = '') {
        $this->tpl->set_page_title("Edit group information");
        $this->load->library(array('form_validation'));
        $group = $this->groupmodel->get_record($id);       // get record
        $this->assign($group);

        $config = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|xss_clean|callback_duplicate_group_check[' . $group['name'] . ']'
            ),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit Group', 'link_title' => 'Group List', 'link_action' => 'group');
            $this->load->view('group/edit_group', $head);
        } else {
            $data['name'] = $this->input->post('name');
            $data['description'] = $this->input->post('description');
            $this->groupmodel->edit($id, $data);   // Update data 
            $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Group'));
            redirect('group');
        }
    }

    function set_status($id, $val) {
        $this->groupmodel->change_status($id, $val);
        echo $this->status_change($id, $val);
    }

    function set_member_status($id, $val) {
        $this->groupmodel->change_member_status($id, $val);
        echo $this->status_change($id, $val);
    }

    function del($id) {
        if (!$id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'Not Delete.');
        } else {
            $this->groupmodel->del($id);
            $status = 1;
            $message = $this->tpl->set_message('delete', 'Group');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
    }

    /* validation function for checking groupname duplicacy */

    function duplicate_group_check($str, $param = '') {
        $count = $this->groupmodel->duplicate_group_check($str, $param);
        if ($count > 0) {
            $this->form_validation->set_message('duplicate_group_check', "%s <span style='color:green;'>$str</span> already exists.");
            return false;
        }
        return true;
    }

    /*     * **********************   Group contact ******************************* */

    function memberList($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Group Member List', 'link_title' => 'New Group Member', 'link_action' => 'group/memberAdd', 'status_type' => 1);
        $labels = array('name' => 'Name/File', 'groupname' => 'Group Name', 'phone' => 'Phone', 'status' => 'Status');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->groupmodel->member_count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('memberEdit' => 'memberEdit', 'memberDel' => 'memberDel', 'memberSms' => 'memberSms'));
        $this->set_pagination($config);
        $groups = $this->groupmodel->get_memeber_list($search_data);
        $this->assign('records', $groups);
        $this->load->view('group/group_member_list', $head);
    }

    function memberAdd() {
        $this->tpl->set_page_title("Add new group member");
        $this->load->library(array('form_validation'));
        $config1 = array(
            array(
                'field' => 'group_id',
                'label' => 'Group',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $config2 = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'trim|required|xss_clean'
            )
        );

        if ($_FILES["numberfile"]["name"]) {
            $config = $config1;
        } else {
            $config = array_merge($config1, $config2);
        }

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $group_options = $this->groupmodel->group_option();
        $this->assign('group_options', $group_options);
        $this->assign('file', $_FILES["numberfile"]["name"]);
        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Group Member Details', 'link_title' => 'Group Member List', 'link_action' => 'group/memberList');
            $this->load->view('group/new_group_member', $head);
        } else {
            $new_name = date('Ymdhis') . '_' . $_FILES["numberfile"]["name"];

            if ($this->upload_image('numberfile', $new_name)) {
                $ext = end(explode(".", $_FILES['numberfile']['name']));

                if ($ext == 'csv' || $ext == 'CSV') {
                    $handle = fopen($_FILES['numberfile']['tmp_name'], "r");
                    
                    $is_first_line = true;
                    while (($csvdata = fgetcsv($handle, 10000, ",")) !== FALSE) {
                        
                        $numcols = count($csvdata);
                        
                        if($is_first_line) { $is_first_line = false; continue; }
                        
                        if($numcols==0) { continue; }
                        
                        if($numcols ==1){
                            $name = "Untitled";
                            $phone2 = mysql_real_escape_string($csvdata[0]);
                        } else if($numcols >=2){
                            $name = mysql_real_escape_string($csvdata[0]);
                            $phone2 = mysql_real_escape_string($csvdata[1]);
                        }
    
                        $data['group_id'] = $this->input->post('group_id');
                        $data['name'] = $name;
                        $data['phone'] = $phone2;
                        $data['created_by'] = $this->user_id;
                        $data['created'] = date('Y-m-d');
                        $group_id = $this->groupmodel->addMember($data);
                    }
                    if ($group_id) {
                        $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Group Member'));
                        redirect('group/memberList');
                    }
                }elseif($ext == 'xls' OR $ext == 'xlsx'){

					$targetPath = "./upload/group_members/".$new_name;
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
							$phone2 = $val['B'];
						}
						if($val['B']==''){
							$phone2 = 0;
						}
						$data['group_id'] = $this->input->post('group_id');
						$data['name'] = $name;
						$data['phone'] = $phone2;
						$data['created_by'] = $this->user_id;
						$data['created'] = date('Y-m-d');
						$group_id = $this->groupmodel->addMember($data);
					}				
					if ($group_id) 
					{
						$this->session->set_flashdata('message', $this->tpl->set_message('add', 'Group Member'));
						redirect('group/memberList');
					}
/* 					echo '<pre>';
					print_r($arr_data); */
					
				}
                else {

                    $phoneList = file_get_contents($_FILES['numberfile']['tmp_name']);
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

                            $name = 'Untitled';
                            $phone2 = $phoneList[$w];
                            $data['group_id'] = $this->input->post('group_id');
                            $data['name'] = $name;
                            $data['phone'] = $phone2;
                            $data['created_by'] = $this->user_id;
                            $data['created'] = date('Y-m-d');
                            $group_id = $this->groupmodel->addMember($data);
                        }
                    }
                    if ($group_id) {
                        $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Group Member'));
                        redirect('group/memberList');
                    }
                }
            } else {
                $data['name'] = $this->input->post('name');
                $data['group_id'] = $this->input->post('group_id');
                $data['phone'] = $this->input->post('phone');
                $data['created_by'] = $this->user_id;
                $data['created'] = date('Y-m-d');
                $group_id = $this->groupmodel->addMember($data);
                if ($group_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Group Member'));
                    redirect('group/memberList');
                }
            }
        }
    }

    function memberEdit($id = '') {
        $this->tpl->set_page_title("Edit group information");
        $this->load->library(array('form_validation'));
        $group = $this->groupmodel->get_record_member($id);       // get record
        $this->assign($group);

        $config1 = array(
            array(
                'field' => 'group_id',
                'label' => 'Group',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $config2 = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'phone',
                'label' => 'phone',
                'rules' => 'trim|required|xss_clean'
            )
        );

        if ($_FILES["numberfile"]["name"]) {
            $config = $config1;
        } else {
            $config = $config1;
            //$config = array_merge($config1,$config2);
        }

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $group_options = $this->groupmodel->group_option();
        $this->assign('group_options', $group_options);
        $this->assign('editfile', $_FILES["numberfile"]["name"]);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit Group Member', 'link_title' => 'Group member List', 'link_action' => 'group/memberList');
            $this->load->view('group/edit_group_member', $head);
        } else {
            $new_name = date('Ymdhis') . '_' . $_FILES["numberfile"]["name"];
            if ($this->upload_image('numberfile', $new_name)) {
                $new_file_name = str_replace(' ', '_', $new_name);
                $data['name'] = $new_file_name;
                $data['group_id'] = $this->input->post('group_id');
                $data['file'] = $new_file_name;
                $group_member_id = $this->groupmodel->editMember($id, $data);
                if ($group_member_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Member'));
                    redirect('group/memberList');
                }
            } else {
                $data['name'] = $this->input->post('name');
                $data['group_id'] = $this->input->post('group_id');
                $data['phone'] = $this->input->post('phone');
                $group_member_id = $this->groupmodel->editMember($id, $data);
                if ($group_member_id) {
                    $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Member'));
                    redirect('group/memberList');
                }
            }
        }
    }

    function memberDel($id) {
        if (!$id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'Not Delete.');
        } else {
            $this->groupmodel->delMember($id);
            $status = 1;
            $message = $this->tpl->set_message('delete', 'Group member');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
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
	
	function excel() {
         $labels = array('name' => 'Name/File', 'groupname' => 'Group Name', 'phone' => 'Phone');
		$report_exel = $this->groupmodel->get_member_list_report(json_decode($this->session->userdata('search_data'), true));
        $this->get_excel($labels, $report_exel);
    }

}

?>
