<?php
 class Ajax extends Bindu_Controller
 {
 	function __construct()
 	{
 	 	parent::__construct();
 	 	if($this->input->is_ajax_request()) {
            $this->tpl->set_layout('ajax_layout');
        }       
		$this->load->model(array('ajaxmodel'));
 	}

  	function get_category()
    {
        $brand_id = $this->input->post('brand_id');
        $rs = array(array('id' => '', 'title' => '---- Select Category ----'));
        $get_category = array_merge($rs, $this->ajaxmodel->get_category($brand_id));
        $this->output->set_output(json_encode($get_category));
    }
	
	function get_subcategory()
    {
        $category_id = $this->input->post('category_id');
        $rs = array(array('id' => '', 'title' => '---- Select Sub Category ----'));
        $get_subcategory = array_merge($rs, $this->ajaxmodel->get_subcategory($category_id));
        $this->output->set_output(json_encode($get_subcategory));
    }
	
	function get_product()
    {
        $category_id = $this->input->post('category_id');
        $rs = array(array('id' => '', 'title' => '---- Select Product ----'));
        $get_product = array_merge($rs, $this->ajaxmodel->get_product($category_id));
        $this->output->set_output(json_encode($get_product));
    }
	
 }
?>
