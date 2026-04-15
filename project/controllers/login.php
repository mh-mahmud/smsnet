<?php

class Login extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->set_layout('login_layout');
        $this->load->helper(array('html', 'array', 'form'));
        $this->load->model('Loginmodel');  // Load Model
    }

    function index() {
        if ($this->session->userdata('validated') == true) {
            redirect('home');
        }
        $this->load->view('login/login_page');
    }

    function login_check() {
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'passwd',
                'label' => 'passwd',
                'rules' => 'trim|required|xss_clean'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        if ($this->form_validation->run() == FALSE) {
            $status = 0;
        } else {
            //$ipaddress = $_SERVER['REMOTE_ADDR'];
            $user = $this->Loginmodel->check_login();
            if (!empty($user)) {
                $date_time = $this->current_date();
                $this->Loginmodel->update_login_time($user['id'], $date_time);  // update last login time

                $sdata = array(
                    'user_username' => $user['username'],
                    'user_userid' => $user['id'],
                    'user_group_id' => $user['id_user_group'],
                    'parent_id' => $user['created_by'],
                    'user_status' => $user['status'],
                    'validated' => true,
                    'rateid' => $user['rate_id']
                    
                );
                $this->session->set_userdata($sdata);

                $status = 1;

                $datah['employee_id'] = $this->session->userdata('user_userid');
                $datah['today_status'] = '';
                $datah['tomorrow_task'] = '';
                $datah['priv_status'] = '';
                $datah['today_task'] = '';
                $datah['login_date_time'] = $this->current_date();

                //if($this->session->userdata('user_group_id')!=1)
                //$history_id = $this->Loginmodel->add($datah);
                //$this->session->set_userdata('history_id',$history_id);					
            } else {
                $status = 0;
            }
        }
        echo $status;
        exit;
    }

    function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }

}

?>
