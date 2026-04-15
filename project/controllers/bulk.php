<?php

class Bulk extends Bindu_Controller {

    function __construct() {
        parent::__construct();
        $this->user_id = $this->session->userdata('user_userid');
        $this->load->helper(array(
            'html',
            'array',
            'form'
        ));
        $this->load->model(array(
            'apimodel'
        ));
        $timezone = "Asia/Dhaka";
        if (function_exists('date_default_timezone_set'))
            date_default_timezone_set($timezone);
    }

    /* --------------- check login ------------------ */

    /**
     *
     */
    function check_login() {
        $json = file_get_contents('php://input');

        // $json = '{"username":"admin","password":"admin"}';

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

    /* --------------- End check login ------------------ */

    function is_valid_xml($xml) {
        libxml_use_internal_errors(true);

        // $doc = new DOMDocument('1.0', 'utf-8');
        // $doc->loadXML( $xml );

        $errors = libxml_get_errors();
        return empty($errors);
    }

    function api() {
        $data = file_get_contents('php://input');
        $orderid = $this->user_id . uniqid();

        // simple hack to find the data format tried by the users

        $xml = strpos($data, '<xml') !== false || strpos($data, '<sms_data>') !== false;
        $json = strpos($data, '"sms_data"') !== false;

        // First check with json format

        if (is_object(json_decode($data))) {
            $jsondata = json_decode($data, TRUE);
            $json = true;
            $auth = $jsondata['auth'];
            if ($auth) {
                $authdata['username'] = $auth['username'];
                $authdata['api_key'] = $auth['api_key'];
                $authdata['api_secret'] = $auth['api_secret'];
            }

            $message_list = $jsondata['sms_data'];
            $allsmsdata = array();
            foreach ($message_list as $key => $val) {
                $smsdata['message'] = $val['message'];
                $smsdata['senderID'] = $val['mask'];
                $smsdata['recipient'] = $val['recipient'];
                $allsmsdata[] = $smsdata;
            }
        } else {

            // converting all special char to html entities
            $data = str_replace('&','&amp;', $data);
            $read_xml = simplexml_load_string($data);
            if ($read_xml !== false) {
                $xml = true;
                $authdata['username'] = trim($read_xml->auth->username);
                $authdata['api_key'] = trim($read_xml->auth->api_key);
                $authdata['api_secret'] = trim($read_xml->auth->api_secret);
                $allsmsdata = array();
                foreach ($read_xml->bulk_sms->sms as $xmlbulksms) {
                    $smsdata['message'] = $xmlbulksms->message;
                    $smsdata['senderID'] = $xmlbulksms->mask;
                    $smsdata['recipient'] = $xmlbulksms->recipient;
                    $allsmsdata[] = $smsdata;
                }
            } else {
                $sms['messageid'] = "";
                $sms['status'] = 'failed';
                $sms['message'] = 'Invalid data ! Please check your request data format!';
                if ($json) {
                    $responses['result'][] = $sms;
                    header("content_type: text/json", True);
                    echo json_encode($responses);
                    exit;
                } else
                if ($xml) {
                    $xml_response = '<?xml version="1.0" encoding="UTF-8" ?>';
                    $xml_response.= '<bulksms>
					    <messageid>' . $sms['messageid'] . '</messageid>
					    <status>' . $sms['status'] . '</status>
					    <message>' . $sms['message'] . '</message>
				    </bulksms>';
                    header("content_type: text/xml", True);
                    echo $xml_response;
                    exit;
                }
            }
        }

        $user = $this->apimodel->check_user($authdata);
        if ($user) {
            $file_name = $orderid . '.csv';
            $file_path = 'upload/group_members/' . $file_name;
            $fp = fopen($file_path, 'w');
            foreach ($allsmsdata as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);
            $model_data['user_id'] = $user['id'];
            $model_data['date'] = date('Y-m-d h:i:s');
            $model_data['source'] = 'API';
            $model_data['sentFrom'] = '8167';
            $model_data['is_unicode'] = 'NA';
            $model_data['IP'] = $this->get_client_ip();
            $model_data['message'] = 'Bulk Request accepted from Bulk API';
            $model_data['sms_type'] = 'SentMessage';
            $model_data['orderid'] = $orderid;
            $model_data['file'] = $file_name;
            $sendmessage_id = $this->apimodel->add($model_data);
            if ($sendmessage_id) {
                $sms['messageid'] = $orderid;
                $sms['status'] = 'success';
                $sms['message'] = 'Request has been accepted successfully';
                $responses['result'][] = $sms;
                if ($json) {
                    header("content_type: text/json", True);
                    echo json_encode($responses);
                    exit;
                } else
                if ($xml) {
                    $xml_response = '<?xml version="1.0" encoding="UTF-8" ?>';
                    $xml_response.= '<bulksms>
						<messageid>' . $sms['messageid'] . '</messageid>
						<status>' . $sms['status'] . '</status>
						<message>' . $sms['message'] . '</message>
					</bulksms>';
                    header("Content_type: text/xml", True);
                    echo $xml_response;
                    exit;
                }
            } else {
                $sms['messageid'] = "";
                $sms['status'] = 'failed';
                $sms['message'] = 'Please check your request data!';
                $responses['result'][] = $sms;
                if ($json) {
                    header("content_type: application/json", True);
                    echo json_encode($responses);
                    exit;
                }

                if ($xml) {
                    $xml_response = '<?xml version="1.0" encoding="UTF-8" ?>';
                    $xml_response.= '<bulksms>
						<messageid>' . $sms['messageid'] . '</messageid>
						<status>' . $sms['status'] . '</status>
						<message>' . $sms['message'] . '</message>
					</bulksms>';
                    header("content_type: text/xml", True);
                    echo $xml_response;
                    exit;
                }
            }
        } else {
            $sms['messageid'] = "";
            $sms['status'] = 'failed';
            $sms['message'] = 'Please check your User or password or Key or Secret';
            $responses['user'][] = $user;
            if ($json) {
                header("content_type: application/json", True);
                echo json_encode($responses);
                exit;
            }

            if ($xml) {
                $xml_response = '<?xml version="1.0" encoding="UTF-8" ?>';
                $xml_response.= '<bulksms>
					<messageid>' . $sms['messageid'] . '</messageid>
					<status>' . $sms['status'] . '</status>
					<message>' . $sms['message'] . '</message>
				</bulksms>';
                header("content_type: text/xml", True);
                echo $xml_response;
                exit;
            }
        }
    }

    /* ---------------------- End Send SMS --------------- */
}
