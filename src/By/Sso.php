<?php
/**
 * Created by PhpStorm.
 * User: NeoXie
 * Date: 2016/11/4
 * Time: 10:02
 */

namespace Neo\By;

use Illuminate\Support\Facades\Cookie;
use Neo\Helper;
use Symfony\Component\HttpFoundation\Tests\CookieTest;

class Sso
{

    public function __construct()
    {
        $config = \Neo\Helper::static_path("by");
        $this->appid = $config['appid'];
        $this->token = $config['token'];
        $this->request_url = $config['sso_url'] . $this->appid;
        $this->cookie_save_time = $config['cookie_save_time'];
        $this->priv_url = $config['priv_url'];
    }


    public function setCookie(){
        if(isset($_REQUEST['admin_uid']) && isset($_REQUEST['admin_key'])){
            $this->admin_uid = $_REQUEST['admin_uid'];
            $this->admin_key = $_REQUEST['admin_key'];
            $uid_cookie = cookie("admin_uid",$_REQUEST['admin_uid'],$this->cookie_save_time);
            $key_cookie = cookie("admin_key",$_REQUEST['admin_key'],$this->cookie_save_time);
            $this->sso_cookies =  array($uid_cookie,$key_cookie);
            return true;
        }
         header("location:".$this->request_url);
    }

    public function checkCookie(){
        if(Cookie::get('admin_uid',false) &&  Cookie::get('admin_key',false)){
            $this->admin_uid = Cookie::get('admin_uid',false);
            $this->admin_key = Cookie::get('admin_key',false);
            return true;
        }
       $this->setCookie();
    }

    public function getUserInfo(){
        $params = array('do'=>'getInfo','uid' => $this->admin_uid,'key' => $this->admin_key,'appid' => $this->appid);
        $data = Helper::curl($this->priv_url,$params);
        return json_decode($data,true);
    }

    public function getPriv(){
        $params = array('do'=>'getPriv','uid' => $this->admin_uid,'key' => $this->admin_key,'appid' => $this->appid);
        $data = Helper::curl($this->priv_url,$params);
        return json_decode($data,true);
    }

    public function getCookies(){
        if(isset($this->sso_cookies)){
            return $this->sso_cookies;
        }
    }

}