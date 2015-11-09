<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_user extends CI_Controller
{
    const PROFILE_IMAGE_BASE_DIR = "/var/www/project/img/profile/";

	function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('encrypt');
		$this->load->library('Bd_error');
		$this->load->library('class_cert');
		$this->load->helper('url');
        $this->load->helper('b2d_helper');
		$this->load->database();
		$this->load->model('Users_model');
        $this->load->model('Service_model');
	}

	public function index()
	{
		if (! isset($_POST['data1'])) {
			$this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
			return;
		}
		$deviceId = $this->input->post('data1');
		$this->Users_model->get_user($deviceId);
	}

	public function user()
	{
		if (! isset($_POST['data1'])) {
			$this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
			return;
		}
		$deviceId = $this->input->post('data1');
		$this->Users_model->get_user($deviceId);
	}



    public function androidAppVersion()
    {
        if (! isset($_POST['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        if (! isset($_POST['data2'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $appVersion = intval($this->input->post('data1'));
        $deviceId = $this->input->post('data2');
        $userSn = $this->Users_model->getUserInfoSetVersionWithDeviceId($deviceId, $appVersion);

        $version = $this->Service_model->androidAppVersion();
        $debugData = array(
            'device_id' => $deviceId
        );
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => array(
                'version' => $version,
                'url' => 'market://details?id=kr.co.socialtoday.pusanjin&hl=ko',
                'must' => 0,
                'user_sn' => $userSn
            ),
            'debug' => $debugData
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

	public function register() {
		if (! isset($_POST['data1'])) {
			$this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
			return;
		}
		if (! isset($_POST['data2'])) {
			$this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
			return;
		}
        if (! isset($_POST['data3'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

		$deviceId = $this->input->post('data1');
        if (strlen($deviceId) < 8) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $recommendId = $this->input->post('data2');
        /*
        if (strlen($deviceId) < 2) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        */

        $region = intval($this->input->post('data3'));
        if ($region == 0) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        $data['coDeviceId'] = $deviceId;
        $data['coRecommendId'] = $recommendId;
        $data['coRegion'] = $region;
        $data['coEntryDate'] = date('Y-m-d H:i:s');
		$result = $this->Users_model->register($data);

        if (! $result['result']) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_DB_INSERT_FAIL));
            return;
        }
        $sn = $result['sn'];

        $toClient = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'sn' => $sn,
            'recommend' => $recommendId
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($toClient));
	}

    /**
     * registerGcmToken: gcm token을 등록
     * data1: userSn => coSn
     * data2: gcm_token => coPushId
     *
     */
    public function registerGcmToken() {
        if (! isset($_POST['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        if (! isset($_POST['data2'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $userSn = intval($this->input->post('data1'));
        $token = $this->input->post('data2');

        $this->Users_model->registerGcmToken($userSn, $token);

        $toClient = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS)
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($toClient));
    }

    /**
     * postProfile
     * data1: userSn
     * data2: deviceId
     * data3: nick
     * data4: profile
     */
    public function postProfile() {
        if (! isset($_POST['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        if (! isset($_POST['data2'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        if (! isset($_POST['data3'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        if (! isset($_POST['data4'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $userSn = intval($this->input->post('data1'));
        $deviceId = $this->input->post('data2');
        $nick = $this->input->post('data3');
        $profile = $this->input->post('data4');

        $this->Users_model->updateProfile($userSn, $deviceId, $nick, $profile);

        $toClient = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS)
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($toClient));
    }

    public function postProfileImage() {
        $upName = array_keys($_FILES)[0];

        $uploadInfo = $_FILES[$upName];
        $result = b2d_upload_check($uploadInfo);
        if ($result != Bd_error::BD_ERR_SUCCESS) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg($result));
            return;
        }

        $filename = pathinfo($uploadInfo['name'], PATHINFO_FILENAME);
        $userSn = intval($filename);

        $dirName = $userSn % 255;

        $tmpName = $uploadInfo['tmp_name'];
        $destName = sprintf("%s%02x/%s", self::PROFILE_IMAGE_BASE_DIR, $dirName, $uploadInfo['name']);

        if (! move_uploaded_file($tmpName, $destName)) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_UPLOAD_MOVE_FAILED));
            return;
        }

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS)
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }


}
