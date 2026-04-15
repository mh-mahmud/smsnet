<?php
 class Country extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('countrymodel','operatormodel','channelmodel','routemodel','optionmodel'));
		$this->tpl->set_page_title('Country Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Country List','link_title'=>'New Country','link_action'=>'country/add','status_type'=>1);
		$labels=array('name'=>'Name','nicename'=>'Nice Name','phonecode'=>'Phone COde');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->countrymodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$countrys=$this->countrymodel->get_list($search_data);
  		$this->assign('records',$countrys);
  		$this->load->view('country/country_list',$head);	
  	}
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('country');
		}
		$this->tpl->set_page_title("View Country information");
		
		$country=$this->countrymodel->get_country_details($id);							// get record
		$this->assign($country); 
		

		$head = array('page_title'=>'Country Details','link_title'=>'Country List','link_action'=>'country');
		$this->load->view('country/view_country',$head);
	}
	
  	function add()
  	{
  		$this->tpl->set_page_title("Add new country");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_country_check'
                  ),
             array(
                     'field'   => 'nicename',
                     'label'   => 'nicename',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'phonecode',
                     'label'   => 'phonecode',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Country Add','link_title'=>'Country List','link_action'=>'country');
			$this->load->view('country/new_country',$head);			
		}
		else
		{
			$data['name']=$this->input->post('name');
			$data['nicename']=$this->input->post('nicename');
			$data['phonecode']=$this->input->post('phonecode');
			$country_id=$this->countrymodel->add($data);
			if($country_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Country'));
				redirect('country');
			}			
		}
  	}

	

	function edit($id='')
	{
		$this->tpl->set_page_title("Edit country information");
		$this->load->library(array('form_validation'));		
		$country=$this->countrymodel->get_record($id);							// get record
		$this->assign($country);  
		$config = array(
                array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_country_check['.$country['name'].']'
                  ),
             array(
                     'field'   => 'nicename',
                     'label'   => 'nicename',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'phonecode',
                     'label'   => 'phonecode',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
		
		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Country','link_title'=>'Country List','link_action'=>'country');
			$this->load->view('country/edit_country',$head);
		}
		else
		{
			$data['name']=$this->input->post('name');
			$data['nicename']=$this->input->post('nicename');
			$data['phonecode']=$this->input->post('phonecode');
			$this->countrymodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Country'));
			redirect('country');
					
		}
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
			$this->countrymodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Country');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking countryname duplicacy */

	function duplicate_country_check($str,$param='')
  	{
      	$count = $this->countrymodel->duplicate_country_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_country_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}
	
	function operatorindex($sort_type='desc',$sort_on='id')
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
		$this->assign('grid_action',array('edit'=>'operatoredit','del'=>'operatordel'));
		$this->set_pagination($config);
  		$operators=$this->operatormodel->get_list($search_data);
  		$this->assign('records',$operators);
  		$this->load->view('operator/operator_list',$head);	
  	}
	
	function channelindex($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$operator_options = $this->channelmodel->getOperatorOption(); 
		$this->assign('operator_options',$operator_options);
		
		$channel_type_options = array('HTTP'=>'HTTP','SMPP'=>'SMPP','MAP'=>'MAP'); 
		$this->assign('channel_type_options',$channel_type_options);
		
		$head = array('page_title'=>'Channel List','link_title'=>'New Channel','link_action'=>'channel/add','status_type'=>1);
		$labels=array('name'=>'Name','operator_name'=>'Operator','channel_type'=>'Type','url'=>'Url','username'=>'Username','password'=>'Password','ip'=>'IP','port'=>'Port','created'=>'Created','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->channelmodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$channels=$this->channelmodel->get_list($search_data);
  		$this->assign('records',$channels);
  		$this->load->view('channel/channel_list',$head);	
  	}
	function routeindex($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		$user_options = $this->optionmodel->get_user();
		$this->assign('user_options',$user_options);
		$operator_options = $this->optionmodel->get_operator();
		$this->assign('operator_options',$operator_options);
		$channel_options = $this->optionmodel->get_channel();
		$this->assign('channel_options',$channel_options);
		
		$head = array('page_title'=>'Route List','link_title'=>'New Route','link_action'=>'route/add','status_type'=>1);
		$labels=array('user'=>'User Name','operator'=>'Operator','has_mask'=>'Masking Option','channel'=>'Channel','cost'=>'Cost','default_mask'=>'Default Mask','success_rate'=>'Success Rate','created'=>'Created','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->routemodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$routes=$this->routemodel->get_list($search_data);
  		$this->assign('records',$routes);
  		$this->load->view('route/route_list',$head);	
  	}
 }
?>
