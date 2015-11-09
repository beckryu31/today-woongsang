<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // READ
    public function getUserInfoWithDeviceId($deviceId) {
        $query_string = "SELECT coSn FROM UserTable WHERE coDeviceId=?";
        $query_result = $this->db->query($query_string, array($deviceId));

        if ($query_result->num_rows() == 0) {
            return 0;
        } else {
            $sn = $query_result->row()->coSn;
            return $sn;

        }
    }

    public function checkUser($userSn, $deviceId) {
        $query_string = "SELECT coDeviceId FROM UserTable WHERE coSn=?";
        $query_result = $this->db->query($query_string, array($userSn));

        if ($query_result->num_rows() == 0) {
            return false;
        } else {
            if (strcmp($deviceId, $query_result->row()->coDeviceId) == 0) {
                return true;
            }
            return false;
        }
    }

    public function getUserInfoSetVersionWithDeviceId($deviceId, $appVersion) {
        $query_string = "SELECT coSn, coAppVersion FROM UserTable WHERE coDeviceId=?";
        $query_result = $this->db->query($query_string, array($deviceId));

        if ($query_result->num_rows() == 0) {
            return 0;
        } else {
            $result_row = $query_result->row();
            $sn = $result_row->coSn;
            if ($appVersion != $result_row->coAppVersion) {
                $this->updateAppVersion($sn, $appVersion);
            }
            return $sn;

        }
    }

    public function updateAppVersion($userSn, $appVersion) {
        $query_string = "UPDATE UserTable SET coAppVersion=? WHERE coSn=?";
        $this->db->query($query_string, array($appVersion, $userSn));
    }

    public function registerGcmToken($userSn, $token) {
        $data = array(
            'coPushId' => $token
        );

        $this->db->update('UserTable', $data, array('coSn' => $userSn));
        return true;
    }

    public function updateProfile($userSn, $deviceId, $nick, $profile) {
        $data = array(
            'coNick' => $nick,
            'coProfile' => $profile
        );

        $this->db->update('UserTable', $data, array('coSn' => $userSn));
        return true;
    }

    /**
     * get_all_users
     * 해당 컴퍼니의 모든 유저리스트를 가져온다.
     * $company_id == 0 이면 모든 유저 리스트를 가져온다. (site admin용)
     */
    public function get_user($company_sn) {
        $query_string = "SELECT coSn, coNick, coLevel, coCompanySn, coGroupSecondSn, coName, coCertLevel, coPhone, coRegisterDate, coMemo FROM tbUser WHERE coCompanySn=?";
        $query_result = $this->db->query($query_string, array($company_sn));

        return $query_result;
    }

    /**
     * get_site_admin
     * 회사 관리자 레벨의 모든 유저리스트를 가져온다.
     */
    public function get_site_admins() {
        $admin_level = Class_cert::USER_LEVEL_ADMIN;
        $query_string = "SELECT coSn, coEmail, coLevel, coCompanySn, coGroupSecondSn, coName, coCertLevel, coPhone, coRegisterDate, coMemo FROM tbUser WHERE coLevel=?";
//        $query_string = "SELECT coSn, coEmail, coLevel, coCompanySn, coGroupSecondSn, coName, coCertLevel, coPhone, coRegisterDate, coMemo FROM tbUser WHERE coLevel=100";
        $query_result = $this->db->query($query_string, array($admin_level));

        return $query_result;
    }

    // CREATE
    public function register($data) {
        if ($this->db->insert('UserTable', $data)) {
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

    /**
     * db에 유저를 넣고 sn 값을 리턴한다.
     * @param   array   $data
     * @return  int     새롭게 만들어진 레코드의 sn값. 실패면 0 리턴.
     */
    public function add_user($data) {
        if ($this->db->insert('tbUser', $data)) {
            return $this->db->insert_id();
        } else {
            return 0;
        }
    }

    public function delete_user($sn) {
        return $this->db->delete('tbUser', array('coSn' => $sn));
    }

    public function update_user($data, $sn) {
//        $this->db->where('coSn', $sn);
        $this->db->update('tbUser', $data, array('coSn' => $sn));

        return true;
    }
    // UTIL
    public function check_email($email) {
        $query_string = "SELECT coSn FROM tbUser WHERE coEmail= ?";
        $query = $this->db->query($query_string, array($email));
        return $query->num_rows();
    }

    public function validate_user($email, $password) {
        $query_string = "SELECT coSn, coPassword, coLevel, coCompanySn, coCertLevel FROM tbUser WHERE coEmail=?";

        $rows = $this->db->query($query_string, array($email));
        $return_data = array();

        if ($rows->num_rows() == 0) {
            $return_data['result'] = 1;
            return $return_data;
        } else {
            if (password_verify($password, $rows->row()->coPassword)) {
                $return_data['result'] = 0;
                $return_data['user_sn'] = $rows->row()->coSn;
                $return_data['level'] = $rows->row()->coLevel;
                $return_data['company_sn'] = $rows->row()->coCompanySn;
                $return_data['cert_level'] = $rows->row()->coCertLevel;
                return $return_data;
            } else {
                $return_data['result'] = 2;
                return $return_data;
            }
        }
    }

    public function check_password($password, $sn) {
        $query_string = "SELECT coPassword FROM tbUser WHERE coSn=?";

        $rows = $this->db->query($query_string, array($sn));

        if ($rows->num_rows() == 0) {
            return 1;
        } else {
            if (password_verify($password, $rows->row()->coPassword)) {
                return 0;
            } else {
                return 2;
            }
        }
    }

    public function is_company_user($company_sn, $user_sn) {
        $query_string = "SELECT coCompanySn FROM tbUser WHERE coSn=?";
        $rows = $this->db->query($query_string, array($user_sn));
        if ($rows->num_rows() == 0) {
            return false;
        }
        if ($company_sn == $rows->row()->coCompanySn) {
            return true;
        } else {
            return false;
        }
    }



    public function resetRegistrationId($token, $company_sn) {
        $query_string = "UPDATE tbUser SET coPushId='' WHERE coPushId=? AND coCompanySn<>?";
        $this->db->query($query_string, array($token, $company_sn));
    }
}
