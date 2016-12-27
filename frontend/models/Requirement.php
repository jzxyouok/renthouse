<?php
namespace frontend\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;

class Requirement extends ActiveRecord
{
	public static function tableName()
    {
        return 't_requirement';
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

    public static function AddRequirement($jsonObj)
    {
    	Yii::error("Requirement::AddRequirement jsonObj is : ".print_r($jsonObj,1)."\n");
    	try{
            $Time = dechex(time());
            Yii::error("Requirement::AddRequirement Time is $Time\n");
            $Seq = dechex(rand());
            Yii::error("Requirement::AddRequirement Seq is $Seq\n");
            $Seq = str_pad($Seq,8,"0",STR_PAD_LEFT);
            Yii::error("Requirement::AddRequirement Seq is $Seq\n");
            $redis_id=$Time.$Seq;
            Yii::error("Requirement::AddRequirement redis_id is $redis_id\n");

	    	$requirement = new Requirement();
			$requirement->userid = $jsonObj->userid;
			$requirement->city = $jsonObj->city;
			$requirement->district = $jsonObj->district;
			$requirement->court = $jsonObj->court;
			// $requirement->lat = $jsonObj->lat;
			$requirement->lat = static::getSubNumber($jsonObj->lat,5);
			// $requirement->lng = $jsonObj->lng;
			$requirement->lng = static::getSubNumber($jsonObj->lng,5);
			$requirement->price = $jsonObj->price;
			$requirement->area = $jsonObj->area;
			$requirement->room = $jsonObj->room;
			$requirement->hall = $jsonObj->hall;
			$requirement->kitchen = $jsonObj->kitchen;
			$requirement->toilet = $jsonObj->toilet;
			$requirement->decorate = $jsonObj->decorate;
			$requirement->apartmenttype = $jsonObj->apartmenttype;
			$requirement->renttype = $jsonObj->renttype;
			$requirement->cooking = $jsonObj->cooking;
			$requirement->pay = $jsonObj->pay;
			$requirement->deposit = $jsonObj->deposit;
			$requirement->airconditioning = $jsonObj->airconditioning;
			$requirement->washingmachine = $jsonObj->washingmachine;
			$requirement->refrigerator = $jsonObj->refrigerator;
			$requirement->shower = $jsonObj->shower;
			$requirement->broadband = $jsonObj->broadband;
			$requirement->accommodationproof = $jsonObj->accommodationproof;
			$requirement->contactor = $jsonObj->contactor;
			$requirement->tel = $jsonObj->tel;
			$requirement->description = $jsonObj->description;
			$requirement->createtime = date('Y-m-d H:i:s',time());
			$requirement->updatetime = date('Y-m-d H:i:s',time());
            $requirement->redis_id = $redis_id;
            $requirement->agentid = $jsonObj->agentid;
            $requirement->save();

	        $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            $redis->geoadd('requirement',$jsonObj->lng,$jsonObj->lat,$redis_id);
	        $redis->close();

	        $arr['rscode']=0;
            return $arr;
        } catch(\Exception $e) {
        	Yii::error("Requirement::AddRequirement error is ".$e->getMessage()."\n");
            return ['code'=>1];
        }
        
    }


    public static function GetRequirement($jsonObj)
    {
    	Yii::error("Requirement::GetRequirement jsonObj is : ".print_r($jsonObj,1)."\n");
    	$redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $geolist=$redis->georadius('requirement',$jsonObj->lng,$jsonObj->lat,'35','km');
        Yii::error("Requirement::GetRequirement geolist is : ".print_r($geolist,1)."\n");
        $i=0;
        if($geolist){
        	foreach($geolist as $geo){
	        	$point=$redis->geopos('requirement',$geo);
	        	Yii::error("Requirement::GetRequirement point is : ".print_r($point,1)."\n");
	        	$res[$i]['lng']=$point[0][0];
	        	$res[$i]['lat']=$point[0][1];
                $res[$i]['redis_id']=$geo;
	        	$i++;
	        }
	        $redis->close();
	        return $res;
        }else{
        	Yii::error("Requirement::GetRequirement no geolist find\n");
        	$redis->close();
        }
    }


    public static function GetRequirementById($jsonObj)
    {
    	Yii::error("Requirement::GetRequirementById jsonObj is : ".print_r($jsonObj,1)."\n");
	    $requirement=static::findOne($jsonObj->requirementId)->toArray();
	    Yii::error("Requirement::GetRequirementById requirement is : ".print_r($requirement,1)."\n");
        if($requirement['agentid']!=0){
                $requirement['userid']=$requirement['agentid'];
                $sql='SELECT * FROM t_user WHERE id='.$requirement['agentid'];
                Yii::error("GetRequirementById: ".$sql);
                $user=static::findBySql($sql)->asArray()->one();
                Yii::error("Requirement::GetRequirementById user is : ".print_r($user,1)."\n");
                $requirement['picture']=$user['picture'];
                $requirement['tel']=$user['phone'];
                $requirement['contactor']=$user['nickname'];
        }
	    return $requirement;
    }


    public static function GetRequirementList($jsonObj)
    {
    	Yii::error("Requirement::GetRequirementList jsonObj is : ".print_r($jsonObj,1)."\n");
    	$sql='SELECT t_requirement.*, t_user.picture FROM t_requirement LEFT JOIN t_user ON t_requirement.userid=t_user.id WHERE t_requirement.lng='.$jsonObj->lng.' AND t_requirement.lat='.$jsonObj->lat.' LIMIT '.$jsonObj->start.','.$jsonObj->count;
		Yii::error("getRequirementList: ".$sql);
		$requirementlist = static::findBySql($sql)->asArray()->all();
        Yii::error("Requirement::GetRequirementList requirementlist is : ".print_r($requirementlist,1)."\n");
        $i=0;
        $res=$requirementlist;
        foreach($requirementlist as $requirement){
            if($requirement['agentid']!=0){
                $requirement['userid']=$requirement['agentid'];
                $sql='SELECT * FROM t_user WHERE id='.$requirement['agentid'];
                Yii::error("getRequirementList: ".$sql);
                $user=static::findBySql($sql)->asArray()->one();
                Yii::error("Requirement::GetRequirementList user is : ".print_r($user,1)."\n");
                $requirement['picture']=$user['picture'];
                $requirement['tel']=$user['phone'];
                $requirement['contactor']=$user['nickname'];

                $res[$i]=$requirement;
            }
            $i++;
        }
        return $res;
    }


    public static function getUserRequirements($jsonObj){
        Yii::error("Requirement::getUserRequirements jsonObj is : ".print_r($jsonObj,1)."\n");
        $query = new Query;
        $query  ->select(['t_requirement.*', 't_user.picture as picture'])  
                ->from('t_requirement')
                ->join('LEFT OUTER JOIN', 't_user', 't_user.id =t_requirement.userid')
                ->where(['t_user.id' => $jsonObj->userid]);
        $command = $query->createCommand();
        $res = $command->queryAll(); 
        Yii::error("Requirement::getUserRequirements res is : ".print_r($res,1)."\n");
        return $res;
    }


    public static function modifyRequirement($jsonObj){
        Yii::error("Requirement::modifyRequirement jsonObj is : ".print_r($jsonObj,1)."\n");

        $requirement=static::findOne($jsonObj->requirementId);
        try{
                if(empty($requirement)){
                    $arr['rscode']=1;
                    $arr['error']='需求不存在';
                    return $arr;
                }
                Yii::error("requirement is ".print_r($requirement,1)."\n");
                $requirement->price = $jsonObj->price;
                $requirement->area = $jsonObj->area;
                $requirement->room = $jsonObj->room;
                $requirement->hall = $jsonObj->hall;
                $requirement->kitchen = $jsonObj->kitchen;
                $requirement->toilet = $jsonObj->toilet;
                $requirement->decorate = $jsonObj->decorate;
                $requirement->apartmenttype = $jsonObj->apartmenttype;
                $requirement->renttype = $jsonObj->renttype;
                $requirement->cooking = $jsonObj->cooking;
                $requirement->pay = $jsonObj->pay;
                $requirement->deposit = $jsonObj->deposit;
                $requirement->airconditioning = $jsonObj->airconditioning;
                $requirement->washingmachine = $jsonObj->washingmachine;
                $requirement->refrigerator = $jsonObj->refrigerator;
                $requirement->shower = $jsonObj->shower;
                $requirement->broadband = $jsonObj->broadband;
                $requirement->accommodationproof = $jsonObj->accommodationproof;
                $requirement->contactor = $jsonObj->contactor;
                $requirement->tel = $jsonObj->tel;
                $requirement->description = $jsonObj->description;
                $requirement->updatetime = date('Y-m-d H:i:s',time());
                $requirement->save();
                
                $arr['rscode']=0;
                return $arr;
        } catch(\Exception $e) {
            Yii::error("error is: ".$e->getMessage());
            $arr['rscode']=1;
            $arr['error']=$e->getMessage();
            return $arr;
        }
    }


    public static function DeleteRequirementById($jsonObj)
    {
        Yii::error("Requirement::DeleteRequirementById jsonObj is : ".print_r($jsonObj,1)."\n");
        $requirement=static::findOne($jsonObj->requirementId);
        Yii::error("Requirement::DeleteRequirementById requirement to be delete is : ".print_r($requirement,1)."\n");

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->zrem('requirement',$requirement->redis_id);
        $redis->close();

        $requirement->delete();

        $arr['rscode']=0;
        return $arr;
    }

    public static function GetRequirementByAgentid($jsonObj){
        Yii::error("Requirement::GetRequirementByAgentid jsonObj is : ".print_r($jsonObj,1)."\n");
        $query = new Query;
        $query  ->select(['t_requirement.*', 't_user.picture as picture'])  
                ->from('t_requirement')
                ->join('LEFT OUTER JOIN', 't_user', 't_user.id =t_requirement.userid')
                ->where(['t_requirement.agentid' => $jsonObj->userid]);
        $command = $query->createCommand();
        $res = $command->queryAll(); 
        Yii::error("Requirement::GetRequirementByAgentid res is : ".print_r($res,1)."\n");
        return $res;
    }

}
