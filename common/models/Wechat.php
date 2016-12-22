<?php
namespace common\models;
use Yii;
use yii\helpers\Json;
use common\helpers\Curl;
use yii\db\ActiveRecord;
use common\helpers\Functions;

class Wechat extends ActiveRecord
{

        static public $appid = "wxa6712579de2a8a75";
        static public $appsecret = "2dd7f33047c024d82e768cf3c1a3bb7d";
        static private $token;
        static public $mchid = '1230055202';//商户号，涉及到支付才会用
        static public $key = '507e7d15ef798934454cf7b0513e979d';//支付相关key

        public static function tableName()
	    {
	        return 't_user';
	    }

        // 微信认证
        public function index(){
            $nonce     = $_GET['nonce'];
            $token     = 'iqhuzb1407225296';
            $timestamp = $_GET['timestamp'];
            $echostr   = $_GET['echostr'];
            $signature = $_GET['signature'];
            $array = array();
            $array = array($nonce, $timestamp, $token);
            sort($array);
            $str = sha1( implode( $array ) );
            if( $str  == $signature && $echostr ){
                echo  $echostr;
                exit;
            }
        }
        // 接收事件推送并回复
        public function reponseMsg(){
            $postStr = file_get_contents("php://input");
            if (!empty($postStr)){
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

                if( strtolower( $postObj->MsgType) == 'event'){
                    //关注 subscribe 事件
                    if( strtolower($postObj->Event == 'subscribe') ){
                        //回复用户消息(纯文本格式) 
                        $toUser   = $postObj->FromUserName;
                        $fromUser = $postObj->ToUserName;
                        $time     = time();
                        $msgType  =  'text';
                        $content  = '欢迎关注芝麻找房，这是一个服务买家的精准找房平台

点击下方菜单，开启您的找房之旅吧。

（1）点击<a href="http://www.lovespace.cc/weixin/HomePage/RequirementList.html">【我要找房】</a>看看别人想买什么房子
（2）点击<a href="http://www.lovespace.cc/weixin/publish.html">【发布需求】</a>我想要的我做主

如您有任何使用问题，可直接在公众号后台留言，我们会及时查看并予以解决。';
                        $template = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                    </xml>";
                        $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                        echo $info;

                    }
                }
            
                //用户发送tuwen1关键字的时候，回复一个单图文
                if( strtolower($postObj->MsgType) == 'text' ){
                    $toUser = $postObj->FromUserName;
                    $fromUser = $postObj->ToUserName;
                    switch( trim($postObj->Content) ){
                        // case 'tuwen':
                        //     echo "";
                        //     exit;
                        //     $type = 'news';
                        //     $content = array(
                        //                     array(
                        //                         'title'=>'imooc',
                        //                         'description'=>"imooc is very cool",
                        //                         'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                        //                         'url'=>'http://www.imooc.com',
                        //                     ),
                        //                     array(
                        //                         'title'=>'hao123',
                        //                         'description'=>"hao123 is very cool",
                        //                         'picUrl'=>'https://www.baidu.com/img/bdlogo.png',
                        //                         'url'=>'http://www.hao123.com',
                        //                     ),
                        //                     array(
                        //                         'title'=>'qq',
                        //                         'description'=>"qq is very cool",
                        //                         'picUrl'=>'http://www.imooc.com/static/img/common/logo.png',
                        //                         'url'=>'http://www.qq.com',
                        //                     ),
                        //                 );
                        // break;
                        case 'door':
                            $type = 'text';
                            $content = 'http://www.lovespace.cc/?r=door/scope&view=index';
                        break;
                        case '领取':
                            $type = 'text';
                            $content = $toUser;
                        break;
                        default:
                            $type = 'transfer_customer_service';
                        break;
                    }
                    switch($type){
                        case 'news':
                           $template = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <ArticleCount>".count($content)."</ArticleCount>
                                    <Articles>";
                            foreach($content as $k=>$v){
                                $template .="<item>
                                                <Title><![CDATA[".$v['title']."]]></Title> 
                                                <Description><![CDATA[".$v['description']."]]></Description>
                                                <PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
                                                <Url><![CDATA[".$v['url']."]]></Url>
                                            </item>";
                            }
                            $template .="</Articles></xml>";
                            echo sprintf($template, $toUser, $fromUser, time(), $type);
                        break;
                        case 'text':
                            $template = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                    </xml>";
                            echo sprintf($template, $toUser, $fromUser, time(), $type, $content);
                        break;
                        case 'transfer_customer_service':
                            $template = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    </xml>";
                            echo sprintf($template, $toUser, $fromUser, time(), $type);
                         break;
                    }
                }
            }
                
        }

        // 获取并缓存 accesstoken
        static function getWxAccessToken(){
            if(Yii::$app->cache->get('wxToken')){
                static::$token = Yii::$app->cache->get('wxToken');
                return static::$token;
            }
            //1.请求url地址
            $appid = static::$appid;
            $appsecret = static::$appsecret;

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
            $res = Curl::http_curl($url);
            Yii::$app->cache->set('wxToken', $res['access_token'], 5400);
            static::$token = $res['access_token'];
            return static::$token;
        }
        // 微信获取权限跳转链接
        function getScope($index,$scope,$view,$p = ''){
            //echo static::$token;
            if($p){
                $p = unserialize($p);
                $ps='';
                foreach ($p as $key => $value) {
                    $ps.='&'.$key.'='.$value;
                }
            }
            
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".static::$appid."&redirect_uri=".urlencode($index.$ps)."&response_type=code&scope=snsapi_".$scope."&state=".$view."#wechat_redirect";
            //https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect 
            return $url;
        }
        // 微信获取权限 return accesstoken openid 等
        function getScopeToken($code){
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".static::$appid."&secret=".static::$appsecret."&code=".$code."&grant_type=authorization_code";
                $res = Curl::http_curl($url);
                return $res;
        }
        // 获取微信服务器IP
        function getWxServerIp(){
            $accessToken = $this->getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accessToken;
            $res = Curl::http_curl($url);
            return $res;
        }
        // 获取微信openid关联用户信息
        function getInfo($code){
            $tokenArray = $this -> getScopeToken($code);
            Yii::error("Wechat:getInfo  tokenArray is ".print_r($tokenArray,1)."\n");
            // var_dump($tokenArray);
            $openid = $tokenArray['openid'];
            if(!$openid){
                return array();
            }

            $token = $tokenArray['access_token'];
            if($zminfo = static::find()->where(['wxopenid'=>$openid])->asArray()->one()){
                $connection = Yii::$app->db;
                $sql = sprintf("UPDATE t_user set logintime= '%s',totaltimes = totaltimes+1 where  id = %s;",date('Y-m-d H:i:s',time()),$zminfo['id']);
                $command = $connection->createCommand($sql);
                $command->execute();
                
                return $zminfo;
            }else{
                $url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid."&lang=zh_CN";
                Yii::error("Wechat::getInfo url is :$url\n");
                $wxinfo = Curl::http_curl($url);
                Yii::error("Wechat::getInfo wxinfo is : ".print_r($wxinfo,1)."\n");
                if(!$wxinfo['openid']){
                    return [];
                }
                $obj = new Wechat();
                $obj->nickname = $wxinfo['nickname'];
                $obj->sex = $wxinfo['sex'];
                $obj->picture = $wxinfo['headimgurl'];
                $obj->city = $wxinfo['city'];
                $obj->srcflag = 3;
                $obj->createtime = $obj->logintime = date('Y-m-d H:i:s',time());
                $obj->wxopenid = $wxinfo['openid'];
                $obj->phone = '';
                $obj->identitycard='';
                $obj->tags='';
                $obj->email='';
                $obj->realname='';
                // $obj->tags='';
                // $obj->wxunionid = $wxinfo['unionid'];
                $obj->validflag = 1;
                $obj->agentflag = 1;
                $obj->save();
                return $obj;
            }
            // return $info;
        }

        /***************************只能抓取关注用户的信息，用以判断是否为订阅用户***********************/
        public function getSubscribeInfo($openid){
            $token = $model::getWxAccessToken();  
            $info = $model::http_curl("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$token."&openid=".$openid."&lang=zh_CN");
            return $info;
        }

        // 获取jssdk 需求array
        public function getSignPackage($signUrl) {
            $jsapiTicket = $this->getJsApiTicket();
            // 注意 URL 一定要动态获取，不能 hardcode.
            $url = $signUrl;

            $timestamp = time();
            $nonceStr = $this->createNonceStr();

            // 这里参数的顺序要按照 key 值 ASCII 码升序排序
            $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

            $signature = sha1($string);

            $signPackage = array(
                "appId"     => static::$appid,
                "nonceStr"  => $nonceStr,
                "timestamp" => $timestamp,
                "url"       => $url,
                "signature" => $signature,
                "rawString" => $string,
                //"ticket"    => $jsapiTicket,
                //"access"    => static::$token
            );
            return $signPackage; 
        }
    //获取随机字符串
        public function createNonceStr($length = 16) {
          // $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          // $str = "";
          // for ($i = 0; $i < $length; $i++) {
          //   $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
          // }
          // return $str;
            $chars = array( 
                "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                "a", "b", "c", "d", "e", "f", "g", "h", "i", "j",
                "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", 
                "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", 
                "E", "F", "G", "H", "I", "J", "K", "L", "M", "N",
                "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X",
                "Y", "Z", 
            );
            $str = "";
            for ($i = 0; $i < $length; $i++) {
                $str .= $chars[mt_rand(0, 61)];
            }
            return $str;
        }
        // 获取jssdk
        private function getJsApiTicket() {
            if(Yii::$app->cache->get('wxToken')){
                $ticket = Yii::$app->cache->get('jsTicket');
                return $ticket;
            }
            $accessToken = static::getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken&type=wx_card";
            $res = Curl::http_curl($url);
            $ticket = $res['ticket'];
            if ($ticket) {
              Yii::$app->cache->set('jsTicket', $ticket, 7000);
            }
            return $ticket;
        }

        // 创建微信公众号菜单
        public function createMenu($data = array())
        {
            $token = static::getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
            $res = Curl::http_curl($url,true,$data);
            return $res;
        }
        // 发送模板消息
        public function sendTemplateMessage($data){
            // Yii::trace("sendTemplateMessageData is ".print_r($data)."\n");
            $token = static::getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$token;
            $res = Curl::http_curl($url,true,$data);
            return $res;
        }
        //上传永久素材
        public function uploadFM($data)
        {
            $token = static::getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$token;
            $res = Curl::http_curl($url,true,$data);
            return $res;
        }
        //获取素材列表
        public function getFMList($data){
            $token = static::getWxAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=".$token;
            $res = Curl::http_curl($url,true,$data);
            return $res;
        }
        // 获取sign
        public function createSign($parameter){
            ksort($parameter);
            // var_dump($parameter);

            foreach ($parameter as $k => $v)
            {
                if($k != "sign" && $v != "" && !is_array($v)){
                    $buff .= $k . "=" . $v . "&";
                }
            }
            
            $buff = trim($buff, "&");
            // var_dump($buff);

            //签名步骤二：在string后加入KEY
            $string = $buff . "&key=".static::$key;
            //签名步骤三：MD5加密
            $string = md5($string);
            //签名步骤四：所有字符转为大写
            return $sign = strtoupper($string);
        }
        // 微信支付 微信端创建订单 返回jsapi所用参数
        public function wepay_setorder($openid,$body,$attach,$price,$backup,$tags,$type,$out_trade_no = null){
            if(!$out_trade_no){
                $out_trade_no = static::$mchid.date("YmdHis");
            }
            $parameter['appid'] = static::$appid;
            $parameter['mch_id'] = static::$mchid;
            $parameter['openid'] = $openid;
            $parameter['spbill_create_ip'] = Functions::getUserIp();
            $parameter['body'] = $body;
            $parameter['attach'] = $attach;
            $parameter['out_trade_no'] = $out_trade_no;
            $parameter['total_fee'] = $price;  //价格
            $parameter['time_start'] = date("YmdHis");
            $parameter['time_expire'] = date("YmdHis", time() + 600);
            $parameter['goods_tag'] = $tags;
            $parameter['notify_url'] = $backup;
            $parameter['trade_type'] = $type;
            // $parameter['product_id'] = 1000000001;
            $parameter['nonce_str'] = $this->createNonceStr(32);
            // var_dump($parameter);
            $sign = $this->createSign($parameter);

            $parameter['sign'] = $sign;

            $xml = "<xml>";
            foreach ($parameter as $key=>$val)
            {
                if (is_numeric($val)){
                    $xml.="<".$key.">".$val."</".$key.">";
                }else{
                    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                }
            }
            $xml.="</xml>";

            $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
            $response = Curl::http_curl($url,1,$xml,'default','xml');
            // var_dump($response);
            return $response = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        }
}
