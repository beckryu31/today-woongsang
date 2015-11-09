<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('b2d_make_temp_password'))
{
    function b2d_make_temp_password($length = 12) {
        $pass_string = "23456789abcdefghkmnpqrstuvwxyzABCDEFGHJKMNPRSTUVWXYZ";
        $set_length = strlen($pass_string) - 1;

        if ($length < 4) {
            $length = 12;
        }
        $temp_password = "";
        for ($i = 0; $i < $length; $i++) {
            $temp_password = $temp_password . $pass_string[rand(0, $set_length)];
        }

        return $temp_password;
    }
}

if ( ! function_exists('b2d_check_uuid'))
{
    /**
     * 적절한 uuid 문자열인지를 문자열 길이로 테스트 : 8-4-4-4-12
     * @param string $uuid
     * @return bool
     */
    function b2d_check_uuid($uuid = '')
    {
        $result = preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $uuid);
        if ($result == 0) {
            return false;
        }
        return true;
    }
}

if ( ! function_exists('b2d_encrypt_password'))
{
    /**
     * 적절한 uuid 문자열인지를 문자열 길이로 테스트 : 8-4-4-4-12
     * @param string $uuid
     * @return bool
     */
    function b2d_encrypt_password($password)
    {

    }
}

if ( ! function_exists('b2d_check_phone_num'))
{
    function b2d_check_phone_num($phone = '') {
        // 너무 짧은 번호는 틀렸다.
        if (strlen($phone) < 10) {
            return false;
        }
        //  첫글자가
        //$result = preg_match('/^[+0-9]{1}[0-9]*}$/', $phone);
        $result = preg_match('/^[0-9]{1}[0-9]*$/', $phone);
        if ($result == 0) {
            return false;
        }
        return true;
    }
}

if ( ! function_exists('b2d_make_auth_num')) {
    /**
     * 인증에 사용할 4자리 번호를 생성. 모두 달라야한다.
     * @return string
     */
    function b2d_make_auth_num() {
        $num1 = rand(1, 9);
        do {
            $num2 = rand(0, 9);
        } while ($num1 == $num2);

        do {
            $num3 = rand(0, 9);
        } while ($num1 == $num3 || $num2 == $num3);

        do {
            $num4 = rand(0, 9);
        } while ($num1 == $num4 || $num2 == $num4 || $num3 == $num4);

        $result_num = $num1 * 1000 + $num2 * 100 + $num3 * 10 + $num4;
        $result = sprintf("%d", $result_num);

        return $result;
    }
}

if ( ! function_exists('b2d_check_auth_num'))
{
    function b2d_check_auth_num($num = '') {
        // 너무 짧은 번호는 틀렸다.
        if (strlen($num) < 4) {
            return false;
        }
        //  첫글자가
        //$result = preg_match('/^[+0-9]{1}[0-9]*}$/', $phone);
        $result = preg_match('/^[0-9]{4}$/', $num);
        if ($result == 0) {
            return false;
        }
        return true;
    }
}

/*
if ( ! function_exists('b2d_request_sms')) {
    function b2d_request_sms($phone, $auth_num, $index_code) {
        $utf_string = "[" . $auth_num . "] 내단골에서 보낸 인증번호입니다.";
        $content_string = iconv("UTF-8", "EUC-KR", $utf_string);

        $data = array(
            'authKey' => SMS_AUTH_KEY,
            'cpnumber' => $phone,
            'sendnumber' => SMS_SEND_NUMBER,
            'content' => $content_string,
            'index_code' => $index_code,
            'return_url' => SMS_RETURN_URL
        );
        $url = SMS_URL . SMS_PATH;
//        $post_data = 'authKey=' . SMS_AUTH_KEY . '&cpnumber=' . $phone . '&sendnumber=' . SMS_SEND_NUMBER . '&content=' . $auth_num . '&index_code=' . $index_code . '&return_url=' . SMS_RETURN_URL;
//        echo $post_data . "\n";
//        echo var_dump($data);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        try {
            $result = @file_get_contents($url, false, $context);
        } catch (Exception $e) {
            return false;
        }

        $result_array = json_decode($result, true);

        if ($result_array['resultCode'] == 200) {
            return true;
        } else {
            return false;
        }
    }
}
*/
if ( ! function_exists('b2d_upload_check')) {
    function b2d_upload_check($upload_info, $max_size = 500000) {
        if (!isset($upload_info['error']) || is_array($upload_info['error'])) {
            return Bd_error::BD_ERR_UPLOAD_INVALID_PARAM;
        }
        // Check $_FILES['userfile']['error'] value.
        switch ($upload_info['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return Bd_error::BD_ERR_UPLOAD_NO_FILE;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return Bd_error::BD_ERR_UPLOAD_SIZE_LIMIT;
            default:
                return Bd_error::BD_ERR_UPLOAD_UNKNOWN;
        }

        // You should also check filesize here.
        if ($upload_info['size'] > $max_size) {
            return Bd_error::BD_ERR_UPLOAD_TOO_BIG;
        }

        // DO NOT TRUST $_FILES['mime'] VALUE !!
        // Check MIME Type by yourself.
        // 문제가 있을 수 있어서 일단 제거함.
        /*
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search($finfo->file($upload_info['tmp_name']),
                array(
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ),
                true
            )) {
            return BD_ERR_UPLOAD_INVLID_FORMAT;
        }
        */

        return Bd_error::BD_ERR_SUCCESS;
    }
}