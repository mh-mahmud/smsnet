<?php
 class Smstemplate extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('smstemplatemodel'));
		$this->tpl->set_page_title('Template Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Template List','link_title'=>'New Template','link_action'=>'smstemplate/add','status_type'=>1);
		$labels=array('title'=>'Title','description'=>'Description','created_by'=>'Created By','status'=>'Status');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->smstemplatemodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('view'=>'view','edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$smstemplates=$this->smstemplatemodel->get_list($search_data);
  		$this->assign('records',$smstemplates);
  		$this->load->view('smstemplate/smstemplate_list',$head);	
  	}
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('smstemplate');
		}
		$this->tpl->set_page_title("View Template information");
		
		$smstemplate=$this->smstemplatemodel->get_smstemplate_details($id);							// get record
		$this->assign($smstemplate); 
		

		$head = array('page_title'=>'Template Details','link_title'=>'Template List','link_action'=>'smstemplate');
		$this->load->view('smstemplate/view_smstemplate',$head);
	}
	
  	function add()
  	{
  		$this->tpl->set_page_title("Add new smstemplate");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_smstemplate_check'
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
			$head = array('page_title'=>'Template Add','link_title'=>'Template List','link_action'=>'smstemplate');
			$this->load->view('smstemplate/new_smstemplate',$head);			
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');
			$data['created_by']=$this->user_id;
			$data['created_date']= date('Y-m-d');
			$smstemplate_id=$this->smstemplatemodel->add($data);
			if($smstemplate_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Template'));
				redirect('smstemplate');
			}			
		}
  	}

	

	function edit($id='')
	{
		$this->tpl->set_page_title("Edit smstemplate information");
		$this->load->library(array('form_validation'));		
		$smstemplate=$this->smstemplatemodel->get_record($id);							// get record
		$this->assign($smstemplate);  
		$config = array(
               array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_smstemplate_check['.$smstemplate['title'].']'
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
			$head = array('page_title'=>'Edit Template','link_title'=>'Template List','link_action'=>'smstemplate');
			$this->load->view('smstemplate/edit_smstemplate',$head);
		}
		else
		{
			$data['title']=$this->input->post('title');
			$data['description']=$this->input->post('description');
			$data['created_by']=$this->user_id;
			$data['created_date']= date('Y-m-d');
			$this->smstemplatemodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Template'));
			redirect('smstemplate');
					
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
			$this->smstemplatemodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Template');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking smstemplatename duplicacy */

	function duplicate_smstemplate_check($str,$param='')
  	{
      	$count = $this->smstemplatemodel->duplicate_smstemplate_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_smstemplate_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}	
 }
?>
