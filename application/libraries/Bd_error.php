<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bd_error {
    const BD_ERR_SUCCESS = 0;
    const BD_ERR_INVALID_UUID = 1;
    const BD_ERR_INVALID_PHONE_NUM = 2;
    const BD_ERR_AUTH_SMS_FAILED = 3;
    const BD_ERR_AUTH_FAILED = 4;
    const BD_ERR_AUTH_TIME_OVER = 5;
    const BD_ERR_AUTH_INVALID_NUM = 6;
    const BD_ERR_AUTH_DUPLICATED = 7;
    const BD_ERR_INVALID_SHOP_NAME = 8;
    const BD_ERR_DB_INSERT_FAIL = 9;
    const BD_ERR_DB_DELETE_FAIL = 10;
    const BD_ERR_DB_SELECT_FAIL = 11;
    const BD_ERR_DB_UPDATE_FAIL = 12;
    const BD_ERR_USER_NONE = 13;
    const BD_ERR_SHOP_NONE = 14;
    const BD_ERR_USER_DUPLICATED = 15;
    const BD_ERR_FACTORY_DUPLICATED = 16;
    const BD_ERR_INVALID_USER = 17;
    const BD_ERR_INVALID_SHOP = 18;
// file upload
    const BD_ERR_UPLOAD_INVALID_PARAM = 19;
    const BD_ERR_UPLOAD_NO_FILE = 20;
    const BD_ERR_UPLOAD_SIZE_LIMIT = 21;
    const BD_ERR_UPLOAD_UNKNOWN = 22;
    const BD_ERR_UPLOAD_TOO_BIG = 23;
    const BD_ERR_UPLOAD_INVALID_FORMAT = 24;
    const BD_ERR_UPLOAD_MOVE_FAILED = 25;

// file download
    const BD_ERR_DOWNLOAD_INVALID_PARAM = 26;
    const BD_ERR_DOWNLOAD_UNKNOWN = 27;
    const BD_ERR_DOWNLOAD_NO_FILE = 28;
    const BD_ERR_DOWNLOAD_NO_ROW = 29;

    const BD_ERR_SMS_BAD_COVERAGE = 30;
    const BD_ERR_SMS_POWER_OFF = 31;
    const BD_ERR_SMS_BAD_FORMAT = 32;
    const BD_ERR_SMS_NO_CUSTOMER = 33;
    const BD_ERR_SMS_BAD_TRANS = 34;
    const BD_ERR_SMS_SPAM_REJECT = 35;
    const BD_ERR_SMS_MMS_NOT_SUPPORT = 36;
    const BD_ERR_SMS_ETC = 37;
    const BD_ERR_SMS_UNUSE_ID = 38;        // 사용불가 ID
    const BD_ERR_SMS_BAD_PASSWORD = 39;
    const BD_ERR_SMS_BAD_ID = 40;          // 존재하지 않는 ID
    const BD_ERR_SMS_INVALID_SENDER = 41;
    const BD_ERR_SMS_BAD_PHONE = 42;
    const BD_ERR_SMS_CANCEL = 43;
    const BD_ERR_SMS_INVALID_NUMBER = 44;  // 오류번호
    const BD_ERR_SMS_CUTTING = 45;
    const BD_ERR_SMS_DUPLICATED = 46;
    const BD_ERR_SMS_NO_ID = 47;
    const BD_ERR_SMS_NO_PASSWORD = 48;
    const BD_ERR_SMS_NO_NUMBER = 49;
    const BD_ERR_SMS_NO_CONTENT = 50;
    const BD_ERR_SHOP_CREATE = 51;
    const BD_ERR_JOIN_SHOP = 52;
    const BD_ERR_INVALID_PARAM = 53;
    const BD_ERR_INVALID_EMAIL = 54;
    const BD_ERR_INVALID_PASSWORD = 55;
    const BD_ERR_EMAIL_DUPLICATED = 56;
    const BD_ERR_INVALID_TAG_UUID = 57;
    const BD_ERR_TAG_MISMATCH = 58;
    const BD_ERR_RANK_MISMATCH = 59;
    const BD_ERR_PASSWORD_MISMATCH = 60;
    const BD_ERR_INVALID_COUPON_SN = 61;
    const BD_ERR_INVALID_USER_ID = 62;
    const BD_ERR_ID_DUPLICATED = 63;
    const BD_ERR_INVALID_VERSION =64;
    const BD_ERR_ID_REJECTED = 65;
    const BD_ERR_SESSION_END = 66;
    const BD_ERR_INVALID_COMPANY_NAME = 67;
    const BD_ERR_LOW_USER_LEVEL = 68;
    const BD_ERR_PASSWORD_TOO_SHORT = 69;
    const BD_ERR_INVALID_BOARD_TYPE = 70;
    const BD_BASIC_POINT = 100;


    static private $error_msg = array (
        Bd_error::BD_ERR_SUCCESS => array('success', '성공'),
        Bd_error::BD_ERR_INVALID_UUID => array('invalid uuid', '잘못된 uuid'),
        Bd_error::BD_ERR_INVALID_PHONE_NUM => array('invalid phone number', '잘못된 전화번호'),
        Bd_error::BD_ERR_AUTH_SMS_FAILED => array('sms failed', 'SMS 전송 오류'),
        Bd_error::BD_ERR_AUTH_FAILED => array('Authentication failed', '인증번호 틀림'),
        Bd_error::BD_ERR_AUTH_TIME_OVER => array('Time Over', '전송시간초과'),
        Bd_error::BD_ERR_AUTH_INVALID_NUM => array('Invalid Auth Number', '유효한 인증코드 아님'),
        Bd_error::BD_ERR_AUTH_DUPLICATED => array('duplicated request', '중복 인증 요청'),
        Bd_error::BD_ERR_INVALID_SHOP_NAME => array('invalid shop name', '잘못된 매장이름'),
        Bd_error::BD_ERR_DB_INSERT_FAIL => array('DB Insert Failed', 'DB Insert 오류'),
        Bd_error::BD_ERR_DB_DELETE_FAIL => array('DB Delete Failed', 'DB Delete 오류'),
        Bd_error::BD_ERR_DB_SELECT_FAIL => array('DB Select Failed', 'DB Select 오류'),
        Bd_error::BD_ERR_DB_UPDATE_FAIL => array('DB Update Failed', 'DB Update 오류'),
        Bd_error::BD_ERR_USER_NONE => array('User not exist', '사용자 없음'),
        Bd_error::BD_ERR_SHOP_NONE => array('Shop not exist', '매장 없음'),
        Bd_error::BD_ERR_USER_DUPLICATED => array('Duplicated User', '중복 사용자 발견'),
        Bd_error::BD_ERR_FACTORY_DUPLICATED => array('Duplicated Factory', '중복 공장 이미 있음'),
        Bd_error::BD_ERR_INVALID_USER => array('Invalid user param', '잘못된 사용자 변수'),
        Bd_error::BD_ERR_INVALID_SHOP => array('Invalid shop param', '잘못된 매장 변수'),
        Bd_error::BD_ERR_UPLOAD_INVALID_PARAM => array('Invalid upload param', '잘못된 업로드 정보'),
        Bd_error::BD_ERR_UPLOAD_NO_FILE => array('upload empty contents', '업로드 파일이 없음'),
        Bd_error::BD_ERR_UPLOAD_SIZE_LIMIT => array('over upload size limit', '업로드 정보에 너무 큰 크기'),
        Bd_error::BD_ERR_UPLOAD_UNKNOWN => array('Unknown Upload error', '알수 없는 업로드 오류'),
        Bd_error::BD_ERR_UPLOAD_TOO_BIG => array('Upload too big size', '너무 큰 파일을 업로드'),
        Bd_error::BD_ERR_UPLOAD_INVALID_FORMAT => array('Invalid file format', '허용되는 파일형식이 아님'),
        Bd_error::BD_ERR_UPLOAD_MOVE_FAILED => array('Move upploaded file failed', '업로드한 파일 저장에 실패'),
        Bd_error::BD_ERR_DOWNLOAD_INVALID_PARAM => array('Request invalid file', '잘못된 파일 요청'),
        Bd_error::BD_ERR_DOWNLOAD_UNKNOWN => array('Unknown download error', '알 수 없는 다운로드 오류'),
        Bd_error::BD_ERR_DOWNLOAD_NO_FILE => array('Requested file not exists', '요청한 파일이 없습니다'),
        Bd_error::BD_ERR_DOWNLOAD_NO_ROW => array('shop banner row count is 0', 'DB에 해당 값이 없습니다'),
        Bd_error::BD_ERR_SMS_BAD_COVERAGE => array('Bad Coverage','음영지역'),
        Bd_error::BD_ERR_SMS_POWER_OFF => array('POWER OFF','전원꺼짐'),
        Bd_error::BD_ERR_SMS_BAD_FORMAT => array('BAD NUMBER FORMAT','잘못된 휴대폰 번호 형식'),
        Bd_error::BD_ERR_SMS_NO_CUSTOMER => array('INVALID PHONE NUMBER','비가입자 및 결번'),
        Bd_error::BD_ERR_SMS_BAD_TRANS => array('NUMBER TRANSFER ERROR','번호이동 오류'),
        Bd_error::BD_ERR_SMS_SPAM_REJECT => array('T-SPAMMED','이통사 수긴거부(스팸)'),
        Bd_error::BD_ERR_SMS_MMS_NOT_SUPPORT => array('MMS NOT SUPPORTED','MMS 미지원'),
        Bd_error::BD_ERR_SMS_ETC => array('ETC','기타 사유'),
        Bd_error::BD_ERR_SMS_UNUSE_ID => array('EXPIRED ID','사용불가 ID'),        // 사용불가 ID
        Bd_error::BD_ERR_SMS_BAD_PASSWORD => array('BAD PASSWORD','패스워드 오류'),
        Bd_error::BD_ERR_SMS_BAD_ID => array('INVALID ID','존재하지 않는 ID'),          // 존재하지 않는 ID
        Bd_error::BD_ERR_SMS_INVALID_SENDER => array('INVALID SENDER NUMBER','발송번호 정보 오류'),
        Bd_error::BD_ERR_SMS_BAD_PHONE => array('INVALID ADDRESS','주소 정보 생성 오류'),
        Bd_error::BD_ERR_SMS_CANCEL => array('CANCELED','전송취소'),
        Bd_error::BD_ERR_SMS_INVALID_NUMBER => array('INVALID NUMBER','오류번호'),  // 오류번호
        Bd_error::BD_ERR_SMS_CUTTING => array('M-SPAMMED','신안정보 스팸차단'),
        Bd_error::BD_ERR_SMS_DUPLICATED => array('DUPLICATED NUMBER','번호 중복'),
        Bd_error::BD_ERR_SMS_NO_ID => array('ID PARAM ERROR','아이디 누락'),
        Bd_error::BD_ERR_SMS_NO_PASSWORD => array('PASSWORD PARAM ERROR','패스워드 누락'),
        Bd_error::BD_ERR_SMS_NO_NUMBER => array('NUMBER PARAM ERROR','휴대폰 번호 누락'),
        Bd_error::BD_ERR_SMS_NO_CONTENT => array('CONTENT PARAM ERROR','문자내용 누락'),
        Bd_error::BD_ERR_SHOP_CREATE => array('CANNOT CREATE SHOP DATA', '점포 생성을 하지 못했습니다.'),
        Bd_error::BD_ERR_JOIN_SHOP => array('Cannot match shop and manager', '점포와 사용자 매치 실패'),
        Bd_error::BD_ERR_INVALID_EMAIL =>array('Invalid Email Param', '이메일이 전달되지 않았습니다.'),
        Bd_error::BD_ERR_INVALID_PASSWORD =>array('Invalid Password Param', '암호가 전달되지 않았습니다.'),
        Bd_error::BD_ERR_EMAIL_DUPLICATED => array('Duplicated email address', '이미 존재하는 이메일입니다.'),
        Bd_error::BD_ERR_INVALID_TAG_UUID => array('Invalid TAG uuid', '잘못된 TAG uuid입니다.'),
        Bd_error::BD_ERR_TAG_MISMATCH => array('Tag id mismatched', 'TAG ID가 맞지 않습니다.'),
        Bd_error::BD_ERR_RANK_MISMATCH => array('Rank mismatched', '쿠폰 등수가 맞지 않습니다.'),
        Bd_error::BD_ERR_PASSWORD_MISMATCH => array('Password is incorrect', '잘못된 암호입니다.'),
        Bd_error::BD_ERR_INVALID_COUPON_SN => array('Coupon SN is incorrect', '잘못된 쿠폰번호입니다.'),
        Bd_error::BD_ERR_INVALID_USER_ID => array('Invalid User Id', '문제가 있는 사용자 아이디입니다'),
        Bd_error::BD_ERR_ID_DUPLICATED => array('Duplicated User ID', '이미 존재하는 아이디 입니다.'),
        Bd_error::BD_ERR_INVALID_VERSION => array('Invalid Version No.', '앱 버전이 잘못되었습니다.'),
        Bd_error::BD_ERR_ID_REJECTED => array('Duplicated User ID', '이미 존재하는 아이디입니다.'),
        Bd_error::BD_ERR_SESSION_END => array('Session expired', '종료된 세션값입니다.'),
        Bd_error::BD_ERR_INVALID_COMPANY_NAME => array('Invalid Company Name', '회사 이름이 잘못되었습니다.'),
        Bd_error::BD_ERR_LOW_USER_LEVEL => array('User level is low', '수정 권한이 없습니다'),
        Bd_error::BD_ERR_PASSWORD_TOO_SHORT => array('Password length is too short.', '암호는 최소 4자 이상이어야 합니다.'),
        Bd_error::BD_ERR_INVALID_BOARD_TYPE => array('Invalid Board Type.', '사용하지 않는 게시판입니다'),

        Bd_error::BD_ERR_INVALID_PARAM => array('Invalid Params', '잘못된 인수가 전달되었습니다.')
    );

    protected $nation;
    protected $CI;
    protected $language_array = array(
        'english' => 0,
        'korean' => 1
    );

    public function __construct() {
        $this->CI =& get_instance();
        $this->nation = 1;
    }
    /**
     * @param int $nation_code 메시지 언어 선택
     * 1: 영어
     * 2: 한국어
     */
    public function set_nation($nation_code = 0) {
        if ($nation_code > 0) {
            $this->nation = $nation_code - 1;
        }
    }

    /**
     * @param $lang     string  선택언어: language 파일에 정의되어야 함.
     */
    public function set_nation_with_language($lang) {
        if (array_key_exists($lang, $this->language_array)) {
            $this->nation = $this->language_array[$lang];
        }
    }

    /**
     * @param   int     $error_code
     * @param   int     $nation_code
     * @return  string  해당 언어의 에러 메시지
     */
    public function get_error_msg($error_code, $nation_code = 0) {
        if (!array_key_exists($error_code, Bd_error::$error_msg)) {
            return "";
        }
        if ($nation_code == 0) {
            $nation_code = $this->nation;
        } else {
            $nation_code = $nation_code - 1;
        }
        return Bd_error::$error_msg[$error_code][$nation_code];
    }

    /**
     * @param   int     $error_code
     * @param   int     $nation
     * @return  string  json으로 인코딩된 에러 코드와 메시지
     */
    public function make_json_error_msg($error_code, $nation = 0) {
        $err = array();
        if (!array_key_exists($error_code, Bd_error::$error_msg)) {
            $err['return_code'] = $error_code;
            $err['msg'] = "";
        } else {
            $err['return_code'] = $error_code;
            $err['msg'] = $this->get_error_msg($error_code, $nation);
        }
        return json_encode($err);
    }

    /**
     * @param $key
     * @param $content
     *
     * key, value 값을 ehco로 출력한다.
     */
    static public function lined_echo($key, $content) {
        echo "<p>" . $key . " = [" . $content . "]</p><br />";
    }
}