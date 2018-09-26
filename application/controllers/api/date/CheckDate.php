<?php
/* 
 * @author  Napapat
 */
//error_reporting(E_ALL);
//ini_set('display_errors', 0);

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class CheckDate extends REST_Controller {

    function __construct(){
        parent::__construct();
    }

    public function index_get(){
        $validate = $this->validate($this->get());
        if($validate !== TRUE)
        {
            $response = array('status'=> false,
                            'message'=> $validate);
            return $this->response($response, REST_Controller::HTTP_BAD_REQUEST);
        }
		
		$respose = array(
			'message' => 'validated success',
			'note' => 'test validate'
		);
		
		return $this->response($response, REST_Controller::HTTP_OK);
    }

    private function validate($data)
    {
        // check value is not empty
        if(empty($data))
        {
            return 'invalid parameter';
        }
        
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);
        // set rule validate
        $this->form_validation->set_rules('destinationId', 'destinationId', 'required|numeric');
        $this->form_validation->set_rules('date', 'date', 'required|callback_check_date');
        if ($this->form_validation->run() == FALSE)
        {   
            return $this->form_validation->error_string("");
        }

        //check checkin date format
        $date_now = new DateTime(date('Y-m-d'));
        //checkin date must equal or greather than today
        $searchDate = new DateTime($data['date']);

        if($searchDate < $date_now)
        {
            return 'Search date must be equal or greather than today';
        }
        
        return TRUE;
    }

    public function check_date($date){
        //$this->form_validation->set_error_delimiters('','');
        if (!DateTime::createFromFormat('Y-m-d', $date)) { 
            //yes it's YYYY-MM-DD
            $this->form_validation->set_message('date', 'The {field} has not a valid date format');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
