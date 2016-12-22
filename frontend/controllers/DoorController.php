<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Wechat;
use yii\helpers\Json;

class DoorController extends Controller
{
	public $enableCsrfValidation = false;
	public function actionIndex()
	{
		$model = new Wechat();
		$model -> reponseMsg();
	}

	// 微信认证
    public function actionCheck(){
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

	public function actionScope($view){
		$p = Yii::$app->request->get();
		unset($p['r']);
		unset($p['view']);
		$p = serialize($p);
		$model = new Wechat();
		$url = $model -> getScope("http://www.lovespace.cc/?r=door/redirect","userinfo",$view,$p);
		header('Location:'.$url);
	}

	public function actionRedirect(){
		// header("Content-type:text/json;charset=utf-8");
		$request = Yii::$app->request->get();
		$code = $request['code'];
		$state = $request['state'];
		Yii::error("code is ".print_r($code,1)."\n");
		Yii::error("state is ".print_r($state,1)."\n");
		unset($request['r']);
		unset($request['code']);
		unset($request['state']);
		$p='';
		$i=0;
		foreach ($request as $key => $value) {
			if($i==0){
				$p.='?'.$key.'='.$value;
			}else{
				$p.='&'.$key.'='.$value;
			}
			$i++;
		}
		$model = new Wechat();
		$infoArray = $model->getInfo($code);
		Yii::error("DoorController:actionRedirect  infoArray is ".print_r($infoArray,1)."\n");

		$openid = $infoArray['wxopenid'];
		$phone = $infoArray['phone'];
		if(!$infoArray){
			header('Location:http://www.lovespace.cc/weixin/'.$state.'.html'.$p);
		}
		$refer = $state;
		if(!$phone){
			$state = "Mine/reg";
		}
		Yii::error("infoArray is ".print_r($infoArray,1)."\n");
		$infoJson = Json::encode($infoArray);
		$infoJson = str_replace("wxopenid", "openid", $infoJson);
		Yii::error("infoJson is ".print_r($infoJson,1)."\n");
		$strs = "localStorage.setItem('zufangLoginUserInfo','".$infoJson."');";
		$strs.= "localStorage.setItem('openid','".$openid."');";
		$strs.= "localStorage.setItem('refer','".$refer."');";
		
		header("Content-type:text/html;charset=utf-8");

		echo "<script type='text/javascript' charset='utf-8'>";


		echo "if(window.localStorage){
				 //alert('This browser supports localStorage');
				}else{
				 alert('This browser does NOT support localStorage');
			 }";
		echo $strs;
		echo "window.location.href='http://www.lovespace.cc/weixin/".$state.".html".$p."';";
		// echo "&lt;/script&gt;";
		echo "</script>";
		Yii::error("p is ".print_r($p,1)."\n");
	}

	public function actionGetJssdk($url){
		$model = new Wechat();
		$res = $model -> getSignPackage($url);
		return Json::encode($res);
	}
	public function actionFakeLogin(){

		$userid = intval(Yii::$app->request->post('userid'));
		
		if($userid && ($zminfo = \frontend\models\User::find()->where(['t_user.id'=>$userid])->asArray()->one())){
                $connection = Yii::$app->db;
                $sql = sprintf("UPDATE t_user set logintime= '%s' where  id = %s;",date('Y-m-d H:i:s',time()),$zminfo['id']);
                $command = $connection->createCommand($sql);
                $command->execute();
        }
        $infoJson = Json::encode($zminfo);

		// var_dump($infoJson);

		$strs = "localStorage.setItem('zufangLoginUserInfo','".$infoJson."');";
		$strs.= "localStorage.setItem('openid','".$openid."');";

		header("Content-type:text/html;charset=utf-8");

		echo "<script type='text/javascript' charset='utf-8'>";

		// echo "&lt;script type='text/javascript' charset='utf-8'&gt;";

		echo "if(window.localStorage){
				 //alert('This browser supports localStorage');
				}else{
				 alert('This browser does NOT support localStorage');
			 }";
		echo $strs;
		echo "window.location.href='http://www.lovespace.cc/weixin/HomePage/RequirementList.html';";
		// echo "&lt;/script&gt;";
		echo "</script>";
	}
}
