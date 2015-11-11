<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_board extends CI_Controller
{
    const BOARD_IMAGE_BASE_DIR = "/var/www/project/img/board/";
    const PROFILE_IMAGE_BASE_DIR = "/var/www/project/img/profile/";
    const THUMBNAIL_IMAGE_BASE_DIR = "/var/www/project/img/thumbnail/";

    const BOARD_TYPE_HOT_PLACE = 1;
    const BOARD_TYPE_TALK = 2;
    const BOARD_TYPE_COUPON = 3;
    const BOARD_TYPE_BIG_SALE = 4;
    const BOARD_TYPE_SHOP = 5;
    const BOARD_TYPE_EVENT = 6;
    const BOARD_TYPE_PRICE_INFO = 7;
    const BOARD_TYPE_MARKET = 8;
    const BOARD_TYPE_NOTICE = 9;

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
        $this->load->model('Boards_model');
	}

	public function postTalkContent()
	{
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
        if (! isset($_POST['data5'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $userSn = intval($this->input->post('data1'));
		$deviceId = $this->input->post('data2');
        $region = intval($this->input->post('data3'));
        $boardType = intval($this->input->post('data4'));
        $content = $this->input->post('data5');

		if ($this->Users_model->checkUser($userSn, $deviceId) == false) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        $currentTime = date('Y-m-d H:i:s');

        $insertData = array(
            'coRegion' => $region,
            'coType' => $boardType,
            'coWriter' => $userSn,
            'coTitle' => "",
            'coSubTitle' => "",
            'coPhone' => "",
            'coPhotoCount' => 0,
            'coContent' => $content,
            'coAddress' => "",
            'coDesc' => "",
            'coRelate' => "",
            'coRelateId' => 0,
            'coStartDate' => $currentTime,
            'coExpireDate' => $currentTime,
            'coTag' => 0,
            'coLikeCount' => 0,
            'coCouponCount' => 0,
            'coMainTag' => 0,
            'coSubOrder' => 0,
            'coAdType' => 0,
            'coAdvertizer' => ""
        );

        $boardResult = $this->Boards_model->insert($insertData);
        if ($boardResult['result'] == false) {
            $result = array(
                'return_code' => Bd_error::BD_ERR_DB_INSERT_FAIL,
                'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_DB_INSERT_FAIL),
                'board_sn' => $boardResult['sn']
            );
        } else {
            $result = array(
                'return_code' => Bd_error::BD_ERR_SUCCESS,
                'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
                'board_sn' => $boardResult['sn']
            );
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($result));

	}

    public function postBoardImage() {
        $up_name = array_keys($_FILES)[0];

        $uploadInfo = $_FILES[$up_name];
        $result = b2d_upload_check($uploadInfo);
        if ($result != Bd_error::BD_ERR_SUCCESS) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg($result));
            return;
        }

        $file_name = pathinfo($uploadInfo['name'], PATHINFO_FILENAME);
        $file_ext = pathinfo($uploadInfo['name'], PATHINFO_EXTENSION);
        $boardSn = intval($file_name);
        $dir_name = $boardSn % 255;

        $tmp_name = $uploadInfo['tmp_name'];
        $sequence = $this->Boards_model->getBoardImageCount($boardSn);
        $dest_name = sprintf("%s%02x/%s-%d.%s", self::BOARD_IMAGE_BASE_DIR, $dir_name, $file_name, $sequence, $file_ext);

        if (! move_uploaded_file($tmp_name, $dest_name)) {
            $this->output->set_content_type('application/json')->set_output(Ndg_error::ndg_make_json_error_msg(Ndg_error::NDG_ERR_UPLOAD_MOVE_FAILED));
            return;
        }

        $thumbnailDest = sprintf("%s%02x/%s-%d.%s", self::THUMBNAIL_IMAGE_BASE_DIR, $dir_name, $file_name, $sequence, $file_ext);

        $config['image_library'] = 'gd2';
        $config['source_image'] = $dest_name;
        $config['create_thumb'] = FALSE;
        $config['new_image'] = $thumbnailDest;
        $config['maintain_ratio'] = true;
        $config['width'] = 256;
        $config['height'] = 256;

        $this->load->library('image_lib', $config);

        if (!$this->image_lib->resize()) {
            $result = array(
                'return_code' => Bd_error::BD_ERR_INVALID_TAG_UUID,
                'msg' => $this->image_lib->display_errors()
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($result));
            return;
        }

        // DB에 저장할 것
        $this->Boards_model->increaseBoardImageCount($boardSn);

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS)
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function boardWriterProfileImage() {
        if (! isset($_GET['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        $boardSn = intval($_GET['data1']);

        $writerSn = $this->Boards_model->writerSn($boardSn);

        if ($writerSn == 0) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_DB_SELECT_FAIL));
            return;
        }

        $filename = sprintf("%d.jpg", $writerSn);

        $pull_path = sprintf("%s%02x/%s", self::PROFILE_IMAGE_BASE_DIR, $writerSn % 255, $filename);

        if (!file_exists($pull_path)) {
            $pull_path = sprintf("%s/00/0.png", self::PROFILE_IMAGE_BASE_DIR);
        }
        if (file_exists($pull_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($pull_path));
            readfile($pull_path);
            exit;
        } else {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_DOWNLOAD_NO_FILE));
            return;
        }
    }

    public function boardThumbnail() {
        if (! isset($_GET['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        if (! isset($_GET['data2'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        $boardSn = intval($_GET['data1']);
        $order = intval($_GET['data2']);

        $filename = sprintf("%d-%d.jpg", $boardSn, $order);

        $pull_path = sprintf("%s%02x/%s", self::THUMBNAIL_IMAGE_BASE_DIR, $boardSn % 255, $filename);

        if (!file_exists($pull_path)) {
            $pull_path = sprintf("%s00/0.jpg", self::BOARD_IMAGE_BASE_DIR);
        }
        if (file_exists($pull_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($pull_path));
            readfile($pull_path);
            exit;
        } else {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_DOWNLOAD_NO_FILE));
            return;
        }
    }

    public function boardPhoto() {
        if (! isset($_GET['data1'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        if (! isset($_GET['data2'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        $boardSn = intval($_GET['data1']);
        $order = intval($_GET['data2']);

        $filename = sprintf("%d-%d.jpg", $boardSn, $order);

        $pull_path = sprintf("%s%02x/%s", self::BOARD_IMAGE_BASE_DIR, $boardSn % 255, $filename);

        if (!file_exists($pull_path)) {
            $pull_path = sprintf("%s00/0.jpg", self::BOARD_IMAGE_BASE_DIR);
        }
        if (file_exists($pull_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.$filename);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($pull_path));
            readfile($pull_path);
            exit;
        } else {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_DOWNLOAD_NO_FILE));
            return;
        }
    }

    public function boardPostList() {
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

        if ($this->Users_model->checkUser($userSn, $deviceId) == false) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        $regionCode = intval($this->input->post('data3'));
        $boardType = intval($this->input->post('data4'));

        switch($boardType) {
            case self::BOARD_TYPE_HOT_PLACE:
                $data = $this->Boards_model->getPosting($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_TALK:
                $data = $this->Boards_model->getPosting($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_COUPON:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_BIG_SALE:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_EVENT:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_PRICE_INFO:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_MARKET:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_NOTICE:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            default:
                $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_BOARD_TYPE));
                return;
        }

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function shopAdList() {
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
        if (! isset($_POST['data5'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }
        if (! isset($_POST['data6'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $userSn = intval($this->input->post('data1'));
        $deviceId = $this->input->post('data2');

        if ($this->Users_model->checkUser($userSn, $deviceId) == false) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        $regionCode = intval($this->input->post('data3'));
        $boardType = intval($this->input->post('data4'));
        $mainTag = intval($_POST['data5']);
        $subTag = intval($_POST['data6']);

        switch($boardType) {
            case self::BOARD_TYPE_SHOP:
                $data = $this->Boards_model->getShopAdList($regionCode, $boardType, $mainTag, $subTag);
                break;
            default:
                $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_BOARD_TYPE));
                return;
        }

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function allShopTag() {
        $mainTag = $this->Boards_model->allShopMainTag();
        $subTag = $this->Boards_model->allShopSubTag();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'main_tag' => $mainTag,
            'sub_tag' => $subTag
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function allShopSubTag() {
        $data = $this->Boards_model->allShopSubTag();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function marketTag() {
        $mainTag = $this->Boards_model->marketTag();
        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $mainTag
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function content() {
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
        if (! isset($_POST['data5'])) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
            return;
        }

        $userSn = intval($this->input->post('data1'));
        $deviceId = $this->input->post('data2');

        if ($this->Users_model->checkUser($userSn, $deviceId) == false) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        $regionCode = intval($this->input->post('data3'));
        $boardType = intval($this->input->post('data4'));
        $postSn = intval($this->input->post('data5'));

        switch($boardType) {
            case self::BOARD_TYPE_HOT_PLACE:
                $data = $this->Boards_model->getTalkContent($postSn, $userSn);
                break;
            case self::BOARD_TYPE_TALK:
                $data = $this->Boards_model->getPosting($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_COUPON:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_BIG_SALE:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_EVENT:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_PRICE_INFO:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_MARKET:
                $data = $this->Boards_model->getBoardList($regionCode, $boardType);
                break;
            case self::BOARD_TYPE_NOTICE:
                $data = $this->Boards_model->getNoticeContent($postSn);
                break;
            default:
                $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_BOARD_TYPE));
                return;
        }

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
            'data' => $data
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }

    public function like() {
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

        if ($this->Users_model->checkUser($userSn, $deviceId) == false) {
            $this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_AUTH_FAILED));
            return;
        }

        $postSn = intval($this->input->post('data3'));
        $isLike = intval($this->input->post('data4'));

        if ($isLike > 0) {
            $this->Boards_model->setLike($postSn, $userSn);
        } else {
            $this->Boards_model->unsetLike($postSn, $userSn);
        }

        $result = array(
            'return_code' => Bd_error::BD_ERR_SUCCESS,
            'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS)
        );

        $this->output->set_content_type('application/json')->set_output(json_encode($result));
    }
}
