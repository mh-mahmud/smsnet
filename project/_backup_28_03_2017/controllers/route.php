<?php
 class Route extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('routemodel','optionmodel'));
		$this->tpl->set_page_title('Route Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
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
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('route');
		}
		$this->tpl->set_page_title("View Route information");
		
		$route=$this->routemodel->get_route_details($id);							// get record
		$this->assign($route); 
		

		$head = array('page_title'=>'Route Details','link_title'=>'Route List','link_action'=>'route');
		$this->load->view('route/view_route',$head);
	}
	
  	function add()
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
                     'rules'   => 'trim|required|xss_clean'
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
			$head = array('page_title'=>'Route Details','link_title'=>'Route List','link_action'=>'route');
			$this->load->view('route/new_route',$head);			
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
				redirect('country/routeindex');
			}			
		}
  	}

	

	function edit($id='')
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
                     'rules'   => 'trim|required|xss_clean'
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
			$head = array('page_title'=>'Edit Route','link_title'=>'Route List','link_action'=>'route');
			$this->load->view('route/edit_route',$head);
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
			redirect('country/routeindex');
					
		}
	}

	function set_status($id,$val)
	{
	  	$this->routemodel->change_status($id,$val);
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
