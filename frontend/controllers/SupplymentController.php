<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Supplyment;
use frontend\models\HousePic;

class SupplymentController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function actionIndex($userid)
    {
        header("Content-type:json/application;charset=utf-8");
        $model = new User();
    }

	public function actionAddSupplyment()
    {
        Yii::error("Supplyment::actionAddSupplyment\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Supplyment::addSupplyment($obj);
        return json_encode($retstr);
    }

    public function actionGetSupplyment()
    {
        Yii::error("Supplyment::actionGetSupplyment\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Supplyment::getSupplyment($obj);
        return json_encode($retstr);
    }

    public function actionGetSupplymentById()
    {
        Yii::error("Supplyment::actionGetSupplymentById\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $res1=Supplyment::getSupplymentById($obj);
        Yii::error("res1 is ".print_r($res1,1)."\n");
        $res2=HousePic::getHouseDetail($obj);
        Yii::error("res2 is ".print_r($res2,1)."\n");
        $res=array(
            "id"=>$res1['id'],
            "redis_id"=>$res1['redis_id'],
            "userid"=>$res1['userid'],
            "city"=>$res1['city'],
            "district"=>$res1['district'],
            "court"=>$res1['court'],
            "lat"=>$res1['lat'],
            "lng"=>$res1['lng'],
            "area"=>$res1['area'],
            "price"=>$res1['price'],
            "floor"=>$res1['floor'],
            "totalfloor"=>$res1['totalfloor'],
            "room"=>$res1['room'],
            "hall"=>$res1['hall'],
            "kitchen"=>$res1['kitchen'],
            "toilet"=>$res1['toilet'],
            "decorate"=>$res1['decorate'],
            "apartmenttype"=>$res1['apartmenttype'],
            "renttype"=>$res1['renttype'],
            "cooking"=>$res1['cooking'],
            "pay"=>$res1['pay'],
            "deposit"=>$res1['deposit'],
            "airconditioning"=>$res1['airconditioning'],
            "washingmachine"=>$res1['washingmachine'],
            "refrigerator"=>$res1['refrigerator'],
            "shower"=>$res1['shower'],
            "broadband"=>$res1['broadband'],
            "accommodationproof"=>$res1['accommodationproof'],
            "contactor"=>$res1['contactor'],
            "tel"=>$res1['tel'],
            "description"=>$res1['description'],
            "createtime"=>$res1['createtime'],
            "updatetime"=>$res1['updatetime'],
            "agentid"=>$res1['agentid'],
            "picture"=>$res2
        );
        return json_encode($res);
    }

    public function actionGetSupplymentList()
    {
        Yii::error("Supplyment::actionGetSupplymentList\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Supplyment::getSupplymentList($obj);
        return json_encode($retstr);
    }

    public function actionGetMyHouse()
    {
        Yii::error("Supplyment::GetMyHouse\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Supplyment::getMyHouse($obj);
        return json_encode($retstr);
    }


    public function actionModifySupplyment()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $res=Supplyment::ModifySupplyment($obj);
        return json_encode($res);
    }


    public function actionDeleteSupplymentById()
    {
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $res=Supplyment::DeleteSupplymentById($obj);
        return json_encode($res);
    }

    public function actionGetClientHouse()
    {
        Yii::error("Supplyment::GetClientHouse\n");
        $data=Yii::$app->request->rawBody;
        $obj = json_decode($data);
        $retstr=Supplyment::GetClientHouse($obj);
        return json_encode($retstr);
    }
}

?>
