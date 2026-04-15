<?php
 class Loginmodel extends Bindu_Model
 {
 	///var $table='user';

 	function check_login()
	{
 		$user = $this->input->post('username');
 		$pass = md5($this->input->post('passwd'));
 		$this->db->select('ad.id,username,created_by,id_user_group,status, rate_id');		
 	 	$this->db->from('user ad');
 	 	$this->db->where('ad.username',$user);
		$this->db->where('ad.passwd',$pass);
		//$this->db->where('ad.status','ACTIVE');
 		return $this->get_row();	
	}
	
 	
	function get_logged_user()
 	{
 		$userid = $this->session->userdata('user_userid');
 		$username = $this->session->userdata('user_username');
        $user_group_id = $this->session->userdata('user_group_id');
        $this->db->select('user.id,id_user_group, username,short_name,ag.title user_type,email, address, mobile,user.status');
        $this->db->join('user_group ag', 'ag.id = id_user_group','left');
        $this->db->where(array('user.id'=>$userid,'username'=>$username));
        $user=$this->get_row();
        return $user;
 	}
	
	
	function update_login_time($user_id,$date_time)
	{
		$data['last_login_time']=$date_time;
		$this->db->update('user',$data,array('id'=>$user_id));
	}
	
 }
?>
