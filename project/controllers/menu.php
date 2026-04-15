<?php
 class Menu extends Bindu_Controller
 {
  	function __construct()
 	{
 	 	parent::__construct();
 	 	$this->load->model(array('menumodel','usergroupmodel'));
 	 	$this->tpl->set_page_title('Menu manager');
 	}
	 
	function index($sort_type='desc',$sort_on='id')
	{			
		$data['page_title'] = 'Menu List';
		$data['link_title'] = 'New Menu';
		$data['link_action'] = 'menu/add';
		$data['status_type'] = 2;
		$labels=array('menu_title'=>'Title','module_link'=>'Module Link','order'=>'Order','status'=>'Status',);
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->menumodel->count_list();
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']=$sort_on;
		$config['sort_type']=$sort_type;
		$this->assign('grid_action',array('view'=>'view','edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
		$menu=$this->menumodel->get_list();
		$this->assign('records',$menu);
		if($this->input->is_ajax_request())
		{
			$this->load->view('elements/grid_board');
		}else{
			$this->load->view('menu/menu_list',$data);
		}
   }


	 /**
	  * @param string $parent_id
      */
	 function add($parent_id='')
	{
  		$this->load->library(array('form_validation'));
		$config = array(
				array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required'
                    ),				
				array(
                     'field'   => 'module_link',
                     'label'   => 'module_link',
                     'rules'   => 'trim|required'
                    ),		
				array(
                     'field'   => 'order',
                     'label'   => 'Order',
                     'rules'   => 'trim|required|numeric'
                    ),		
				array(
                     'field'   => 'status',
                     'label'   => 'Status',
                     'rules'   => 'trim|required'
                    ),
				array(
                     'field'   => 'icon',
                     'label'   => 'Icon',
                     'rules'   => 'trim'
                    ),
				array(
                     'field'   => 'icon_color',
                     'label'   => 'Icon Color',
                     'rules'   => 'trim'
                    ),		
				array(
					 'field'   => 'user_group_id[]',
					 'label'   => 'Admin Group Permission',
					 'rules'   => 'trim|required|xss_clean'
					)	
			);
		
		$this->form_validation->set_rules($config);
	  	
		$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
	  	
		if($this->form_validation->run() == TRUE)
		{
			$data['title']=$this->input->post('title');
			if($parent_id){
				$data['parent_id']=$parent_id;
			}
			$data['user_group_id']=implode(',',$this->input->post('user_group_id'));			
			$data['module_link']=$this->input->post('module_link');
			$data['order']=$this->input->post('order');
			$data['status']=$this->input->post('status');
			$data['icon']=$this->input->post('icon');
			$data['icon_color']=$this->input->post('icon_color');
			$menu_id=$this->menumodel->add($data);
			if($menu_id)
			{
				$this->session->set_flashdata('message',$this->tpl->set_message('add','Menu'));
				if($parent_id)
				{
					redirect('menu/view/'.$parent_id);
				}else{
					redirect('menu');
				}
			}	
		}
		else
		{
			$user_group_options=$this->usergroupmodel->group_option(); 		// get user group list
			$this->assign('user_group_options',$user_group_options);
			$status_option=array('PUBLISH'=>'Publish','UNPUBLISH'=>'Unpublish');
			$this->assign('status_option',$status_option);
			$this->assign('parent_id',$parent_id);
			$this->load->view('menu/new_menu');
		}				
	} 
  
	function edit($id='',$parent_id='')
	{
		$row=$this->menumodel->get_record($id);
		$this->assign($row);
		
		$this->load->library(array('form_validation'));
		$config = array(
				array(
                     'field'   => 'title',
                     'label'   => 'Title',
                     'rules'   => 'trim|required'
                    ),				
				array(
                     'field'   => 'module_link',
                     'label'   => 'module_link',
                     'rules'   => 'trim|required'
                    ),		
				array(
                     'field'   => 'order',
                     'label'   => 'Order',
                     'rules'   => 'trim|required|numeric'
                    ),		
				array(
                     'field'   => 'status',
                     'label'   => 'Status',
                     'rules'   => 'trim|required'
                    ),
				array(
                     'field'   => 'icon',
                     'label'   => 'Icon',
                     'rules'   => 'trim'
                    ),
				array(
                     'field'   => 'icon_color',
                     'label'   => 'Icon Color',
                     'rules'   => 'trim'
                    ),					
				array(
					 'field'   => 'user_group_id[]',
					 'label'   => 'Admin Group Permission',
					 'rules'   => 'trim|required|xss_clean'
					)					
			);		
				
		$this->form_validation->set_rules($config);
		$this->form_validation->set_error_delimiters('<span class="verr"><i class="fa fa-exclamation-circle"></i> ', '</span>');
		
		if($this->form_validation->run() == TRUE)
		{
			$new_data['title']=$this->input->post('title');
			$new_data['user_group_id']=implode(',',$this->input->post('user_group_id'));
			$new_data['order']=$this->input->post('order');
			$new_data['status']=$this->input->post('status');
			$new_data['module_link']=$this->input->post('module_link');
			$new_data['icon']=$this->input->post('icon');
			$new_data['icon_color']=$this->input->post('icon_color');
			$this->menumodel->update_menu($id,$new_data);
			$this->session->set_flashdata('message',$this->tpl->set_message('edit','Menu'));
			if($parent_id)
			{
				redirect('menu/view/'.$parent_id);
			}else{
				redirect('menu');	
			}
		}
		else
		{
			$user_group_options=$this->usergroupmodel->group_option(); 		// get user group list
			$this->assign('user_group_options',$user_group_options);
			$user_group=explode(',',$row['user_group_id']);   // explode task array
			$this->assign('user_group',$user_group);
			$status_option=array('PUBLISH'=>'Publish','UNPUBLISH'=>'Unpublish');
			$this->assign('status_option',$status_option);
			$this->assign('parent_id',$parent_id);
			$this->load->view('menu/edit_menu');
		}		
		
	} 
  
	function del($id='',$parent_id='')
	{
		$child=$this->menumodel->count_child($id);
		if($child>0)
		{
			$status = 0;
			$message = $this->tpl->set_message('error','Please delete child first.');
		}
		else
		{
			$this->menumodel->del($id);
			$status = 1;
			$message = $this->tpl->set_message('delete','Menu');			
		}
		$array = array('status'=>$status,'message'=>$message);
		echo json_encode($array);
	}
  
	function set_status($id,$val)
	{	
		$this->menumodel->change_status($id,$val);
		echo $this->status_change($id,$val);
	}
	
	
	function set_submenu_status($id,$parent_id='',$val)
	{	
		$this->menumodel->change_status($id,$val);
		echo $this->status_change($id,$val);	
	}
	
	
	function view($id='')
	{
		if($id=='')
		{
			redirect('menu');
		}
		$this->tpl->set_page_title("View menu information");
		$menu=$this->menumodel->get_menu_details($id);							// get record
		$this->assign($menu); 
		$user_group_options=$this->usergroupmodel->group_option(); 		// get user group list
		$this->assign('user_group_options',$user_group_options);
		$user_group=explode(',',$menu['user_group_id']);   // explode task array
		$this->assign('user_group',$user_group);
		
		//------------ for grid board ---------------//
		$labels=array('menu_title'=>'Title','module_link'=>'Module Link','order'=>'Order','status'=>'Status',);
		$this->tpl->set_js(array('jquery.statusmenu'));
		$this->assign('labels',$labels);
		$config['total_rows'] = $this->menumodel->count_list($id);
		$config['uri_segment'] = 6;
		$config['select_value'] = $this->input->post('rec_per_page');
		$config['sort_on']='id';
		$config['sort_type']='desc';
		$this->assign('grid_action',array('edit'=>'edit','del'=>'del'));
		$this->set_pagination($config);
		$menu=$this->menumodel->get_list($id);
		$this->assign('records',$menu);		
		$head = array('page_title'=>'Menu Details','link_title'=>'Add Submenu','link_action'=>'menu/add/'.$id,'status_type'=>1);
		$this->load->view('menu/view_menu',$head);
	}
	
	
	function menu_permission()
	{
		$menu = $this->menumodel->get_list();
		foreach($menu as $val)
		{
			$data_child_array = array();
			$menu_child = $this->menumodel->get_list($val['id']);
			foreach($menu_child as $val_child)
			{
				$data_child['id'] = $val_child['id'];
				$data_child['menu_title'] = $val_child['menu_title'];
				$data_child['icon'] = $val_child['icon'];
				$data_child['user_group_id'] = $val_child['user_group_id'];
				$data_child_array[] = $data_child;
			}
			$data['id'] = $val['id'];
			$data['menu_title'] = $val['menu_title'];
			$data['icon'] = $val['icon'];
			$data['user_group_id'] = $val['user_group_id'];
			$data['child'] = $data_child_array;
			$data_array[] = $data;
		}
		$this->assign('menu_list',$data_array);	
		$user_group_options=$this->usergroupmodel->group_option(); 		// get user group list
		$this->assign('user_group_options',$user_group_options);
		$this->load->view('menu/menu_permission');
	}
	
	function update_permission()
	{
		$menu = $this->menumodel->get_all_menu();
		foreach($menu as $val)
		{
			$data['id'] = $val['id'];
			$data['user_group_id'] = implode(',',$this->input->post($val['id']));
			$data_array[] = $data;
		}
		
		$this->menumodel->update_menu_permission($data_array);
		$this->session->set_flashdata('message',$this->tpl->set_message('edit','Menu permission'));
		redirect('menu/menu_permission');
	}
	
	
	
 }
?>
