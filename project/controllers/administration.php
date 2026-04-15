<?php
 class Administration extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('countrymodel','operatormodel','channelmodel','routemodel','optionmodel','usermodel'));
		$this->tpl->set_page_title('Country Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Country List','link_title'=>'New Country','link_action'=>'administration/add','status_type'=>1);
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
  		$this->load->view('administration/country/country_list',$head);	
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
		

		$head = array('page_title'=>'Country Details','link_title'=>'Country List','link_action'=>'administration');
		$this->load->view('administration/country/view_country',$head);
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
			$head = array('page_title'=>'Country Add','link_title'=>'Country List','link_action'=>'administration');
			$this->load->view('administration/country/new_country',$head);			
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
				redirect('administration');
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
			$head = array('page_title'=>'Edit Country','link_title'=>'Country List','link_action'=>'administration');
			$this->load->view('administration/country/edit_country',$head);
		}
		else
		{
			$data['name']=$this->input->post('name');
			$data['nicename']=$this->input->post('nicename');
			$data['phonecode']=$this->input->post('phonecode');
			$this->countrymodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Country'));
			redirect('administration');
					
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
	
	
	/*************************** Operator ****************************/
	
	function operatorindex($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Operator List','link_title'=>'New Operator','link_action'=>'administration/operatoradd','status_type'=>1);
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
  		$this->load->view('administration/operator/operator_list',$head);	
  	}
	
	function operatoradd()
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
			$head = array('page_title'=>'Operator Details','link_title'=>'Operator List','link_action'=>'administration');
			$this->load->view('administration/operator/new_operator',$head);			
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
				$this->addDefaultPrice($operator_id);
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Operator'));
				redirect('administration/operatorindex');
			}			
		}
  	}

	function addDefaultPrice($operator_id)
	{
		$data['rate_id'] = 0;
		$data['operator_id'] = $operator_id;
		$data['buying_rate'] = 0;
		$data['selling_rate'] = 0;
		$data['created_by'] = 1;
		$data['created'] = $this->current_date();
		$this->operatormodel->addAdminDefaultPrice($data);		
	}
	
	function operatoredit($id='')
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
			$head = array('page_title'=>'Edit Operator','link_title'=>'Operator List','link_action'=>'administration');
			$this->load->view('administration/operator/edit_operator',$head);
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
			redirect('administration/operatorindex');
					
		}
	}

	function operator_set_status($id,$val)
	{
	  	$this->operatormodel->change_status($id,$val);
	  	echo $this->status_change($id,$val);
	}

	function operatordel($id)
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

	/*************************** Channel ****************************/
	
	function channelindex($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$operator_options = $this->channelmodel->getOperatorOption(); 
		$this->assign('operator_options',$operator_options);
		
		$channel_type_options = array('HTTP'=>'HTTP','SMPP'=>'SMPP','MAP'=>'MAP'); 
		$this->assign('channel_type_options',$channel_type_options);
		
		$head = array('page_title'=>'Channel List','link_title'=>'New Channel','link_action'=>'administration/channeladd','status_type'=>1);
		$labels=array('name'=>'Name','operator_name'=>'Operator','channel_type'=>'Type','created'=>'Created','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->channelmodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('view'=>'channelview','edit'=>'channeledit','del'=>'channeldel'));
		$this->set_pagination($config);
  		$channels=$this->channelmodel->get_list($search_data);
  		$this->assign('records',$channels);
  		$this->load->view('administration/channel/channel_list',$head);	
  	}
	
	function channelview($id='')
	{
		if($id=='')
		{
			redirect('administration/channelindex');
		}
		$this->tpl->set_page_title("View Channel information");
		
		$channel=$this->channelmodel->get_channel_details($id);							// get record
		$this->assign($channel); 
		

		$head = array('page_title'=>'Channel Details','link_title'=>'Channel List','link_action'=>'administration/channelindex');
		$this->load->view('administration/channel/view_channel',$head);
	}
	
	function channeladd()
  	{
  		$this->tpl->set_page_title("Add new channel");
		$this->load->library(array('form_validation'));
		$config0 = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_channel_check'
                  ),
             array(
                     'field'   => 'operator_id',
                     'label'   => 'operator',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'channel_type',
                     'label'   => 'channel_type',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
			$config1 = array(
               array(
                     'field'   => 'url',
                     'label'   => 'url',
                     'rules'   => 'trim|required'
                  )
            );
			
			$config2 = array(
               array(
                     'field'   => 'username',
                     'label'   => 'username',
                     'rules'   => 'trim|required|xss_clean'
                  ),
             array(
                     'field'   => 'password',
                     'label'   => 'password',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
			$config3 = array(
               array(
                     'field'   => 'ip',
                     'label'   => 'IP',
                     'rules'   => 'trim|required|xss_clean'
                  ),
             array(
                     'field'   => 'port',
                     'label'   => 'Port',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			$config4 = array(
               array(
                     'field'   => 'mode',
                     'label'   => 'Mode',
                     'rules'   => 'trim|xss_clean'
                  )
            );
			
		if($this->input->post('channel_type')=='')
		{
			$config = array_merge($config0);
		}
		if($this->input->post('channel_type')=='HTTP')
		{
			$config = array_merge($config0,$config1,$config2);
		}
		if($this->input->post('channel_type')=='SMPP')
		{
			$config = array_merge($config0,$config2,$config3,$config4);
		}
		if($this->input->post('channel_type')=='MAP')
		{
			$config = array_merge($config0,$config3);
		}
		
		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		$operator_options = $this->channelmodel->getOperatorOption(); 
		$this->assign('operator_options',$operator_options);
		
		$channel_type_options = array('HTTP'=>'HTTP','SMPP'=>'SMPP','MAP'=>'MAP'); 
		$this->assign('channel_type_options',$channel_type_options);
		
		$this->assign('channel_type',$this->input->post('channel_type'));
		
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Channel Add','link_title'=>'Channel List','link_action'=>'administration/channelindex');
			$this->load->view('administration/channel/new_channel',$head);			
		}
		else
		{
			$data['name']=$this->input->post('name');
			$data['operator_id']=$this->input->post('operator_id');
			$data['channel_type']=$this->input->post('channel_type');
			$data['url']=$this->input->post('url');
			$data['username']=$this->input->post('username');
			$data['password']=$this->input->post('password');
			$data['ip']=$this->input->post('ip');
			$data['port']=$this->input->post('port');
			$data['mode']=$this->input->post('mode');
			$data['created_by']=$this->user_id;
			$data['created']=date('Y-m-d');
			$channel_id=$this->channelmodel->add($data);
			if($channel_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Channel'));
				redirect('administration/channelindex');
			}			
		}
  	}

	function channeledit($id='')
	{
		$this->tpl->set_page_title("Edit channel information");
		$this->load->library(array('form_validation'));		
		$channel=$this->channelmodel->get_record($id);							// get record
		$this->assign($channel);  
		$config0 = array(
               array(
                     'field'   => 'name',
                     'label'   => 'Name',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_channel_check['.$channel['name'].']'
                  ),
             array(
                     'field'   => 'operator_id',
                     'label'   => 'operator',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'channel_type',
                     'label'   => 'channel_type',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
			$config1 = array(
               array(
                     'field'   => 'url',
                     'label'   => 'url',
                     'rules'   => 'trim|required'
                  )
            );
			
			$config2 = array(
               array(
                     'field'   => 'username',
                     'label'   => 'username',
                     'rules'   => 'trim|required|xss_clean'
                  ),
             array(
                     'field'   => 'password',
                     'label'   => 'password',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
			$config3 = array(
               array(
                     'field'   => 'ip',
                     'label'   => 'IP',
                     'rules'   => 'trim|required|xss_clean'
                  ),
             array(
                     'field'   => 'port',
                     'label'   => 'Port',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			$config4 = array(
               array(
                     'field'   => 'mode',
                     'label'   => 'Mode',
                     'rules'   => 'trim|xss_clean'
                  )
            );
			
		
		if($this->input->post('channel_type')=='')
		{
			$config = array_merge($config0);
		}
		if($this->input->post('channel_type')=='HTTP')
		{
			$config = array_merge($config0,$config1,$config2);
		}
		if($this->input->post('channel_type')=='SMPP')
		{
			$config = array_merge($config0,$config2,$config3,$config4);
		}
		if($this->input->post('channel_type')=='MAP')
		{
			$config = array_merge($config0,$config3);
		}
		
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		$operator_options = $this->channelmodel->getOperatorOption(); 
		$this->assign('operator_options',$operator_options);
		
		$channel_type_options = array('HTTP'=>'HTTP','SMPP'=>'SMPP','MAP'=>'MAP'); 
		$this->assign('channel_type_options',$channel_type_options);
		
		$this->assign('channel_edit_type',$this->input->post('channel_type'));
		
		
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Channel','link_title'=>'Channel List','link_action'=>'administration/channelindex');
			$this->load->view('administration/channel/edit_channel',$head);
		}
		else
		{
			$data['name']=$this->input->post('name');
			$data['operator_id']=$this->input->post('operator_id');
			$data['channel_type']=$this->input->post('channel_type');
			if($this->input->post('channel_type')=='HTTP'){
				$data['url']=$this->input->post('url');
				$data['username']=$this->input->post('username');
				$data['password']=$this->input->post('password');
				$data['ip']='';
				$data['port']='';
				$data['mode']='';
			}
			if($this->input->post('channel_type')=='SMPP'){
				$data['url']='';
				$data['username']=$this->input->post('username');
				$data['password']=$this->input->post('password');
				$data['ip']=$this->input->post('ip');
				$data['port']=$this->input->post('port');
				$data['mode']=$this->input->post('mode');
			}
			if($this->input->post('channel_type')=='MAP'){
				$data['url']='';
				$data['username']='';
				$data['password']='';
				$data['ip']=$this->input->post('ip');
				$data['port']=$this->input->post('port');
				$data['mode']='';
			}
			$data['updated_by']=$this->user_id;
			$data['updated']=date('Y-m-d');
			$this->channelmodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Channel'));
			redirect('administration/channelindex');
					
		}
	}

	function channel_set_status($id,$val)
	{
	  	$this->channelmodel->change_status($id,$val);
	  	echo $this->status_change($id,$val);
	}

	function channeldel($id)
	{
		if(!$id)
		{
			$status = 0;
			$message = $this->tpl->set_message('error','Not Delete.');
		}
		else
		{
			$this->channelmodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Channel');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking channelname duplicacy */

	function duplicate_channel_check($str,$param='')
  	{
      	$count = $this->channelmodel->duplicate_channel_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_channel_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}
	/*************************** Route ****************************/
	
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
		
		$head = array('page_title'=>'Route List','link_title'=>'New Route','link_action'=>'administration/routeadd','status_type'=>1);
		$labels=array('user'=>'User Name','operator'=>'Operator','has_mask'=>'Masking Option','channel'=>'Channel','cost'=>'Cost','default_mask'=>'Default Mask','success_rate'=>'Success Rate','created'=>'Created','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->routemodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'routeedit','del'=>'routedel'));
		$this->set_pagination($config);
  		$routes=$this->routemodel->get_list($search_data);
  		$this->assign('records',$routes);
  		$this->load->view('administration/route/route_list',$head);	
  	}
	function routeadd()
  	{
  		$this->tpl->set_page_title("Add new route");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'user_id',
                     'label'   => 'User Name',
                     'rules'   => 'trim|xss_clean|callback_user_operator_check'
                  ),
             array(
                     'field'   => 'operator_id',
                     'label'   => 'Operator Name',
                     'rules'   => 'trim|required|xss_clean|callback_user_operator_check'
                  ),
              array(
                     'field'   => 'channel_id',
                     'label'   => 'Channel Name',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'cost',
                     'label'   => 'Cost',
                     'rules'   => 'trim|xss_clean'
                  ),
             array(
                     'field'   => 'default_mask',
                     'label'   => 'default mask',
                     'rules'   => 'trim|xss_clean'
                  ),
              array(
                     'field'   => 'has_mask',
                     'label'   => 'Has mask',
                     'rules'   => 'trim|required|xss_clean'
                  ),
               array(
                     'field'   => 'success_rate',
                     'label'   => 'Success Rate',
                     'rules'   => 'trim|xss_clean'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	$user_options = $this->optionmodel->get_user();
		$this->assign('user_options',$user_options);
		$operator_options = $this->optionmodel->get_operator();
		$this->assign('operator_options',$operator_options);
		$channel_options = $this->optionmodel->get_channel();
		$this->assign('channel_options',$channel_options);
		$mask_options = array('1'=>'YES','0'=>'NO','2'=>'ALL');
		$this->assign('mask_options',$mask_options);
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Route Details','link_title'=>'Route List','link_action'=>'administration/routeindex');
			$this->load->view('administration/route/new_route',$head);			
		}
		else
		{
			$data['user_id']=$this->input->post('user_id');
			$data['operator_id']=$this->input->post('operator_id');
			$data['channel_id']=$this->input->post('channel_id');
			$data['cost']=$this->input->post('cost');
			$data['default_mask']=$this->input->post('default_mask');
			$data['has_mask']=$this->input->post('has_mask');
			$data['success_rate']=$this->input->post('success_rate');
			$data['created_by']=$this->user_id;
			$data['created']=date('Y-m-d');
			$route_id=$this->routemodel->add($data);
			if($route_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Route'));
				redirect('administration/routeindex');
			}			
		}
  	}

	

	function routeedit($id='')
	{
		$this->tpl->set_page_title("Edit route information");
		$this->load->library(array('form_validation'));		
		$route=$this->routemodel->get_record($id);							// get record
		$this->assign($route);  
		
		$config = array(
               array(
                     'field'   => 'user_id',
                     'label'   => 'User Name',
                     'rules'   => 'trim|xss_clean|callback_user_operator_check'
                  ),
             array(
                     'field'   => 'operator_id',
                     'label'   => 'Operator Name',
                     'rules'   => 'trim|xss_clean|callback_user_operator_check'
                  ),
              array(
                     'field'   => 'channel_id',
                     'label'   => 'Channel Name',
                     'rules'   => 'trim|required|xss_clean'
                  ),
              array(
                     'field'   => 'cost',
                     'label'   => 'Cost',
                     'rules'   => 'trim|xss_clean'
                  ),
              array(
                     'field'   => 'default_mask',
                     'label'   => 'default mask',
                     'rules'   => 'trim|xss_clean'
                  ), 
			array(
                     'field'   => 'has_mask',
                     'label'   => 'Has mask',
                     'rules'   => 'trim|required|xss_clean'
                  ),
               
              array(
                     'field'   => 'success_rate',
                     'label'   => 'Success Rate',
                     'rules'   => 'trim|xss_clean'
                  )
              
                 );
			
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	$user_options = $this->optionmodel->get_user();
		$this->assign('user_options',$user_options);
		$operator_options = $this->optionmodel->get_operator();
		$this->assign('operator_options',$operator_options);
		$channel_options = $this->optionmodel->get_channel();
		$this->assign('channel_options',$channel_options);
		$mask_options = array('1'=>'YES','0'=>'NO','2'=>'ALL');
		$this->assign('mask_options',$mask_options);
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Route','link_title'=>'Route List','link_action'=>'administration/routeindex');
			$this->load->view('administration/route/edit_route',$head);
		}
		else
		{
			$data['user_id']=$this->input->post('user_id');
			$data['operator_id']=$this->input->post('operator_id');
			$data['channel_id']=$this->input->post('channel_id');
			$data['cost']=$this->input->post('cost');
			$data['default_mask']=$this->input->post('default_mask');
			$data['has_mask']=$this->input->post('has_mask');
			$data['success_rate']=$this->input->post('success_rate');
			$this->routemodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Route'));
			redirect('administration/routeindex');
					
		}
	}

	function route_set_status($id,$val)
	{
	  	$this->routemodel->change_status($id,$val);
	  	echo $this->status_change($id,$val);
	}

	function routedel($id)
	{
		if(!$id)
		{
			$status = 0;
			$message = $this->tpl->set_message('error','Not Delete.');
		}
		else
		{
			$this->routemodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Route');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking routename  */

	function user_operator_check($str,$param='')
  	{
      	if($this->input->post('operator_id')=='' AND $this->input->post('user_id')=='')
        {
            $this->form_validation->set_message('operator_check', "select atlest operator or user.");
		 	return false;
     	}
       	return true;
  	}
 }
?>
