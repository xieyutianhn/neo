<?php
/**
 * Created by PhpStorm.
 * User: NeoXie
 * Date: 2016/11/4
 * Time: 11:56
 */
namespace Neo;

use Psy\Exception\TypeErrorException;

class Helper
{
    static public function static_path($config_name, $ext = ".php")
    {
        if (strstr(".php", $config_name)) $ext = "";
        return require_once dirname(__FILE__) . "/config/$config_name" . $ext;
    }

    static public function curl($url,$data = array(),$type = 0)
    {
        if(!is_array($data)){
            throw new TypeErrorException("must be array");
        }
        $ch = curl_init();

        curl_setopt($ch,CURLOPT_HEADER,0);
        if($type == 1){
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        }else{
            $url = $url."?".http_build_query($data);
        }
        curl_setopt($ch,CURLOPT_URL,$url);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

}