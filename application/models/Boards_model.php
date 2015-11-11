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
        $queryString = "SELECT BoardTable.coSn, coWriter, coNick, coPhotoCount, coReplyCount, coContent, BoardTable.coEntryDate FROM BoardTable INNER JOIN UserTable ON BoardTable.coWriter=UserTable.coSn WHERE BoardTable.coRegion=? AND BoardTable.coType=? AND coDelete=0 AND coParentSn=0 ORDER BY BoardTable.coEntryDate DESC";
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

    public function getTalkContent($postSn, $userSn) {
        $data = array();
        $data['content'] = "";
        $data['like'] = 0;

        $queryString = "SELECT coContent FROM BoardTable WHERE coSn=?";
        $queryResult = $this->db->query($queryString, array($postSn));
        if ($queryResult->num_rows() == 0) {
            return $data;
        }
        if (is_null($queryResult->row()->coContent)) {
            return $data;
        }
        $data['content'] = $queryResult->row()->coContent;
        $queryString = "SELECT COUNT(coPostSn) AS coLike FROM FavorateTable WHERE coUserSn=? AND coPostSn=?";
        $queryResult = $this->db->query($queryString, array($userSn, $postSn));
        $data['like'] = $queryResult->row()->coLike;

        return $data;
    }

    public function getNoticeContent($postSn) {
        $queryString = "SELECT coContent FROM BoardTable WHERE coSn=?";
        $queryResult = $this->db->query($queryString, array($postSn));
        if ($queryResult->num_rows() == 0) {
            return "";
        }
        if (is_null($queryResult->row()->coContent)) {
            return "";
        } else {
            return $queryResult->row()->coContent;
        }
    }

    public function setLike($postSn, $userSn) {
        $data = array(
            'coUserSn' => $userSn,
            'coPostSn' => $postSn
        );

        $queryString = "SELECT COUNT(coPostSn) AS coLike FROM FavorateTable WHERE coUserSn=? AND coPostSn=?";
        $queryResult = $this->db->query($queryString, array($userSn, $postSn));
        if ($queryResult->row()->coLike == 0) {
            $this->db->insert('FavorateTable', $data);
        }
    }

    public function unsetLike($postSn, $userSn) {
        do {
            $queryString = "SELECT COUNT(coPostSn) AS coLike FROM FavorateTable WHERE coUserSn=? AND coPostSn=?";
            $queryResult = $this->db->query($queryString, array($userSn, $postSn));
            if ($queryResult->row()->coLike > 0) {
                $queryString = "DELETE FROM FavorateTable WHERE coUserSn=? AND coPostSn=?";
                $this->db->query($queryString, array($userSn, $postSn));
            } else {
                break;
            }
        } while(true);

    }
}
