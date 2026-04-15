<?php
 class Addressbook extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('addressbookmodel'));
		$this->tpl->set_page_title('Address Book Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Address Book List','link_title'=>'New Address Book','link_action'=>'addressbook/addressBookAdd','status_type'=>1);
		$labels=array('title'=>'Title','description'=>'Description');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->addressbookmodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('view'=>'view','edit'=>'edit','del'=>'del','sms'=>'sms','share'=>'share','manage'=>'manage'));
		$this->set_pagination($config);
  		$addressbooks=$this->addressbookmodel->get_list($search_data);
  		$this->assign('records',$addressbooks);
  		$this->load->view('addressbook/addressbook_list',$head);	
  	}
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('addressbook');
		}
		$this->tpl->set_page_title("View Address Book information");
		
		$addressbook=$this->addressbookmodel->get_addressbook_details($id);							// get record
		$this->assign($addressbook); 
		

		$head = array('page_title'=>'Address Book Details','link_title'=>'Address Book List','link_action'=>'addressbook');
		$this->load->view('addressbook/view_addressbook',$head);
	}
	
  	function addressBookAdd()
  	{
  		$this->tpl->set_page_title("Add new addressbook");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_user_check'
                  ),
               array(
                     'field'   => 'description',
                     'label'   => 'Description',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Address Book Details','link_title'=>'Address Book List','link_action'=>'addressbook');
			$this->load->view('addressbook/new_addressbook',$head);			
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');
			$data['user_id']=$this->user_id;
			$addressbook_id=$this->addressbookmodel->add($data);
			if($addressbook_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Address Book'));
				redirect('addressbook');
			}			
		}
  	}

	

	function edit($id='',$profile='')
	{
		$this->tpl->set_page_title("Edit addressbook information");
		$this->load->library(array('form_validation'));		
		$addressbook=$this->addressbookmodel->get_record($id);							// get record
		$this->assign($addressbook);  
		
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_user_check['.$user['username'].']'
                  ),
               array(
                     'field'   => 'description',
                     'label'   => 'Description',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Address Book','link_title'=>'Address Book List','link_action'=>'addressbook');
			$this->load->view('addressbook/edit_addressbook',$head);
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');					
			$this->addressbookmodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Address Book'));
			redirect('addressbook');
					
		}
	}

	function set_status($id,$val)
	{
	  	$this->addressbookmodel->change_status($id,$val);
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
			$this->addressbookmodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Address Book');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking addressbookname duplicacy */

	function duplicate_addressbook_check($str,$param='')
  	{
      	$count = $this->addressbookmodel->duplicate_addressbook_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_addressbook_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}
	
	
	/************************   Address book contact ********************************/
	
	function manage($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Address Book List','link_title'=>'New Address Book','link_action'=>'addressbook/addressBookAdd','status_type'=>1);
		$labels=array('title'=>'Title','description'=>'Description');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->addressbookmodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('view'=>'view','edit'=>'edit','del'=>'del','sms'=>'sms','share'=>'share','manage'=>'manage'));
		$this->set_pagination($config);
  		$addressbooks=$this->addressbookmodel->get_list($search_data);
  		$this->assign('records',$addressbooks);
  		$this->load->view('addressbook/addressbook_list',$head);	
  	}
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('addressbook');
		}
		$this->tpl->set_page_title("View Address Book information");
		
		$addressbook=$this->addressbookmodel->get_addressbook_details($id);							// get record
		$this->assign($addressbook); 
		

		$head = array('page_title'=>'Address Book Details','link_title'=>'Address Book List','link_action'=>'addressbook');
		$this->load->view('addressbook/view_addressbook',$head);
	}
	
  	function addressBookAdd()
  	{
  		$this->tpl->set_page_title("Add new addressbook");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_user_check'
                  ),
               array(
                     'field'   => 'description',
                     'label'   => 'Description',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Address Book Details','link_title'=>'Address Book List','link_action'=>'addressbook');
			$this->load->view('addressbook/new_addressbook',$head);			
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');
			$data['user_id']=$this->user_id;
			$addressbook_id=$this->addressbookmodel->add($data);
			if($addressbook_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Address Book'));
				redirect('addressbook');
			}			
		}
  	}

	

	function edit($id='',$profile='')
	{
		$this->tpl->set_page_title("Edit addressbook information");
		$this->load->library(array('form_validation'));		
		$addressbook=$this->addressbookmodel->get_record($id);							// get record
		$this->assign($addressbook);  
		
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_user_check['.$user['username'].']'
                  ),
               array(
                     'field'   => 'description',
                     'label'   => 'Description',
                     'rules'   => 'trim|required|xss_clean'
                  )
            );
			
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Address Book','link_title'=>'Address Book List','link_action'=>'addressbook');
			$this->load->view('addressbook/edit_addressbook',$head);
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');					
			$this->addressbookmodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Address Book'));
			redirect('addressbook');
					
		}
	}

	function set_status($id,$val)
	{
	  	$this->addressbookmodel->change_status($id,$val);
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
			$this->addressbookmodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Address Book');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking addressbookname duplicacy */

	function duplicate_addressbook_check($str,$param='')
  	{
      	$count = $this->addressbookmodel->duplicate_addressbook_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_addressbook_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}
	
	
 }
?>
