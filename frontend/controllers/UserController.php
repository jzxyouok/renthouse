<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use frontend\models\Supplyment;
use frontend\models\Requirement;
use frontend\models\WeiXinPic;

class UserController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex($userid)
    {
        header("Content-type:json/application;charset=utf-8");
        $model = new User();
    }

	public function actionGetUserInfo()
	{
		$data=Yii::$app->request->rawBody;
	 	$obj = json_decode($data);
	 	$suerinfo=User::getUserInfo($obj->userid);
	 	if(empty($suerinfo)){
				return 'null';
	 	}
		return json_encode($suerinfo);
	}
	
	public function actionGetUserByOpenid()
	{
		$data=Yii::$app->request->rawBody;
	 	$obj = json_decode($data);
	 	$suerinfo=User::getUserByOpenid($obj->openid);
	 	if(empty($suerinfo)){
				return 'null';
	 	}
		return json_encode($suerinfo);
	}
	
	public function actionModifyUserInfo()
	{
		$data=Yii::$app->request->rawBody;
	 	$obj = json_decode($data);
	 	$suerinfo=User::modifyUserInfo($obj);
		return json_encode($suerinfo);
	}
	
    public function actionGetAgentList()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $suerinfo=User::getAgentList($obj);
        return json_encode($suerinfo);
    }


    public function actionUpdateUserToAgent()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $suerinfo=User::UpdateUserToAgent($obj);
        return json_encode($suerinfo);
    }

    public function actionAddWxPic()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $res=WeiXinPic::AddWxPic($obj);
        return json_encode($res);
    }

}

?>
