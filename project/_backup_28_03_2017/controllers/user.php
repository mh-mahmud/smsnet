<?php

class User extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->admin_group_id = $this->session->userdata('user_group_id');
        $this->load->model(array('billingmodel', 'usermodel', 'usergroupmodel', 'walletmodel', 'operatormodel', 'channelmodel', 'optionmodel'));
        $this->tpl->set_page_title('User Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
        $this->load->library('Excel');
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();
        $this->session->set_userdata('search_data', json_encode($search_data));
        $user_group_options = $this->usergroupmodel->group_option();   // get user group list
        $this->assign('user_group_options', $user_group_options);
        $user_options = $this->walletmodel->user_options();   // get user list
        $this->assign('user_options', $user_options);

        $status_options = array('Active' => 'Active', 'Inactive' => 'Inactive');   // get user group list
        $this->assign('status_options', $status_options);

        $rate_options = $this->optionmodel->get_rate();
        $this->assign('rate_options', $rate_options);

        $head = array('page_title' => 'User List', 'link_title' => 'New User', 'link_action' => 'user/add', 'status_type' => 1);
        $labels = array('username' => 'Username', 'mobile' => 'Mobile No.', 'address' => 'Address', 'user_type' => 'User Type', 'rate_name' => 'Rate Config', 'status' => 'Status');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->usermodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('view' => 'view', 'edit' => 'edit', 'del' => 'del', 'sms' => 'sms', 'login' => 'login', 'userBilling' => 'userBilling',));
        $this->set_pagination($config);
        $users = $this->usermodel->get_list($search_data);
        $this->assign('records', $users);
        $this->load->view('user/user_list', $head);
    }

    function userBilling($id) {
        
        $start_date = date('Y-m-d 00:00:00', strtotime('first day of last month'));
        $end_date = date('Y-m-d 23:59:59', strtotime('last day of last month'));
        
        $billingReport = $this->billingmodel->get_billingReport($id, $start_date, $end_date);
        //echo '<pre>'; print_r($billingReport); exit;
        $this->assign('billingReport', $billingReport);
        $this->load->view('billing/billingReport');
    }

    function view($id = '') {
        if ($id == '') {
            redirect('user');
        }
        $this->tpl->set_page_title("View User information");

        $user = $this->usermodel->get_user_details($id);       // get record
        $this->assign($user);


        $head = array('page_title' => 'User Details', 'link_title' => 'User List', 'link_action' => 'user');
        $this->load->view('user/view_user', $head);
    }

    function add() {
        $this->tpl->set_page_title("Add new user");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[5]|max_length[20]|xss_clean|callback_duplicate_user_check'
            ),
            array(
                'field' => 'passwd',
                'label' => 'Password',
                'rules' => 'trim|required|matches[confirm_password]|min_length[6]'
            ),
            array(
                'field' => 'confirm_password',
                'label' => 'Confirmation',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'id_user_group',
                'label' => 'Admin Group',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|valid_email|callback_duplicate_email_check|xss_clean'
            ),
            array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'mobile',
                'label' => 'Mobile number',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'rate_id',
                'label' => 'Rate Config',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'billing_type',
                'label' => 'Billing Type',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'mrc_otc',
                'label' => 'MRC/OTC',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'duration_validity',
                'label' => 'Validity (Duration)',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'bill_start',
                'label' => 'Bill Cycle Start',
                'rules' => 'trim|xss_clean'
            ),
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        $user_group_options = $this->usergroupmodel->group_option();   // get user group list
        $this->assign('user_group_options', $user_group_options);

        $this->assign("user_type_options", array('prepaid' => "Pre-Paid", 'postpaid' => 'Post-Paid'));

        $rate_options = $this->optionmodel->get_rate();
        $this->assign('rate_options', $rate_options);


        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'New User', 'link_title' => 'User List', 'link_action' => 'user');
            $this->load->view('user/new_user', $head);
        } else {

            $data['username'] = $this->input->post('username');
            $data['passwd'] = md5($this->input->post('passwd'));
            $data['email'] = $this->input->post('email');
            $data['mobile'] = $this->input->post('mobile');
            $data['address'] = $this->input->post('address');
            $data['id_user_group'] = $this->input->post('id_user_group');
            $data['rate_id'] = $this->input->post('rate_id');
            $data['billing_type'] = $this->input->post('billing_type');
            $data['mrc_otc'] = $this->input->post('mrc_otc');

            if ($this->input->post('duration_validity') != '')
                $data['duration_validity'] = $this->input->post('duration_validity');
            else
                $data['duration_validity'] = 30;

            if ($this->input->post('bill_start') != '')
                $data['bill_start'] = $this->input->post('bill_start');
            else
                $data['bill_start'] = 1;

            $data['created_by'] = $this->user_id;
            $user_id = $this->usermodel->add($data);
            if ($user_id) {
                //$this->addDefaultPrice($user_id);
                //$this->addUnittPrice($user_id);
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'User'));
                redirect('user');
            }
        }
    }

    function addDefaultPrice($user_id) {
        $operator_options = $this->channelmodel->getOperatorOption();
        foreach ($operator_options as $key => $opval) {
            $data['operator_id'] = $key;
            $data['buying_rate'] = $this->operatormodel->get_buying_rate_operatorid($key);
            $data['selling_rate'] = 0;
            $data['created_by'] = $user_id;
            $data['created'] = $this->current_date();
            $this->operatormodel->addDefaultPrice($data);
        }
    }

    function addUnittPrice($user_id) {
        $operator_options = $this->channelmodel->getOperatorOption();
        foreach ($operator_options as $key => $opval) {
            $data['operator_id'] = $key;
            $data['user_id'] = $user_id;
            $data['unit_cost'] = 0; //$this->operatormodel->get_buying_rate_operatorid($key);
            $data['created_by'] = $this->user_id;
            $data['created'] = $this->current_date();
            $this->operatormodel->addUnittPrice($data);
        }
    }

    function edit($id = '', $profile = '') {
        $this->tpl->set_page_title("Edit user information");
        $this->load->library(array('form_validation'));
        $user = $this->usermodel->get_record($id);       // get record
        $this->assign($user);

        $config1 = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|min_length[5]|max_length[20]|xss_clean|callback_duplicate_user_check[' . $user['username'] . ']'
            ),
            array(
                'field' => 'id_user_group',
                'label' => 'Admin Group',
                'rules' => 'required'
            )
        );

        $config2 = array(
            array(
                'field' => 'passwd',
                'label' => 'Password',
                'rules' => 'trim|matches[confirm_password]|min_length[6]'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|valid_email|callback_duplicate_email_check[' . $user['email'] . ']'
            ),
            array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'mobile',
                'label' => 'Mobile number',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'rate_id',
                'label' => 'Rate Config',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'billing_type',
                'label' => 'Billing Type',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'mrc_otc',
                'label' => 'MRC/OTC',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'duration_validity',
                'label' => 'Validity (Duration)',
                'rules' => 'trim|xss_clean'
            ),
            array(
                'field' => 'bill_start',
                'label' => 'Bill Cycle Start',
                'rules' => 'trim|xss_clean'
            ),
        );

        if ($profile != '') {
            $config = $config2;
        } else {
            $config = array_merge($config1, $config2);
        }

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $this->form_validation->set_message('matches', 'Confirm password did not match with password');

        $user_group_options = $this->usergroupmodel->group_option();   // get user group list
        $this->assign('user_group_options', $user_group_options);

        $rate_options = $this->optionmodel->get_rate();
        $this->assign('rate_options', $rate_options);
        $this->assign("user_type_options", array('prepaid' => "Pre-Paid", 'postpaid' => 'Post-Paid'));


        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit User', 'link_title' => 'User List', 'link_action' => 'user');
            if ($profile != '' AND $this->admin_group_id != 1) {
                $this->assign('profile', $profile);
                $this->load->view('user/edit_user_profile', $head);
            } else {
                $this->load->view('user/edit_user', $head);
            }
        } else {
            if ($profile != '') {
                $data['email'] = $this->input->post('email');
                $data['mobile'] = $this->input->post('mobile');
                $data['address'] = $this->input->post('address');
                $password = $this->input->post('passwd');
            } else {

                $data['username'] = $this->input->post('username');
                $data['id_user_group'] = $this->input->post('id_user_group');
                $data['email'] = $this->input->post('email');
                $data['mobile'] = $this->input->post('mobile');
                $data['address'] = $this->input->post('address');
                $data['rate_id'] = $this->input->post('rate_id');
                $password = $this->input->post('passwd');
                $data['billing_type'] = $this->input->post('billing_type');
                $data['mrc_otc'] = $this->input->post('mrc_otc');

                if ($this->input->post('duration_validity') != '')
                    $data['duration_validity'] = $this->input->post('duration_validity');
                else
                    $data['duration_validity'] = 30;

                if ($this->input->post('bill_start') != '')
                    $data['bill_start'] = $this->input->post('bill_start');
                else
                    $data['bill_start'] = 1;
            }

            if ($password != NULL) {
                $data['passwd'] = md5($this->input->post('passwd'));
            }
            $this->usermodel->edit($id, $data);   // Update data 
            $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'User'));
            if ($profile) {
                //redirect('user');
                redirect('user/view/' . $id);
            } else {
                redirect('user');
            }
        }
    }

    function set_status($id, $val) {
        $this->usermodel->change_status($id, $val);
        echo $this->status_change($id, $val);
    }

    function set_deposite_status($id, $val) {
        $return = $this->walletmodel->change_deposite_status($id, $val);
        if ($return == true) {
            echo $this->status_change($id, $val);
        } else {
            echo $this->status_change($id, 'Pending');
        }
    }

    function del($id) {
        $present_user_id = $this->session->userdata('user_userid');
        if ($present_user_id == $id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'You can not delete yourself.');
        } else {
            $this->usermodel->del($id);
            $status = 1;
            $message = $this->tpl->set_message('delete', 'User');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
    }

    function password_check($str, $param = '') {
        if (!empty($str)) {
            if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('password_check', 'Password must contain at least uppercase,lowercase and number characters');
                return FALSE;
            }
        }
    }

    /* End password check  */


    /* check duplicate email for validation */

    function duplicate_email_check($str, $param = '') {
        $count = $this->usermodel->duplicate_email_check($str, $param);
        if ($count > 0) {
            $this->form_validation->set_message('duplicate_email_check', "%s <span style='color:green;'>$str</span> already exists.");
            return false;
        }
        return true;
    }

    /* validation function for checking username duplicacy */

    function duplicate_user_check($str, $param = '') {
        $count = $this->usermodel->duplicate_user_check($str, $param);
        if ($count > 0) {
            $this->form_validation->set_message('duplicate_user_check', "%s <span style='color:green;'>$str</span> already exists.");
            return false;
        }
        return true;
    }

    function update_subcategory() {
        $subcategory_list = $this->input->post('subcategory');
        $data['subcategory_id'] = join(', ', $subcategory_list);
        $this->usermodel->update_subcategory($data);
        $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Subcategory'));
        redirect('user/view/' . $this->session->userdata('user_userid'));
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

    function excel() {
        $labels = array('username' => 'Username', 'mobile' => 'Mobile No.', 'address' => 'Address', 'user_type' => 'User Type', 'status' => 'Status');
        $report_exel = $this->usermodel->get_list_report(json_decode($this->session->userdata('search_data'), true));
        $this->get_excel($labels, $report_exel);
    }

    function userWallet($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Wallet List', 'link_title' => '#', 'link_action' => '#', 'status_type' => 1);
        $labels = array('username' => 'Username', 'credite_balance' => 'Credit Balance (BDT/USD)', 'updated_at' => 'Last Update');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->walletmodel->count_wallet_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array());
        $this->set_pagination($config);
        $wallets = $this->walletmodel->get_wallet_list($search_data);
        $this->assign('records', $wallets);
        $this->load->view('wallet/wallet_list', $head);
    }

//Wallet
    function depositIndex($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Payment List', 'link_title' => 'New Payment', 'link_action' => 'user/walletAdd', 'status_type' => 1);
        $labels = array('username' => 'Borrower', 'deposited_by' => 'Deposited By', 'deposit_amount' => 'Amount', 'curency' => 'Curency', 'created' => 'Deposit Date', 'approved_date' => 'Approved Date', 'status' => 'Status');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->walletmodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('walletEdit' => 'walletEdit'));
        $this->set_pagination($config);
        $wallets = $this->walletmodel->get_list($search_data);
        $this->assign('records', $wallets);
        $this->load->view('wallet/wallet_depositlist', $head);
    }

    function walletHistory($sort_type = 'desc', $sort_on = 'id') {
        $head = array('page_title' => 'Wallet History', 'link_title' => 'Wallet History', 'link_action' => 'user/walletHistory', 'status_type' => 1);
        $labels = array('deposited_by' => 'Deposited By', 'deposit_amount' => 'Amount', 'curency' => 'Curency', 'created' => 'Date');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->walletmodel->count_walletHistory_list();
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array());
        $this->set_pagination($config);
        $walletSummery = $this->walletmodel->get_walletHistory_summery();
        $wallets = $this->walletmodel->get_walletHistory_list();
        $this->assign('records', $wallets);
        $this->assign('walletSummery', $walletSummery);
        $this->load->view('wallet/wallet_history', $head);
    }

    function depositHistory($sort_type = 'desc', $sort_on = 'id') {
        $head = array('page_title' => 'Wallet History', 'link_title' => 'Wallet History', 'link_action' => 'user/depositHistory', 'status_type' => 1);
        $labels = array('username' => 'Username', 'deposit_amount' => 'Amount', 'curency' => 'Curency', 'approved_date' => 'Approved Date');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->walletmodel->count_depositHistory_list();
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array());
        $this->set_pagination($config);
        $depositSummery = $this->walletmodel->get_depositHistory_summery();
        $deposits = $this->walletmodel->get_depositHistory_list();
        $this->assign('records', $deposits);
        $this->assign('depositSummery', $depositSummery);
        $this->load->view('wallet/deposit_history', $head);
    }

    function walletAdd() {
        $this->tpl->set_page_title("Add new wallet");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'user_id',
                'label' => 'User',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'deposit_amount',
                'label' => 'Amount',
                //'rules' => 'trim|required|callback_userCreditCheck'
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'curency',
                'label' => 'curency',
                'rules' => 'trim|required'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        $user_options = $this->optionmodel->get_user();   // get user list
        $this->assign('user_options', $user_options);
        $curency_options = array('BDT' => 'BDT', 'USD' => 'USD');   // get user list
        $this->assign('curency_options', $curency_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'New Payment', 'link_title' => 'Payment List', 'link_action' => 'user/wallet');
            $this->load->view('wallet/new_wallet', $head);
        } else {
            $data['user_id'] = $this->input->post('user_id');
            $data['depositor_user_id'] = $this->user_id;
            $data['deposit_amount'] = $this->input->post('deposit_amount');
            $data['deposited_by'] = $this->session->userdata('user_username');
            $data['curency'] = $this->input->post('curency');
            $data['status'] = 'Pending';
            $data['created'] = $this->current_date();
            $deposit_id = $this->walletmodel->add($data);
            if ($deposit_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Deposite'));
                redirect('user/depositIndex');
            }
        }
    }

    function walletEdit($id = '', $profile = '') {
        $this->tpl->set_page_title("Edit wallet information");
        $this->load->library(array('form_validation'));
        $wallet = $this->walletmodel->get_record($id);       // get record
        $this->assign($wallet);

        $config = array(
            array(
                'field' => 'user_id',
                'label' => 'User',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'deposit_amount',
                'label' => 'Amount',
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'curency',
                'label' => 'curency',
                'rules' => 'trim|required'
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $this->form_validation->set_message('matches', 'Confirm password did not match with password');

        $user_options = $this->optionmodel->get_user();   // get user list
        $this->assign('user_options', $user_options);
        $curency_options = array('BDT' => 'BDT', 'USD' => 'USD');   // get user list
        $this->assign('curency_options', $curency_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit Payment', 'link_title' => 'Payment List', 'link_action' => 'wallet');
            $this->load->view('wallet/edit_wallet', $head);
        } else {
            $data['user_id'] = $this->input->post('user_id');
            $data['depositor_user_id'] = $this->user_id;
            $data['deposit_amount'] = $this->input->post('deposit_amount');
            $data['deposited_by'] = $this->session->userdata('user_username');
            $data['curency'] = $this->input->post('curency');
            $data['status'] = 'Pending';
            $data['created'] = $this->current_date();
            $this->walletmodel->edit($id, $data);   // Update data 
            $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Deposite'));
            redirect('user/depositIndex');
        }
    }

    function userCreditCheck($str, $param = '') {
        $count = $this->walletmodel->userCreditCheck($str, $param);
        if ($count == 1) {
            $this->form_validation->set_message('userCreditCheck', "%s <span style='color:green;'>$str</span> is not available.");
            return false;
        }
        return true;
    }

}

?>