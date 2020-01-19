<?php
namespace BaiduT\BaiduTran;

class Translate{

    public $salt = "";

    public $appId = "";

    public $key = "";

    public $arg = array();

    public $url = "http://api.fanyi.baidu.com/api/trans/vip/translate";

    /**
     * construct
     */
    public function __construct($appId,$key){

        $this->salt = rand(10000,99999);

        $this->arg['salt'] = $this->salt;

        $this->arg['appid'] = $appId;

        $this->appId = $appId;

        $this->key = $key;

        $this->arg['action'] = "0";
        
    }
    /**
     * Translate
     */
    public function server($q,$to,$from = "auto",$tts = "1",$dict = "1"){

        $this->arg['sign'] = $this->getSign($q);

        $this->arg['tts'] = $tts;

        $this->arg['dict'] = $dict;

        $this->arg['q'] = $q;

        $this->arg['from'] = $from;

        $this->arg['to'] = $to;

        $post = $this->convert($this->arg);

        $ret = $this->postCurl($this->url,$post);

        $retArr = json_decode($ret,true);

        return $retArr;

    }
    /**
     * sign
     */
    public function getSign($q){

        $str = $this->appId.$this->strToUtf8($q).$this->salt.$this->key;

        $ret = md5($str);

        return $ret;
    }
    /**
     * utf8
     */
    public function strToUtf8($str){

        $encode = mb_detect_encoding($str, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));

        if($encode == 'UTF-8'){

            return $str;

        }else{

            return mb_convert_encoding($str, 'UTF-8', $encode);

        }
    }
    /**
	 * curl 模拟post请求
	 */
	public static function postCurl($path,$postData,$ssl = "1"){
        $header[] = "Content-Type:application/x-www-form-urlencoded";
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $path);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($curl, CURLOPT_HTTPHEADER,$header );
		if($ssl == "0"){
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		}
		$data = curl_exec($curl);
	    curl_close($curl);
	    return $data;
    }
    /**
     *convert
     */
    public function convert(&$args){
        $data = '';
        if (is_array($args))
        {
            foreach ($args as $key=>$val)
            {
                if (is_array($val))
                {
                    foreach ($val as $k=>$v)
                    {
                        $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                    }
                }
                else
                {
                    $data .="$key=".rawurlencode($val)."&";
                }
            }
            return trim($data, "&");
        }
        return $args;
    }
}
?>