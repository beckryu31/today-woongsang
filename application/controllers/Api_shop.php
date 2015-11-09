<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_shop extends CI_Controller
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
		$this->load->model('Shops_model');
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

	public function sub_tag()
	{
		if (! isset($_POST['data1'])) {
			$this->output->set_content_type('application/json')->set_output($this->bd_error->make_json_error_msg(Bd_error::BD_ERR_INVALID_PARAM));
			return;
		}

		$mainTag = intval($this->input->post('data1'));
		$data = $this->Shops_model->getSubTagArray($mainTag);

		$result = array(
			'return_code' => Bd_error::BD_ERR_SUCCESS,
			'msg' => $this->bd_error->get_error_msg(Bd_error::BD_ERR_SUCCESS),
			'data' => $data
		);

        echo json_encode($result);
	}

	public function shop_list()
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

		$region = intval($this->input->post('data1'));
		$mainTag = intval($this->input->post('data2'));
		$subOrder = intval($this->input->post('data3'));
		$this->Shops_model->getShopArray($region, $mainTag, $subOrder);
	}
}
