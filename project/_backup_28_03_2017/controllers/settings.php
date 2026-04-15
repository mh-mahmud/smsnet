<?php

class Settings extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('sendmessagemodel', 'usermodel', 'usergroupmodel', 'maskmodel', 'operatormodel', 'optionmodel'));
        $this->tpl->set_page_title('User Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
    }

    function create_api() {
        $access_check = $this->usermodel->access_check($this->user_id);
        if ($access_check['APIKEY'] AND $access_check['SECRETKEY']) {
            $this->assign('url', site_url('bulk/api'));
            $this->assign('auth', $access_check);
            $this->load->view('user/user_api');
        } else {
            $data['APIKEY'] = md5(uniqid(rand(), TRUE)) . $this->user_id;
            $data['SECRETKEY'] = md5(uniqid(rand(), TRUE)) . $this->user_id;
            $this->usermodel->edit($this->user_id, $data);
            $this->assign('url', site_url('bulk/api'));
            $this->assign('auth', $data);
            $this->load->view('user/user_api');
        }
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Mask List', 'link_title' => 'New Mask', 'link_action' => 'settings/add', 'status_type' => 1);
        $labels = array('senderID' => 'Mask');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->maskmodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('edit' => 'edit', 'del' => 'del'));
        $this->set_pagination($config);
        $masks = $this->maskmodel->get_list($search_data);
        $this->assign('records', $masks);
        $this->load->view('mask/mask_list', $head);
    }

    function add() {
        $this->tpl->set_page_title("Add new mask");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'senderID',
                'label' => 'Mask',
                'rules' => 'trim|required|xss_clean|callback_duplicate_mask_check'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Mask Details', 'link_title' => 'Mask List', 'link_action' => 'mask');
            $this->load->view('mask/new_mask', $head);
        } else {
            $data['senderID'] = $this->input->post('senderID');
            $data['user_id'] = $this->user_id;
            $mask_id = $this->maskmodel->add($data);
            if ($mask_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Mask'));
                redirect('settings');
            }
        }
    }

    function edit($id = '') {
        $this->tpl->set_page_title("Edit mask information");
        $this->load->library(array('form_validation'));
        $mask = $this->maskmodel->get_record($id);       // get record
        $this->assign($mask);

        $config = array(
            array(
                'field' => 'senderID',
                'label' => 'Mask',
                'rules' => 'trim|required|xss_clean|callback_duplicate_mask_check[' . $mask['senderID'] . ']'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit Mask', 'link_title' => 'Mask List', 'link_action' => 'mask');
            $this->load->view('mask/edit_mask', $head);
        } else {
            $data['senderID'] = $this->input->post('senderID');
            $data['user_id'] = $this->user_id;
            $this->maskmodel->edit($id, $data);   // Update data 
            $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Mask'));
            redirect('settings');
        }
    }

    function del($id) {
        if (!$id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'Not Delete.');
        } else {
            $this->maskmodel->del($id);
            $status = 1;
            $message = $this->tpl->set_message('delete', 'Mask');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
    }

    /* validation function for checking maskname duplicacy */

    function duplicate_mask_check($str, $param = '') {
        $count = $this->maskmodel->duplicate_mask_check($str, $param);
        if ($count > 0) {
            $this->form_validation->set_message('duplicate_mask_check', "%s <span style='color:green;'>$str</span> already exists.");
            return false;
        }
        return true;
    }

    /*     * ******************** Price Setting ******************* */

    function smsrate() {
        $this->tpl->set_page_title("Operator Message Rate");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'operator_id',
                'label' => 'operator_id',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'buying_rate',
                'label' => 'Buying Rate',
                'rules' => 'trim|xss_clean|decimal'
            ),
            array(
                'field' => 'selling_rate',
                'label' => 'Selling Rate',
                'rules' => 'trim|xss_clean|decimal'
            )
        );
        
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $operator_options = $this->operatormodel->getOperatorSmsRate($this->session->userdata('user_group_id'));
        $this->assign('operator_options', $operator_options);
        if ($this->form_validation->run() == FALSE) {
            $this->assign('user_id', $this->user_id);
            $head = array('page_title' => 'Operator Message Rate Details', 'link_title' => 'Operator Message Rate List', 'link_action' => 'setting/smsrate');
            $this->load->view('administration/operator/smsrate', $head);
        } else {
            $operator_id = $this->operatormodel->updatesmsrate();
            if ($operator_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Operator SMS Rate'));
                redirect('settings/smsrate');
            }
        }
    }

    function assign_mask($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Assign Mask List', 'link_title' => 'New Assign Mask', 'link_action' => 'settings/assign_mask_add', 'status_type' => 1);
        $labels = array('senderID' => 'Mask', 'users' => 'Users');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->maskmodel->count_assign_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', '');
        $this->set_pagination($config);
        $masks = $this->maskmodel->get_assign_list($search_data);

        /* echo '<pre>';
          print_r($masks); exit;
         */
        $this->assign('records', $masks);
        $this->load->view('mask/mask_assign_list', $head);
    }

    function assign_mask_add() {
        $this->tpl->set_page_title("Assign mask");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'mask_id',
                'label' => 'Mask',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'user_id[]',
                'label' => 'User',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');


        $user_options = $this->optionmodel->get_user();
        $this->assign('user_options', $user_options);

        if ($this->session->userdata('user_group_id') == 3 || $this->session->userdata('user_group_id') == 2) {
            $mask_options = $this->sendmessagemodel->mask_option($this->user_id);
        } else {
            $mask_options = $this->sendmessagemodel->mask_option();
        }
        $this->assign('mask_options', $mask_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Mask Details', 'link_title' => 'Mask List', 'link_action' => 'mask');
            $this->load->view('mask/new_assign_mask', $head);
        } else {
            $usersList = $this->input->post('user_id');
            array_push($usersList, $this->user_id);
            $data['user_id'] = implode(",", $usersList);
            $data['created_by'] = $this->user_id;
            $mask_id = $this->maskmodel->edit_assign($this->input->post('mask_id'), $data);
            if ($mask_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Mask Assign'));
                redirect('settings/assign_mask');
            }
        }
    }

}

?>
