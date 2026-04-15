<?php
 class Operator extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('operatormodel','channelmodel','optionmodel'));
		$this->tpl->set_page_title('Operator Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Operator List','link_title'=>'New Operator','link_action'=>'operator/add','status_type'=>1);
		$labels=array('full_name'=>'Full Name','short_name'=>'Short Name','ton'=>'TON','npi'=>'NPI','created'=>'Created','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->operatormodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$operators=$this->operatormodel->get_list($search_data);
  		$this->assign('records',$operators);
  		$this->load->view('operator/operator_list',$head);	
  	}
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('operator');
		}
		$this->tpl->set_page_title("View Operator information");
		
		$operator=$this->operatormodel->get_operator_details($id);							// get record
		$this->assign($operator); 
		

		$head = array('page_title'=>'Operator Details','link_title'=>'Operator List','link_action'=>'operator');
		$this->load->view('operator/view_operator',$head);
	}
	
  	function add()
  	{
  		$this->tpl->set_page_title("Add new operator");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'full_name',
                     'label'   => 'Full Name',
                     'rules'   => 'trim|required|xss_clean'
                  ),
             array(
                     'field'   => 'country_id',
                     'label'   => 'country_id',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'prefix',
                     'label'   => 'Prefix',
                     'rules'   => 'trim|required|numerique|xss_clean'
                  ),
              array(
                     'field'   => 'ton',
                     'label'   => 'ton',
                     'rules'   => 'trim|xss_clean'
                  ),
              array(
                     'field'   => 'npi',
                     'label'   => 'npi',
                     'rules'   => 'trim|xss_clean'
                  ),
              array(
                     'field'   => 'short_name',
                     'label'   => 'Short Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_operator_check'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	$country_options = $this->optionmodel->get_country();
		$this->assign('country_options',$country_options);
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Operator Details','link_title'=>'Operator List','link_action'=>'operator');
			$this->load->view('operator/new_operator',$head);			
		}
		else
		{
			$data['full_name']=$this->input->post('full_name');
			$data['short_name']=$this->input->post('short_name');
			$data['country_id']=$this->input->post('country_id');
			$data['prefix']=$this->input->post('prefix');
			$data['ton']=$this->input->post('ton');
			$data['npi']=$this->input->post('npi');
			$data['created_by']=$this->user_id;
			$data['created']=date('Y-m-d');
			$operator_id=$this->operatormodel->add($data);
			if($operator_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Operator'));
				redirect('country/operatorindex');
			}			
		}
  	}

	

	function edit($id='')
	{
		$this->tpl->set_page_title("Edit operator information");
		$this->load->library(array('form_validation'));		
		$operator=$this->operatormodel->get_record($id);							// get record
		$this->assign($operator);  
		
		$config = array(
               array(
                     'field'   => 'full_name',
                     'label'   => 'Full Name',
                     'rules'   => 'trim|required|xss_clean'
                  ),
				array(
                     'field'   => 'short_name',
                     'label'   => 'Short Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_operator_check['.$operator['short_name'].']'
                  ),
				array(
                     'field'   => 'country_id',
                     'label'   => 'country_id',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              
				array(
                     'field'   => 'prefix',
                     'label'   => 'Prefix',
                     'rules'   => 'trim|required|numeriq|xss_clean'
                  ),array(
                     'field'   => 'ton',
                     'label'   => 'ton',
                     'rules'   => 'trim|xss_clean'
                  ),
              array(
                     'field'   => 'npi',
                     'label'   => 'npi',
                     'rules'   => 'trim|xss_clean'
                  ),
              
                 );
			
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	$country_options = $this->optionmodel->get_country();
		$this->assign('country_options',$country_options);
		
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Operator','link_title'=>'Operator List','link_action'=>'operator');
			$this->load->view('operator/edit_operator',$head);
		}
		else
		{
			$data['full_name']=$this->input->post('full_name');
			$data['short_name']=$this->input->post('short_name');
			$data['country_id']=$this->input->post('country_id');
			$data['prefix']=$this->input->post('prefix');
			$data['ton']=$this->input->post('ton');
			$data['npi']=$this->input->post('npi');
			$data['updated_by']=$this->user_id;
			$data['updated']=date('Y-m-d');
			$this->operatormodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Operator'));
			redirect('country/operatorindex');
					
		}
	}

	function set_status($id,$val)
	{
	  	$this->operatormodel->change_status($id,$val);
	  	echo $this->status_change($id,$val);
	}

	function del($id)
	{
		if(!$id)
		{
			$status = 0;
			$message = $this->tpl->set_message('error','Not Delete.');
		}
		else
		{
			$this->operatormodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Operator');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking operatorname duplicacy */

	function duplicate_operator_check($str,$param='')
  	{
      	$count = $this->operatormodel->duplicate_operator_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_operator_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}

	
	function smsrate()
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
                     'field'   => 'per_sms_rate',
                     'label'   => 'per_sms_rate',
                     'rules'   => 'trim|xss_clean|decimal'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	$operator_options = $this->operatormodel->getOperatorSmsRate(); 
		$this->assign('operator_options',$operator_options);
		
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Operator Message Rate Details','link_title'=>'Operator Message Rate List','link_action'=>'operator/smsrate');
			$this->load->view('operator/smsrate',$head);			
		}
		else
		{
			$operator_id=$this->operatormodel->updatesmsrate();
			if($operator_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('edit','Operator SMS Rate'));
				redirect('operator/smsrate');
			}			
		}
  	}
 }
?>
