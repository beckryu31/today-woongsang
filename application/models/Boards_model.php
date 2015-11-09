<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Boards_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // CREATE
    public function insert($data) {
        if ($this->db->insert('BoardTable', $data)) {
            $result = array(
                'result' => true,
                'sn' => $this->db->insert_id()
            );
            return $result;
        } else {
            $result = array(
                'result' => false,
                'sn' => 0
            );
            return $result;
        }
    }

    public function getBoardImageCount($boardSn) {
        $queryString = "SELECT coPhotoCount FROM BoardTable WHERE coSn=?";
        $queryResult = $this->db->query($queryString, array($boardSn));
        if ($queryResult->num_rows() == 0) {
            return 0;
        } else {
            return $queryResult->row()->coPhotoCount;
        }
    }

    public function increaseBoardImageCount($boardSn) {
        $queryString= "UPDATE BoardTable SET coPhotoCount=coPhotoCount+1 WHERE coSn=?";
        $this->db->query($queryString, array($boardSn));
    }

    public function writerSn($boardSn) {
        $queryString = "SELECT coWriter FROM BoardTable WHERE coSn=?";
        $queryResult = $this->db->query($queryString, array($boardSn));
        if ($queryResult->num_rows() == 0) {
            return 0;
        } else {
            return $queryResult->row()->coWriter;
        }
    }

    public function getPosting($regionCode, $boardType) {
        $queryString = "SELECT BoardTable.coSn, coWriter, coNick, coPhotoCount, coReplyCount, coContent, BoardTable.coEntryDate FROM BoardTable INNER JOIN UserTable ON BoardTable.coWriter=UserTable.coSn WHERE coDelete=0 AND BoardTable.coRegion=? AND BoardTable.coType=? ORDER BY BoardTable.coEntryDate DESC";
        $queryResult = $this->db->query($queryString, array($regionCode, $boardType));

        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }

    public function getBoardList($regionCode, $boardType) {
        $queryString = "SELECT coSn, coTitle, coSubTitle, coEntryDate FROM BoardTable WHERE coDelete=0 AND coRegion=? AND coType=? ORDER BY coEntryDate DESC";
        $queryResult = $this->db->query($queryString, array($regionCode, $boardType));

        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }

    public function getShopAdList($regionCode, $boardType) {
        $queryString = "SELECT coSn, coTitle, coSubTitle, coEntryDate, coPhone, coAdType, coPhotoCount FROM BoardTable WHERE coDelete=0 AND coRegion=? AND coType=? ORDER BY coAdType, coTitle ASC";
        $queryResult = $this->db->query($queryString, array($regionCode, $boardType));

        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }

    public function allShopMainTag() {
        $queryString = "SELECT coOrder, coName, coIcon FROM ShopMainTagTable ORDER BY coOrder ASC";
        $queryResult = $this->db->query($queryString);
        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }

    public function allShopSubTag() {
        $queryString = "SELECT coMainTag, coOrder, coName, coIcon FROM ShopSubTagTable ORDER BY coMainTag, coOrder ASC";
        $queryResult = $this->db->query($queryString);
        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }

    public function marketTag() {
        $queryString = "SELECT coOrder, coName, coIcon FROM MarketTagTable ORDER BY coOrder ASC";
        $queryResult = $this->db->query($queryString);
        if ($queryResult->num_rows() == 0) {
            return array();
        } else {
            return $queryResult->result_array();
        }
    }
}
