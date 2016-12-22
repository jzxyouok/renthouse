<?php
namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;

class Supplyment extends ActiveRecord
{
	public static function tableName()
    {
        return 't_supplyment';
    }
	
 	/**
     *    截取小数的值 （不四舍五入）
     *     @param    $value    int/float    需要截取值
     *     @param    $num    int         保留几位
     *     @param    $return_num  float    处理后返回的值
     */
    public static function getSubNumber($value=0, $num=2){
        $return_num = 0;
        $value = floatval($value);
        $rate = 1;
        for($i=0;$i<$num;$i++){
            $rate = $rate * 10;
        }
        $return_num = (intval($value*$rate))/$rate;
        if(is_float($return_num)){
            $return_exp = explode('.',$return_num);
            $len = strlen($return_exp[1]);
            if($len<$num){
                $j = intval($num-$len);
                $zero = "";
                for($k=0;$k<$j;$k++){
                    $zero.="0";
                }
                if($zero!=""){
                    $return_num.=$zero;
                }
            }
        }else{
           $return_num = number_format($value,$num);
        }
        return $return_num;
    }

    public static function AddSupplyment($jsonObj)
    {
    	Yii::error("Supplyment::AddSupplyment jsonObj is : ".print_r($jsonObj,1)."\n");
    	try{
    		$Time = dechex(time());
	        Yii::error("Supplyment::AddSupplyment Time is $Time\n");
	        $Seq = dechex(rand());
	        Yii::error("Supplyment::AddSupplyment Seq is $Seq\n");
			$Seq = str_pad($Seq,8,"0",STR_PAD_LEFT);
			Yii::error("Supplyment::AddSupplyment Seq is $Seq\n");
			$redis_id=$Time.$Seq;
			Yii::error("Supplyment::AddSupplyment redis_id is $redis_id\n");

	    	$supplyment = new Supplyment();
			$supplyment->userid = $jsonObj->userid;
			$supplyment->city = $jsonObj->city;
			$supplyment->district = $jsonObj->district;
			$supplyment->court = $jsonObj->court;
			// $supplyment->lat = $jsonObj->lat;
			$supplyment->lat = static::getSubNumber($jsonObj->lat,5);
			// $supplyment->lng = $jsonObj->lng;
			$supplyment->lng = static::getSubNumber($jsonObj->lng,5);
			$supplyment->area = $jsonObj->area;
			$supplyment->price = $jsonObj->price;
			$supplyment->floor = $jsonObj->floor;
			$supplyment->totalfloor = $jsonObj->totalfloor;
			$supplyment->room = $jsonObj->room;
			$supplyment->hall = $jsonObj->hall;
			$supplyment->kitchen = $jsonObj->kitchen;
			$supplyment->toilet = $jsonObj->toilet;
			$supplyment->decorate = $jsonObj->decorate;
			$supplyment->apartmenttype = $jsonObj->apartmenttype;
			$supplyment->renttype = $jsonObj->renttype;
			$supplyment->cooking = $jsonObj->cooking;
			$supplyment->pay = $jsonObj->pay;
			$supplyment->deposit = $jsonObj->deposit;
			$supplyment->airconditioning = $jsonObj->airconditioning;
			$supplyment->washingmachine = $jsonObj->washingmachine;
			$supplyment->refrigerator = $jsonObj->refrigerator;
			$supplyment->shower = $jsonObj->shower;
			$supplyment->broadband = $jsonObj->broadband;
			$supplyment->accommodationproof = $jsonObj->accommodationproof;
			$supplyment->contactor = $jsonObj->contactor;
			$supplyment->tel = $jsonObj->tel;
			$supplyment->description = $jsonObj->description;
			$supplyment->createtime = date('Y-m-d H:i:s',time());
			$supplyment->updatetime = date('Y-m-d H:i:s',time());
			$supplyment->redis_id = $redis_id;
            $supplyment->agentid = $jsonObj->agentid;
	        $supplyment->save();

            $arr['rscode']=0;
            $arr['houseid']=$supplyment->attributes['id'];

            $jsonObj->houseid = $supplyment->attributes['id'];
            $res2 = HousePic::SubmitPicture($jsonObj);
            if(1==$res2){
                $arr['rscode']=1;
            }

	        $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->geoadd('supplyment',$jsonObj->lng,$jsonObj->lat,$redis_id);
            $redis->close(); 
	        
            return $arr;
        } catch(\Exception $e) {
        	Yii::error("Supplyment::AddSupplyment error is ".$e->getMessage()."\n");
            return ['code'=>1];
        }
        
    }


    public static function GetSupplyment($jsonObj)
    {
    	Yii::error("Supplyment::GetSupplyment jsonObj is : ".print_r($jsonObj,1)."\n");
    	$redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $geolist=$redis->georadius('supplyment',$jsonObj->lng,$jsonObj->lat,'35','km');
        Yii::error("Supplyment::GetSupplyment geolist is : ".print_r($geolist,1)."\n");
        $i=0;
        if($geolist){
	        foreach($geolist as $geo){
	        	$point=$redis->geopos('supplyment',$geo);
	        	Yii::error("Supplyment::GetSupplyment point is : ".print_r($point,1)."\n");
	        	$res[$i]['lng']=$point[0][0];
	        	$res[$i]['lat']=$point[0][1];
                $res[$i]['redis_id']=$geo;
	        	$i++;
	        }
	        $redis->close();
	        return $res;
	    }
	    else{
        	Yii::error("Supplyment::GetSupplyment no geolist find\n");
        	$redis->close();
        }
    }


    public static function GetSupplymentById($jsonObj)
    {
	    $supplyment = static::findOne($jsonObj->supplymentId)->toArray();
        Yii::error("Supplyment::GetSupplymentById supplyment is : ".print_r($supplyment,1)."\n");
        if($supplyment['agentid']!=0){
                $supplyment['userid']=$supplyment['agentid'];
                $sql='SELECT * FROM t_user WHERE id='.$supplyment['agentid'];
                Yii::error("GetSupplymentById: ".$sql);
                $user=static::findBySql($sql)->asArray()->one();
                Yii::error("Supplyment::GetSupplymentById user is : ".print_r($user,1)."\n");
                $supplyment['picture']=$user['picture'];
                $supplyment['tel']=$user['phone'];
                $supplyment['contactor']=$user['nickname'];
        }
        return $supplyment;
    }


    public static function GetSupplymentList($jsonObj)
    {
    	Yii::error("Supplyment::GetSupplymentList jsonObj is : ".print_r($jsonObj,1)."\n");
    	$sql='SELECT t_supplyment.*, t_user.picture FROM t_supplyment LEFT JOIN t_user ON t_supplyment.userid=t_user.id WHERE t_supplyment.lng='.$jsonObj->lng.' AND t_supplyment.lat='.$jsonObj->lat.' LIMIT '.$jsonObj->start.','.$jsonObj->count;
		Yii::error("Supplyment::GetSupplymentList: ".$sql);
		$supplymentlist = static::findBySql($sql)->asArray()->all();
        Yii::error("Supplyment::GetSupplymentList supplymentlist is : ".print_r($supplymentlist,1)."\n");
        $i=0;
        $res=$supplymentlist;
        foreach($supplymentlist as $supplyment){
            if($supplyment['agentid']!=0){
                $supplyment['userid']=$supplyment['agentid'];
                $sql='SELECT * FROM t_user WHERE id='.$supplyment['agentid'];
                Yii::error("GetSupplymentList: ".$sql);
                $user=static::findBySql($sql)->asArray()->one();
                Yii::error("Supplyment::GetSupplymentList user is : ".print_r($user,1)."\n");
                $supplyment['picture']=$user['picture'];
                $supplyment['tel']=$user['phone'];
                $supplyment['contactor']=$user['nickname'];

                $res[$i]=$supplyment;
                $i++;
            }
        }
        return $res;  
    }


    public static function DelHouseByHouseid($jsonObj)
    {
        try{
            $house = static::find()->where(['houseid' => $jsonObj->houseid, 'validflag'=>1])->one();
            Yii::error("Supplyment::DelHouseByHouseid jsonObj is ".print_r($jsonObj,1)."\n");
            if($house==null){
                Yii::error("Houseid not exist\n");
                $res['rscode']=1;
                $res['error']='houseid not exist';
                return $res;
            }
            $house->validflag=0;
            $house->save();
            $res['rscode']=0;
            return $res;
        } catch(\Exception $e) {
            Yii::error("Supplyment::DelHouseByHouseid error is ".$e->getMessage()."\n");
            $res['rscode']=1;
            $res['error']=$e->getMessage();
            return $res;
        }
    }

    public static function GetMyHouse($jsonObj)
    {
        Yii::error("Supplyment::GetMyHouse jsonObj is : ".print_r($jsonObj,1)."\n");
        $query = new Query;
        $query  ->select(['t_supplyment.*', 't_user.picture as picture'])  
                ->from('t_supplyment')
                ->join('LEFT OUTER JOIN', 't_user', 't_user.id =t_supplyment.userid')
                ->where(['t_user.id' => $jsonObj->userid]); 
        $command = $query->createCommand();
        $res = $command->queryAll(); 
        Yii::error("res is ".print_r($res,1)."\n");
        return $res;
    }



    public static function modifySupplyment($jsonObj){
        Yii::error("Supplyment::modifySupplyment jsonObj is : ".print_r($jsonObj,1)."\n");

        $supplyment=static::findOne($jsonObj->supplymentId);
        try{
                if(empty($supplyment)){
                    $arr['rscode']=1;
                    $arr['error']='房源不存在';
                    return $arr;
                }
                Yii::error("supplyment is ".print_r($supplyment,1)."\n");
                $supplyment->area = $jsonObj->area;
                $supplyment->price = $jsonObj->price;
                $supplyment->floor = $jsonObj->floor;
                $supplyment->totalfloor = $jsonObj->totalfloor;
                $supplyment->room = $jsonObj->room;
                $supplyment->hall = $jsonObj->hall;
                $supplyment->kitchen = $jsonObj->kitchen;
                $supplyment->toilet = $jsonObj->toilet;
                $supplyment->decorate = $jsonObj->decorate;
                $supplyment->apartmenttype = $jsonObj->apartmenttype;
                $supplyment->renttype = $jsonObj->renttype;
                $supplyment->cooking = $jsonObj->cooking;
                $supplyment->pay = $jsonObj->pay;
                $supplyment->deposit = $jsonObj->deposit;
                $supplyment->airconditioning = $jsonObj->airconditioning;
                $supplyment->washingmachine = $jsonObj->washingmachine;
                $supplyment->refrigerator = $jsonObj->refrigerator;
                $supplyment->shower = $jsonObj->shower;
                $supplyment->broadband = $jsonObj->broadband;
                $supplyment->accommodationproof = $jsonObj->accommodationproof;
                $supplyment->contactor = $jsonObj->contactor;
                $supplyment->tel = $jsonObj->tel;
                $supplyment->description = $jsonObj->description;
                $supplyment->updatetime = date('Y-m-d H:i:s',time());
                $supplyment->save();
                
                $arr['rscode']=0;
                $arr['houseid']=$supplyment->attributes['id'];

                $jsonObj->houseid = $supplyment->attributes['id'];
                $res2 = HousePic::DeletePicture($jsonObj);
                if(1==$res2){
                    $arr['rscode']=1;
                }
                $res3 = HousePic::SubmitPicture($jsonObj);
                if(1==$res3){
                    $arr['rscode']=1;
                }

                return $arr;
        } catch(\Exception $e) {
            Yii::error("error is: ".$e->getMessage());
            $arr['rscode']=1;
            $arr['error']=$e->getMessage();
            return $arr;
        }
    }


    public static function DeleteSupplymentById($jsonObj)
    {
        Yii::error("Supplyment::DeleteSupplymentById jsonObj is : ".print_r($jsonObj,1)."\n");
        $supplyment=static::findOne($jsonObj->supplymentId);
        Yii::error("Supplyment::DeleteSupplymentById supplyment to be delete is : ".print_r($supplyment,1)."\n");

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->zrem('supplyment',$supplyment->redis_id);
        $redis->close();

        $supplyment->delete();

        $arr['rscode']=0;
        return $arr;
    }

}
