<?php

class Sms_api extends Bindu_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('html', 'array', 'form'));
        $this->load->model(array('apimodel'));
    }

    /*--------------- check login ------------------*/
    /**
     *
     */
    function check_login()
    {
        $json = file_get_contents('php://input');
        //$json = '{"username":"admin","password":"admin"}';
        $jsondata = json_decode($json, TRUE);

        $data['username'] = $jsondata['username'];
        $data['passwd'] = md5($jsondata['password']);
		$data['APIKEY'] = $jsondata['APIKEY'];
		$data['SECRETKEY'] = $jsondata['SECRETKEY'];
        
        $user = $this->apimodel->check_user($data);
        if ($user) {
            $user['user_id'] = $user['id'];
            $user['status'] = 'success';
            $user['message'] = 'Login Successful.';
            $responses['user'][] = $user;
        } else {
            $user['user_id'] = '';
            $user['username'] = '';
            $user['status'] = 'fail';
            $user['message'] = 'Username or password did not match.';
            $responses['user'][] = $user;
        }
        header("content_type: application/json", True);
        echo json_encode($responses);

    }
    /*--------------- End check login ------------------*/

	function sendmessage()
	{
		$json = file_get_contents('php://input');
        $orderid = 'ORD-'.uniqid();
        $firstresponses['responses'][] = 'Accept your request. Your sms order id : '.$orderid;
		echo json_encode($firstresponses);
		$json = '{
                  "user_id": "10",
                  "message": "sdfsfsdfsfsd fsfdsfsdf dsfsdfsdf",
                  "senderID": "232323",
                  "pages": "2",
                  "units": "2",
                  "sms_type": "SentMessage",
                  "scheduleDateTime": "2016-10-14 11:08:00",
                  "recipient": [
                      {
                        "number": "01734183130"   
                      },
                      {
						"number": "01734183130"
                      },
                      {
						"number": "01734183130"
                      },
                      {
						"number": "01734183130"
                      }
                  ]
              }';
        $jsondata = json_decode($json, TRUE);
		
		
		
		$data['user_id'] = $jsondata['user_id'];
		$data['message'] = $jsondata['message'];
		$data['senderID'] = $jsondata['senderID'];
		$data['date'] =  date('Y-m-d h:i:s');
		$data['pages'] = $jsondata['pages'];
		$data['source'] = 'API';
		$data['units']=  $jsondata['units'];
		$data['sentFrom'] = '8167';
		$data['is_unicode']= '';
		$data['IP']= '';
		$data['sms_type'] = $jsondata['sms_type'];
		$data['scheduleDateTime'] = $jsondata['scheduleDateTime'];
		$data['orderid'] = $orderid;
		
		$recipient_list = $jsondata['recipient'];
        $recipient_file = array();
        foreach ($recipient_list as $key => $val) {
            $phonenumber['phone'] = $val['number'];
			$recipient_file[] = $phonenumber;
        }
		$oneDimensionalArray = array_map('current', $recipient_file);
		$string = implode(", ", $oneDimensionalArray);
		
		
		$file_name = uniqid().'.txt';
		$file_path = 'upload/group_members/'.$file_name;
		$handle = fopen($file_path, 'w') or die('Cannot open file:  '.$file_path); //implicitly creates file
		
		file_put_contents($file_path, $string);
		
		$data['file']= $file_name;

		$sendmessage_id=$this->apimodel->add($data);
		 if ($sendmessage_id) {
            $sms['status'] = 'success';
            $sms['message'] = 'SMS has been added successfully.';
            $responses['result'][] = $sms;
        } else {
            $sms['status'] = 'fail';
            $sms['message'] = 'Data insert problem.';
            $responses['result'][] = $sms;
        }
        header("content_type: application/json", True);
        echo json_encode($responses);
	}
	
	/*---------------------- End Send SMS ---------------*/ 
		
}