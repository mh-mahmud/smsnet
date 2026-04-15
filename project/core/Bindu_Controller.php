<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Bindu_Controller extends CI_Controller
 {
 	var $data=array();
 	var $name;
 	var $method;
 	var $search_name;

 	function __construct()
 	{
 		parent::__construct();
 		$this->load->library('template','','tpl');
 		$this->load->helper(array('text','date'));
		$this->load->model(array('menumodel','usergroupmodel'));
 		$this->name = $this->router->class;
 		$this->method = $this->router->method;
 		$this->search_name = $this->router->class.'_'.$this->router->method;
 		$this->_check_access();
		$this->access_check();
 		$this->_temp_init();
        $this->tpl->set_css(array('chosen', 'datepicker'));
        $this->tpl->set_js(array('chosen', 'datepicker')); 		
 	}
	
 	function _temp_init()
 	{
 		$this->_assign_template_var();
		if($this->input->is_ajax_request())
		{
			$this->tpl->set_layout('ajax_layout');
		}
 	}

 	function _assign_template_var()
 	{
		$this->tpl->set_page_title('Easy Store');
		$this->tpl->assign('active_controller',$this->name);
		$this->tpl->assign('active_method',$this->method);
		$this->set_top_menu();		
 	}	
	
	function access_check()
	{
		//echo $this->name; exit;
		$user_group = $this->session->userdata('user_group_id');
		$row = $this->menumodel->get_active_menu_id($this->full_url());	
		$user_group_list=explode(',',$row['user_group_id']);	
		if(in_array($user_group,$user_group_list))
		{
			return true;
		}elseif($this->name=='login' OR $this->name=='bulk' OR $this->name=='sendmessage_cronjob'){
			return true;
		}elseif(empty($row) AND $_SERVER['HTTP_REFERER']!=''){
			return true;
		}
		else{
			redirect('home'); 
		}
	}	
	
	function set_top_menu()
 	{
 		$row = $this->menumodel->get_active_menu_id($this->name);
		$user_group=$this->session->userdata('user_group_id');		
		$tm=$this->menumodel->get_menu_list('',$user_group);
		$this->main_menu_data=$tm;
   		$this->_main_menu_html='<ul class="nav" id="main-menu">';
   		$i=1;
		
		foreach($this->main_menu_data as $id=>$menu)
 		{
			if($id==$row['parent_id']){
				$class_li='active-menu';
				$subclass_li='collapse in';
			}elseif($this->name==$menu['module_link'] AND $row['parent_id']==0){
				$class_li='active-menu';
				$subclass_li='collapse in';
			}else{
				$class_li='';
				$subclass_li='collapse';
			}		
			
			if($menu['parent_id']==0)
			{			
				$found=$this->_check_main_menu_child($id);
				if($found>0){					
					$icon='<i class="fa fa-angle-right pull-right"></i>';
					$menu_link = '';
				}else{
					$icon='';
					$menu_link = 'menu_link';
				}				
				$this->_main_menu_html .="<li class='$class_li'><a class='".$menu_link."' href='".site_url().$menu['module_link']."'><i class='fa ".$menu['icon']."'></i><span>".$menu['title']."</span>$icon</a>";
				unset($this->main_menu_data[$id]);
						
				$this->main_menu_data=array_filter($this->main_menu_data);				
				if($found>0){					
					$this->_make_child_main_menu($id,$subclass_li);													
				}
			}
			$i++;
		}		
		$this->_main_menu_html .="</li>";
		$this->_main_menu_html .="</ul>";
		$html=$this->_main_menu_html;
		unset($this->_main_menu_html);
		unset($this->main_menu_data);
		$this->tpl->assign('top_menu_html',$html);	
 	}
	
	
	function _make_child_main_menu($pid,$subclass_li)
	{
		$this->_main_menu_html .="<ul class='nav nav-second-level $subclass_li'>";
		foreach($this->main_menu_data as $id=>$menu){
			if($this->name.'/'.$this->method==$menu['module_link']){
				$class_li='subactive';
			}else{
				$class_li='';
			}	
			if($menu['parent_id']==$pid)
 			{ 				
 				$this->_main_menu_html .="<li class='$class_li'><a class='menu_link' href='".site_url().$menu['module_link']."' title='".$menu['tips']."'><i class='fa ".$menu['icon']."'></i>".$menu['title']."</a>";	
				unset($this->main_menu_data[$id]);
 				$this->main_menu_data=array_filter($this->main_menu_data);
				$found=$this->_check_main_menu_child($id);
				if($found>0)
				$this->_make_child_main_menu($id);
				$this->_main_menu_html .="</li>";
 			}			
		}
		$this->_main_menu_html .="</ul>";		
    }	
	
	function _check_main_menu_child($pid)
    {
		$found=0;
		foreach($this->main_menu_data as $id=>$menu1)
		{
			if($menu1['parent_id']==$pid)
			{
				$found++;
			}		
		}		 
		return $found;
    }	
	
 	function assign($key,$val='')
 	{
 		$this->tpl->assign($key,$val);
 	}

 	function set_layout($file)
 	{
 		$this->tpl->set_layout($file);
 	}

 	function set_pagination($info=array())
 	{
		$this->load->library('pagination');
 		$rec_per_page=$info['select_value'];
 		$data['offset']=0;

 		if($rec_per_page)
 		{
			$config['per_page'] = $rec_per_page;
 		}
 		else
 		{
			$config['per_page']=20;
 		}
 		$data['rec_per_page'] = $config['per_page'];
		$config['num_links'] = 4;
		$config['uri_segment'] = $info['uri_segment'];
		
		$config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
		
		$config['total_rows']=$info['total_rows'];
		$stypes=array('asc'=>'desc','desc'=>'asc');
		$nstype=$stypes[$info['sort_type']];

		$data['sort_type']=$info['sort_type'];
		$data['sort_on']=$info['sort_on'];
		if($info['sufix'])

		$url=$this->tpl->site_url.$this->name.'/'.$this->method.'/'.$info['sufix'].'/';
		else
		 $url=$this->tpl->site_url.$this->name.'/'.$this->method.'/';

		if($info['sort_type'] && $info['sort_on'])

		{
			$config['base_url'] = $url.$info['sort_type'].'/'.$info['sort_on'].'/page';
			$data['sort_url'] = $url.$nstype.'/%s'.'/page';
		}
		else
		{
			$config['base_url'] =$url;
			$data['sort_url'] =$url.'asc/';
		}

		$this->pagination->initialize($config);
		$data['total_record'] = $config['total_rows'];
		$pagination_html= $this->pagination->create_links();
		$this->tpl->set_pagination($pagination_html);
		$this->db->order_by($info['sort_on'], $info['sort_type']);

		if($pagination_html)
		{

				$data['total_page'] = ceil($config['total_rows']/$config['per_page']);
				$data['cur_page']=$this->pagination->cur_page;
				$data['offset']=(int)$this->uri->segment($info['uri_segment']);
				$data['sort_url']=$data['sort_url'].'/'.$data['offset'];
				$this->db->limit($config['per_page'], $data['offset']);

		}
		else
		{
				$data['total_page'] = 1;
				$data['cur_page']=1;
				$data['offset']=0;

		}
		$this->tpl->assign($data);
 	}
	
	
 	function _check_access()
 	{
 		if($this->session->userdata('validated')==false AND $this->name!='login' AND $this->name!='sendmessage_cronjob' AND $this->name!='bulk'){
			redirect('login');
		}else{
			return true;
		} 		
 	}

 	function _is_login_require()
 	{
 		if($this->name=='login')
 		{
 			return false;
 		}
 		else
 		{
 			return true;
 		}
 	}
		
	/*-------------- set current date -----------------*/
	function current_date()
	{
		$timezone = "Asia/Dhaka";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);	
		return date("Y-m-d h:i:s");
	}
	function current_date_only()
	{
		$timezone = "Asia/Dhaka";
		if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);	
		return date("Y-m-d");
	}
	
	/*-------------- End -----------------*/
	
	
	function status_change($id,$val)
	{
		if(strtolower($val)=='publish')
		{
			$data = '<span class="label label-success">Publish</span>';
		}else if(strtolower($val)=='unpublish')
		{
			$data = '<span class="label label-danger">Unpublish</span>';
		}else if(strtolower($val)=='active')
		{
			$data = '<span class="label label-success">Active</span>';
		}else if(strtolower($val)=='inactive')
		{
			$data = '<span class="label label-danger">Inactive</span>';
		}else if(strtolower($val)=='approved')
		{
			$data = '<span class="label label-success">Approved</span>';
		}else if(strtolower($val)=='pending')
		{
			$data = '<span class="label label-warning">Pending</span>';
		}
		$array = array('id'=>$id,'data'=>$data);
		return json_encode($array);
	}
	
	
	function full_url()
	{
		$ci=& get_instance();
		$return = $ci->uri->uri_string();		
		return $return;
	} 
	
	function word_limiter($string, $word_limit){
		$words = explode(" ",$string);
		return implode(" ",array_splice($words,0,$word_limit));
	}
	
	function get_client_ip() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
	
	//Get excel
	Public function get_excel($labels,$report_exel)
	{
		//$labels=array('username'=>'Username','mobile'=>'Mobile No.','address'=>'Address','user_type'=>'User Type','status'=>'Status');
		//$report_exel = $this->usermodel->get_list_report();
		ini_set('memory_limit', '-1');
		$exceldata="";
		foreach ($report_exel as $row){
			$exceldata[] = $row;
		}


		$this->load->library('excel');
		$this->excel->setActiveSheetIndex(0);
        $this->excel->getActiveSheet()->setTitle('Excel List');
        $this->excel->getActiveSheet()->setCellValue('A1', 'Excel Sheet Generated!');
		$loopChar = 65;
		foreach($labels as $key=>$val){
			$this->excel->getActiveSheet()->setCellValue(chr($loopChar).'2', $key); $loopChar++;
		}
		
        //merge cell A1 until C1
		$this->excel->getActiveSheet()->mergeCells('A1:H1');
        //set aligment to center for that merged cell (A1 to C1)
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);
		$this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

		for($col = ord('A'); $col <= ord('I'); $col++){
                //set column dimension
			$this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         //change the font size
			$this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);

			$this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
		//Fill data 
		$this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');
		$charval = 65;
		foreach($exceldata as $eval){
			$this->excel->getActiveSheet()->getStyle(chr($loopChar).'3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$charval++;
		}
		
		$filename='report_List-'.date('ymdhis').'.xls'; //save our workbook as this file name
		header('Content-Type: application/vnd.ms-excel'); //mime type
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache

		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save('php://output');
    }
	
 }
?>
