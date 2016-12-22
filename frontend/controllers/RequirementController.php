<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Requirement;


class RequirementController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex($userid)
    {
        header("Content-type:json/application;charset=utf-8");
        $model = new User();
    }

	public function actionAddRequirement()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Requirement::addRequirement($obj);
        return json_encode($retstr);
    }

    public function actionGetRequirement()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Requirement::getRequirement($obj);
        return json_encode($retstr);
    }

    public function actionGetRequirementById()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Requirement::getRequirementById($obj);
        return json_encode($retstr);
    }

    public function actionGetRequirementList()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Requirement::getRequirementList($obj);
        return json_encode($retstr);
    }

    public function actionGetRequirementByuserid(){
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $reqlist=Requirement::getUserRequirements($obj);
            if(empty($reqlist)){
                return 'null';
        }
        return json_encode($reqlist);
    }

    public function actionModifyRequirement(){
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $reqlist=Requirement::modifyRequirement($obj);
        return json_encode($reqlist);
    }

    public function actionDeleteRequirementById(){
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $reqlist=Requirement::deleteRequirementById($obj);
        return json_encode($reqlist);
    }

    public function actionGetMyRequirementDetail(){
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $reqlist=Requirement::GetMyRequirementDetail($obj);
            if(empty($reqlist)){
                return 'null';
        }
        return json_encode($reqlist);
    }

}

?>
