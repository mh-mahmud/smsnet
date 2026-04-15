<?php
 class Test extends Bindu_Controller
 {
 	 function __construct()
 	 {
 	 	parent::__construct();
 	 	$this->user_id = $this->session->userdata('user_userid');
		$this->load->model(array('testmodel'));
		$this->tpl->set_page_title('Payment Management');
		$this->tpl->set_css(array('chosen','datepicker'));
        $this->tpl->set_js(array('chosen','datepicker'));
    }

  	function index($sort_type='desc',$sort_on='id')
  	{
  		$getAllSenderId = $this->testmodel->getAllSenderId();
		foreach($getAllSenderId as $val)
		{
			$this->testmodel->updateAllSenderId($val['senderID']);
		}
		echo 'Complete.';
  	}
 }
?>
