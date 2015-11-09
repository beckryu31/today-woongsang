<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Class_cert
{
    const   USER_LEVEL_INVALID = 0;
    const   USER_LEVEL_NORMAL = 1;
    const   USER_LEVEL_ADMIN = 100;
    const   USER_LEVEL_SITE_ADMIN = 500;

    const   CERT_LEVEL_BEGIN = 1;
    const   CERT_LEVEL_END = 10;

    /**
     * 보안문제로 사이트에는 실제 레벨을 공개하지 않는다.
     * @param   int     $siteLevel      사이트에서 쓰이는 레벨: 0부터 시작해서 1씩 증가.
     * @return  int     DB에서 사용되는 레벨
     */
    public static function getDbLevel($siteLevel, $adminLevel=1) {
        switch ($siteLevel) {
            case 0:
            case 1:
                return Class_cert::USER_LEVEL_NORMAL;
            case 2:
                if ($adminLevel < Class_cert::USER_LEVEL_ADMIN) {
                    return Class_cert::USER_LEVEL_INVALID;
                }
                return Class_cert::USER_LEVEL_ADMIN;
            case 3:
                if ($adminLevel < Class_cert::USER_LEVEL_SITE_ADMIN) {
                    return Class_cert::USER_LEVEL_INVALID;
                }
                return Class_cert::USER_LEVEL_SITE_ADMIN;
            default:
                return Class_cert::USER_LEVEL_INVALID;
        }
    }

    public static function getSiteLevel($dbLevel) {
        switch ($dbLevel) {
            case Class_cert::USER_LEVEL_NORMAL:
                return 1;
            case Class_cert::USER_LEVEL_ADMIN:
                return 2;
            case Class_cert::USER_LEVEL_SITE_ADMIN:
                return 3;
            default:
                return 0;
        }
    }

}