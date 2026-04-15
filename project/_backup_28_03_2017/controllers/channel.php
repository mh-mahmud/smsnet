<?php
 class Channel extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('channelmodel'));
		$this->tpl->set_page_title('Channel Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
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
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('channel');
		}
		$this->tpl->set_page_title("View Channel information");
		
		$channel=$this->channelmodel->get_channel_details($id);							// get record
		$this->assign($channel); 
		

		$head = array('page_title'=>'Channel Details','link_title'=>'Channel List','link_action'=>'channel');
		$this->load->view('channel/view_channel',$head);
	}
	
  	function add()
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
			$config = array_merge($config0,$config2);
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
			$head = array('page_title'=>'Channel Add','link_title'=>'Channel List','link_action'=>'channel');
			$this->load->view('channel/new_channel',$head);			
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
			$data['created_by']=$this->user_id;
			$data['created']=date('Y-m-d');
			$channel_id=$this->channelmodel->add($data);
			if($channel_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Channel'));
				redirect('country/channelindex');
			}			
		}
  	}

	

	function edit($id='')
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
			$config = array_merge($config0,$config2);
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
		
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Channel','link_title'=>'Channel List','link_action'=>'channel');
			$this->load->view('channel/edit_channel',$head);
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
			}
			if($this->input->post('channel_type')=='SMPP'){
				$data['url']='';
				$data['username']=$this->input->post('username');
				$data['password']=$this->input->post('password');
				$data['ip']='';
				$data['port']='';
			}
			if($this->input->post('channel_type')=='MAP'){
				$data['url']='';
				$data['username']='';
				$data['password']='';
				$data['ip']=$this->input->post('ip');
				$data['port']=$this->input->post('port');
			}
			$data['updated_by']=$this->user_id;
			$data['updated']=date('Y-m-d');
			$this->channelmodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Channel'));
			redirect('country/channelindex');
					
		}
	}

	function set_status($id,$val)
	{
	  	$this->channelmodel->change_status($id,$val);
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
	

 }
?>
