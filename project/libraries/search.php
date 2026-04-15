<?php
/*
 * Created on Jan 21, 2015
 *
 * Created By Imrul Hasan
 */
 
 class Search{
 	private $name;
 	function data_search()
	{
		$CI =& get_instance();		
		$name = $CI->search_name;
		if(!empty($_POST)){
			if($CI->input->post('submit')=='reset'){
				$CI->session->unset_userdata($name);
			}else{
				
				foreach($CI->input->post() as $key=>$val)
				{
					$sdata[$key] = $val;
				}
				$CI->session->set_userdata($name,$sdata);
				//$check = count(array_filter($sdata));
				//$CI->session->set_userdata($name,$check);
			}
		}else{
			
			if($CI->session->userdata($name)>0){
				foreach($CI->session->userdata($name) as $key=>$val)
				{
					$sdata[$key] = $val;
				}
			}else{
				$sdata = array();
			}
		}
		return $sdata;		
	}	
 }

?>
