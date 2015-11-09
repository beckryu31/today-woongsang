<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // READ
    public function androidAppVersion() {
        $query_string = "SELECT coAndroidVersion FROM ServiceTable WHERE coSn=1";
        $appVersion = $this->db->query($query_string)->row()->coAndroidVersion;

        return $appVersion;
    }

    public function iOsAppVersion() {
        $query_string = "SELECT coiOSVersion FROM ServiceTable WHERE coSn=1";
        $appVersion = $this->db->query($query_string)->row()->coiOSVersion;

        return $appVersion;
    }

    public function serverVersion() {
        $query_string = "SELECT coServerVersion FROM ServiceTable WHERE coSn=1";
        $appVersion = $this->db->query($query_string)->row()->coServerVersion;

        return $appVersion;
    }

    public function revision() {
        $queryString = "SELECT coSn, coName, coRevision FROM RevisionTable";
        $queryResult = $this->db->query($queryString, array());

        return $queryResult->result_array();
    }
}
