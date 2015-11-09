<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_service extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encrypt');
		$this->load->library('Bd_error');
		$this->load->library('class_cert');
		$this->load->helper('url');
		$this->load->database();
		$this->load->model('Service_model');
	}

	public function index()
	{
        $version = $this->Service_model->serverVersion();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'version' => $version
        );
        echo json_encode($result);

	}

    public function serverVersion()
    {
        $version = $this->Service_model->serverVersion();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'version' => $version
        );
        echo json_encode($result);
    }

	public function androidAppVersion()
	{
        $version = $this->Service_model->androidAppVersion();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => array(
                'version' => $version,
                'url' => 'market://details?id=kr.co.socialtoday.pusanjin&hl=ko',
                'must' => 0
            )
        );
        echo json_encode($result);
	}

    public function iOSAppVersion()
    {
        $version = $this->Service_model->iOSAppVersion();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'version' => $version
        );
        echo json_encode($result);
    }

    public function info() {
        if (! isset($_POST['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $key = $_POST['data1'];

        if (strcmp($key, "r6CtvpiDgzVdmVv7Jo3jp9ttJwmNJcj6")) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        echo phpinfo();
    }

    public function revision() {
        $revisions = $this->Service_model->revision();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $revisions
        );
        echo json_encode($result);
    }
}
