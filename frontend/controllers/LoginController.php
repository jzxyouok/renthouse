<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Login;
use yii\helpers\Json;
use frontend\models\User;
use common\models\Utils;

class LoginController extends Controller
{
    public $layout = false;
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    // public function actionRegister()
    // {
    // 		$data=Yii::$app->request->rawBody;
    // 		$obj = json_decode($data);
    // 		$retstr=User::register($obj);
    // 		return json_encode($retstr);
    // }
    
    public function actionGetVerificationCode()
    {
            $data=Yii::$app->request->rawBody;
    		$obj = json_decode($data);
    		$retstr = Utils::GetVerificationCode($obj->phone);
			return json_encode($retstr);
    }
    
    public function actionVerifyVerificationCode()
    {
            Yii::error("Login:VerifyVerificationCode\n");
            $data=Yii::$app->request->rawBody;
    		$obj = json_decode($data);
    		$retstr = Utils::VerifyVerificationCode($obj->vcodeseq,$obj->vcode);
			return json_encode($retstr);
    }
    
    // public function actionLogin()
    // {
    // 		$data=Yii::$app->request->rawBody;
    // 		$obj = json_decode($data);
    //         $user=User::findUserByPhone($obj->phone);
    //         if(empty($user)){
    //     		$retstr['rscode']=1;
				// $retstr['error']='用户不存在';
				// return json_encode($retstr);
    //         }
    //         $retstr=$user->login($obj);
    //         return json_encode($retstr);
    // }



    // public function actionRegisterWeixin()
    // {
    //         $data=Yii::$app->request->rawBody;
    //         $obj = json_decode($data);
    //         $retstr=User::registerWeixin($obj);
    //         return json_encode($retstr);
    // }

}
