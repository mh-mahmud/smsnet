<?php
 class Mask extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->load->model(array('maskmodel'));
		$this->tpl->set_page_title('Mask Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
		$this->user_id = $this->session->userdata('user_userid');
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$this->load->library('search');
  		$search_data = $this->search->data_search();  		
  		
		$head = array('page_title'=>'Mask List','link_title'=>'New Mask','link_action'=>'mask/add','status_type'=>1);
		$labels=array('senderID'=>'Mask');
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->maskmodel->count_list($search_data);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
  		$masks=$this->maskmodel->get_list($search_data);
  		$this->assign('records',$masks);
  		$this->load->view('mask/mask_list',$head);	
  	}
	
  	function add()
  	{
  		$this->tpl->set_page_title("Add new mask");
		$this->load->library(array('form_validation'));
		$config = array(
               array(
                     'field'   => 'senderID',
                     'label'   => 'Mask',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_mask_check'
                  )
            );

		$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{			
			$head = array('page_title'=>'Mask Details','link_title'=>'Mask List','link_action'=>'mask');
			$this->load->view('mask/new_mask',$head);			
		}
		else
		{
			$data['senderID']=$this->input->post('senderID');
			$data['created_by']=$this->user_id;
			$mask_id=$this->maskmodel->add($data);
			if($mask_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Mask'));
				redirect('mask');
			}			
		}
  	}

	

	function edit($id='')
	{
		$this->tpl->set_page_title("Edit mask information");
		$this->load->library(array('form_validation'));		
		$mask=$this->maskmodel->get_record($id);							// get record
		$this->assign($mask);  
		
		$config = array(
               array(
                     'field'   => 'senderID',
                     'label'   => 'Mask',
                     'rules'   => 'trim|required|xss_clean|callback_duplicate_mask_check['.$mask['senderID'].']'
                  )
                );
			
	  	$this->form_validation->set_rules($config);
	  	$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if ($this->form_validation->run() == FALSE)
		{
			$head = array('page_title'=>'Edit Mask','link_title'=>'Mask List','link_action'=>'mask');
			$this->load->view('mask/edit_mask',$head);
		}
		else
		{
			$data['senderID']=$this->input->post('senderID');
			$data['created_by']=$this->user_id;
			$this->maskmodel->edit($id,$data);   // Update data 
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Mask'));
			redirect('mask');
					
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
			$this->maskmodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Mask');
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
	
	/* validation function for checking maskname duplicacy */

	function duplicate_mask_check($str,$param='')
  	{
      	$count = $this->maskmodel->duplicate_mask_check($str,$param);
        if($count>0)
        {
            $this->form_validation->set_message('duplicate_mask_check', "%s <span style='color:green;'>$str</span> already exists.");
		 	return false;
     	}
       	return true;
  	}

 }
?>
