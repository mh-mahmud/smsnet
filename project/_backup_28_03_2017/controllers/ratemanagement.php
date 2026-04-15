<?php

class Ratemanagement extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('ratemodel', 'usermodel', 'usergroupmodel', 'optionmodel', 'operatormodel', 'channelmodel'));
        $this->tpl->set_page_title('User Management');
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker'));
        $this->user_id = $this->session->userdata('user_userid');
    }

    function index($sort_type = 'desc', $sort_on = 'id') {
        $this->load->library('search');
        $search_data = $this->search->data_search();
        $status_options = array('default' => 'Default', 'modified' => 'Modified');
        $this->assign('status_options', $status_options);
        $head = array('page_title' => 'Rate List', 'link_title' => 'Rate', 'link_action' => '#', 'status_type' => 1);
        $labels = array('name' => 'Rate Name', 'created' => 'Date');
        $this->assign('labels', $labels);
        $config['total_rows'] = $this->ratemodel->count_list($search_data);
        $config['uri_segment'] = 6;
        $config['select_value'] = $this->input->post('rec_per_page');
        $config['sort_on'] = $sort_on;
        $config['sort_type'] = $sort_type;
        $this->assign('grid_action', array('view' => 'view', 'edit' => 'edit_rate', 'del' => 'del'));
        $this->set_pagination($config);
        $rate = $this->ratemodel->get_list($search_data);
        $this->assign('records', $rate);
        //echo '<pre/>';print_r($rate);exit;
        $this->load->view('ratemanagement/rate_list', $head);
    }

    /*     * ******************** Price Setting ******************* */

    function defaultrate() {
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
            $this->load->view('ratemanagement/smsrate', $head);
        } else {
            $operator_id = $this->operatormodel->updatesmsrate();
            if ($operator_id) {
                $this->session->set_flashdata('message', $this->tpl->set_message('edit', 'Operator SMS Rate'));
                redirect('ratemanagement/defaultrate');
            }
        }
    }

    function view($id = '') {
        if ($id == '') {
            redirect('ratemanagement');
        }
        $this->tpl->set_page_title("View Rate information");

        $rate = $this->ratemodel->get_rate_details($id);       // get record
        $operator_rates = $this->ratemodel->get_operator_rate_details($id);

        $this->assign('rate', $rate);
        $this->assign('operator_rates', $operator_rates);

        $head = array('page_title' => 'Operator Details', 'link_title' => 'Operator List', 'link_action' => 'operator');
        $this->load->view('ratemanagement/view', $head);
    }

    function new_rate() {
        $this->tpl->set_page_title("Operator Message Rate");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|xss_clean'
            )/* ,
                  array(
                  'field' => 'user_id[]',
                  'label' => 'user id',
                  'rules' => 'trim|xss_clean'
                  ) */
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        $operator_options = $this->optionmodel->getOperatorOption();
        $this->assign('operator_options', $operator_options);
        $get_all_defind_users = $this->ratemodel->get_all_defind_users();
        $oneDimensionalArray = array_map('current', $get_all_defind_users);
        $exsisting_users = implode(',', $oneDimensionalArray);
        //$user_options = $this->optionmodel->get_user_for_rate($exsisting_users);
        //$this->assign('user_options', $user_options);

        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Operator Message Rate Details', 'link_title' => 'Operator Message Rate List', 'link_action' => 'setting/usersmsprice');
            $this->load->view('ratemanagement/new_rate', $head);
        } else {
            $users = $this->input->post('user_id');
            $data['name'] = $this->input->post('name');
            //$data['users'] = implode(",", $users);
            $data['created_by'] = $this->user_id;
            $rate_id = $this->ratemodel->rateadd($data);

            if ($rate_id) {
                foreach ($operator_options as $key => $opval) {
                    $datas['rate_id'] = $rate_id;
                    $datas['operator_id'] = $this->input->post('operator_id' . $opval['id']);
                    $datas['buying_rate'] = $this->input->post('selling_rate' . $opval['id']); //$this->operatormodel->get_buying_rate_operatorid($opval['id']);
                    $datas['selling_rate'] = 0; //$this->input->post('selling_rate'.$opval['id']);
                    $datas['created_by'] = $this->user_id;
                    $datas['created'] = $this->current_date();
                    $this->operatormodel->addDefaultPrice($datas);
                }
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Operator SMS Rate'));
                redirect('ratemanagement');
            }
        }
    }

    function edit_rate($id) {
        $this->tpl->set_page_title("Operator Message Rate");
        $this->load->library(array('form_validation'));
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'rules' => 'trim|xss_clean'
            )/*,
            array(
                'field' => 'user_id[]',
                'label' => 'user id',
                'rules' => 'trim|xss_clean'
            )*/
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');

        $operator_options = $this->optionmodel->getOperatorOption();
        $this->assign('operator_options', $operator_options);

//        $user_options = $this->optionmodel->get_user_array();
//        $this->assign('user_options', $user_options);

        $rate = $this->ratemodel->get_rate_details($id);


        // get record
        $operator_rates = $this->ratemodel->get_operator_rate_details($id);

        $this->assign('rate', $rate);
        $this->assign('operator_rates', $operator_rates);
		//echo '<pre>';print_r($operator_rates); exit;
// 		$this->assign('rate_id',$this->uri->segment(3)); 
        if ($this->form_validation->run() == FALSE) {
            $head = array('page_title' => 'Operator Message Rate Details', 'link_title' => 'Operator Message Rate List', 'link_action' => 'setting/usersmsprice');
            $this->load->view('ratemanagement/edit_rate', $head);
        } else {
            $users = $this->input->post('user_id');
            $data['name'] = $this->input->post('name');
//            $data['users'] = implode(", ", $users);
            $data['created_by'] = $this->user_id;
            $rate_id = $this->ratemodel->rateupdate($id, $data);

            if ($rate_id) {
                foreach ($operator_options as $key => $opval) {
                    $datas['rate_id'] = $id;
                    //$datas['rate_id'] = $rate_id;
                    $datas['operator_id'] = $this->input->post('operator_id' . $key);
                    //$datas['operator_id'] = $this->input->post('operator_id' . $opval['id']);
                    //echo $datas['buying_rate'] = $this->input->post('selling_rate' . $opval['id']); //$this->operatormodel->get_buying_rate_operatorid($opval['id']);
                    $datas['buying_rate'] = $this->input->post('selling_rate' . $key); //$this->operatormodel->get_buying_rate_operatorid($opval['id']);
                    $datas['selling_rate'] = 0; //$this->input->post('selling_rate'.$opval['id']);
                    $datas['created_by'] = $this->user_id;
                    $datas['created'] = $this->current_date();
                    $this->operatormodel->addDefaultPrice($datas);

                }
                $this->session->set_flashdata('message', $this->tpl->set_message('add', 'Operator SMS Rate'));
                redirect('ratemanagement');
            }
        }
    }

    function del($id) {
        if (!$id) {
            $status = 0;
            $message = $this->tpl->set_message('error', 'Not Delete.');
        } else {

            //$get_rate_user = $this->ratemodel->get_rate_user_by_rate($id);
            //echo '<pre>';print_r($get_rate_user); exit;

            $defaulr_rate = $this->ratemodel->deldefaultrate($id);
            //$smsprice = $this->ratemodel->delsmsprice($get_rate_user);
            $this->ratemodel->del($id);
            $status = 1;
            $message = $this->tpl->set_message('delete', 'Rate');
        }
        $array = array('status' => $status, 'message' => $message);
        echo json_encode($array);
    }

    function addDefaultPrice($rate_id) {
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

    /* function new_rate()
      {
      $this->tpl->set_page_title("Operator Message Rate");
      $this->load->library(array('form_validation'));
      $config = array(
      array(
      'field'   => 'operator_id',
      'label'   => 'operator_id',
      'rules'   => 'trim|xss_clean'
      ),
      array(
      'field'   => 'unit_cost',
      'label'   => 'unit cost',
      'rules'   => 'trim|xss_clean|decimal'
      )
      );

      $this->form_validation->set_rules($config);
      $this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
      $rate_options = $this->optionmodel->get_rate();
      $this->assign('rate_options',$rate_options);

      $user_options = $this->optionmodel->get_user();
      $this->assign('user_options',$user_options);

      $user_id = $this->input->post('user_id');
      $operator_options = $this->operatormodel->getOperatorUnitCost($user_id);
      $this->assign('operator_options',$operator_options);
      if ($this->form_validation->run() == FALSE)
      {
      $this->assign('id',$user_id);
      $head = array('page_title'=>'Operator Message Rate Details','link_title'=>'Operator Message Rate List','link_action'=>'setting/usersmsprice');
      $this->load->view('ratemanagement/new_rate',$head);
      }
      else
      {
      $unitcost=$this->operatormodel->updatesUnitCost($user_id);
      if($unitcost)
      {
      $this->session->set_flashdata('message',$this->tpl->set_message('add','Operator SMS Rate'));
      redirect('ratemanagement/new_rate');
      }
      }
      } */

    function user_rate() {
        $user_id = $this->input->post('user_id');
        $operator_options = $this->operatormodel->getOperatorUnitCost($user_id);
        $this->assign('operator_options', $operator_options);
        $head = array('page_title' => 'Operator Message Rate Details', 'link_title' => 'Operator Message Rate List', 'link_action' => 'setting/usersmsprice');
        if ($this->input->is_ajax_request()) {
            $this->load->view('ratemanagement/ajax_new_rate', $head);
        }
    }

}

?>
