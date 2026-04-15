<?php

class Wallet extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->user_id = $this->session->userdata('user_userid');
        $this->load->model(array('walletmodel'));
        $this->tpl->set_page_title('Payment Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Payment List', 'link_title' => 'New Payment', 'link_action' => 'wallet/add', 'status_type' => 1);
        $labels = array('username' => 'Username', 'deposited_by' => 'Deposited By', 'deposit_amount' => 'Amount', 'curency' => 'Curency', 'created' => 'Date');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->walletmodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('edit' => 'edit', 'del' => 'del'));
        $this->set_pagination($config);
        $wallets = $this->walletmodel->get_list($search_data);
        $this->assign('records', $wallets);
        $this->load->view('wallet/wallet_depositlist', $head);
    }

    function userWallet($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();

        $head = array('page_title' => 'Wallet List', 'link_title' => '#', 'link_action' => '#', 'status_type' => 1);
        $labels = array('username' => 'Username', 'credite_balance' => 'Credit Balance', 'updated_at' => 'Last Update');
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

    function view($id = '') {
        if ($id == '') {
            redirect('wallet');
        }
        $this->tpl->set_page_title("View Payment information");

        $wallet = $this->walletmodel->get_wallet_details($id);       // get record
        $this->assign($wallet);


        $head = array('page_title' => 'Payment Details', 'link_title' => 'Payment List', 'link_action' => 'wallet');
        $this->load->view('wallet/view_wallet', $head);
    }

    function add() {
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
                'rules' => 'trim|required|callback_userCreditCheck'
            ),
            array(
                'field' => 'curency',
                'label' => 'curency',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        $user_options = $this->walletmodel->user_options();   // get user list
        $this->assign('user_options', $user_options);
        $curency_options = array('BDT' => 'BDT', 'USD' => 'USD');   // get user list
        $this->assign('curency_options', $curency_options);
        $bill_options = array('Due' => 'Due', 'Paid' => 'Paid');   // get user list
        $this->assign('bill_options', $bill_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'New Payment', 'link_title' => 'Payment List', 'link_action' => 'wallet');
            $this->load->view('wallet/new_wallet', $head);
        } else {
            $data['user_id'] = $this->input->post('user_id');
            $data['depositor_user_id'] = $this->user_id;
            $data['deposit_amount'] = $this->input->post('deposit_amount');
            if ($this->session->userdata('user_group_id') == 1) {
                $data['deposited_by'] = 'Admin';
            }
            if ($this->session->userdata('user_group_id') == 2) {
                $data['deposited_by'] = 'Reseller';
            }
            $data['curency'] = $this->input->post('curency');
            $data['status'] = $this->input->post('status');
            $data['created'] = $this->current_date();
            $deposit_id = $this->walletmodel->add($data);
            if ($deposit_id) {
                $balanceupdate = $this->walletmodel->wallet_update($data['user_id'], $data['deposit_amount']);
                if ($balanceupdate) {
                    $this->walletmodel->admin_wallet_update($data['deposit_amount']);
                }
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Payment'));
                redirect('user/userWallet');
            }
        }
    }

    function edit($id = '', $profile = '') {
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
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'curency',
                'label' => 'curency',
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'status',
                'label' => 'status',
                'rules' => 'trim|required'
            )
        );
        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
        $this->form_validation->set_message('matches', 'Confirm password did not match with password');

        $user_options = $this->walletmodel->user_options();   // get user list
        $this->assign('user_options', $user_options);
        $bill_options = array('Due' => 'Due', 'Paid' => 'Paid');   // get user list
        $this->assign('bill_options', $bill_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Edit Payment', 'link_title' => 'Payment List', 'link_action' => 'wallet');
            $this->load->view('wallet/edit_wallet', $head);
        } else {
            $data['user_id'] = $this->input->post('user_id');
            $data['depositor_user_id'] = $this->user_id;
            $data['deposit_amount'] = $this->input->post('deposit_amount');
            if ($this->session->userdata('id_user_group') == 1) {
                $data['deposited_by'] = 'Admin';
            }
            if ($this->session->userdata('id_user_group') == 2) {
                $data['deposited_by'] = 'Reseller';
            }
            $data['curency'] = $this->input->post('curency');
            $data['status'] = $this->input->post('status');
            $data['created'] = $this->current_date();
            $this->walletmodel->edit($id, $data);   // Update data 

            $this->walletmodel->wallet_update($data['user_id'], ($wallet['deposit_amount'] - $data['deposit_amount']));

            $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Payment'));
            redirect('user/userWallet');
        }
    }

    function del($id) {
        if ($id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'You can not delete.');
        } else {
            $this->walletmodel->del($id);
            $status = 1;

            $wallet = $this->walletmodel->get_record($id);    // get record

            $this->walletmodel->wallet_update($wallet['user_id'], ('-' . $wallet['deposit_amount']));

            $message = $this->tpl->set_message('delete', 'Payment');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
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
