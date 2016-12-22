<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Need;

class User extends ActiveRecord
{   
    public static function tableName()
    {
        return 't_user';
    }
    
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    public static function getUserInfo($userid)
    {
    		return static::find()->where(['t_user.id' => $userid])->asArray()->one();
    }
    
    public static function getUserByOpenid($openid)
    {
    		return static::find()->where(['wxopenid' => $openid])->asArray()->one();
    }
    
    public static function modifyUserInfo($jsonData){
    		try{
                    Yii::error("modifyUserInfo: jsonData is ".print_r($jsonData,1)."\n");
    			    $user=static::findIdentity($jsonData->userid);
    			    if(empty($user)){
    			  		    $arr['rscode']=1;
						    $arr['error']='用户不存在';
						    return $arr;	
    			    }

                    if(!empty($jsonData->phone)){
                        $user_phone=static::findOne(['phone' => $jsonData->phone]);

                        if(!empty($user_phone)){
                            if($user_phone->phone!=$user->phone){
                                $arr['rscode']=1;
                                $arr['error']='手机号已经被注册';
                                return $arr;
                            }
                        }

                        $user->phone=$jsonData->phone;
                    }
                    
                    if($user->agentflag==1){
                        $user->max_requirement=1;
                        $user->max_supplyment=5;
                    }else{
                        $user->max_requirement=1;
                        $user->max_supplyment=2;
                    }

			        $user->nickname=$jsonData->nickname;
					$user->picture=$jsonData->picture;
					$user->email=$jsonData->email;
					$user->sex=$jsonData->sex;
					$user->realname=$jsonData->realname;
					$user->identitycard=$jsonData->identitycard;
                    $user->tags=$jsonData->tags;
                    $user->agentflag=$jsonData->agentflag;
					$user->save();
    			 	$arr['rscode']=0;
				    return $arr;
				} catch(\Exception $e) {
				    $arr['rscode']=1;
				    $arr['error']=$e->getMessage();
				    return $arr;
				}
    }
    
    public static function findUserByPhone($phone)
    {
    		return static::findOne(['phone' => $phone]);
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

    public static function GetAgentList($jsonObj)
    {
        Yii::error("User::GetAgentList jsonObj is : ".print_r($jsonObj,1)."\n");
        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $geolist=$redis->georadius('agent',$jsonObj->lng,$jsonObj->lat,'5','km');
        Yii::error("User::GetAgentList geolist is : ".print_r($geolist,1)."\n");
        $i=0;
        if($geolist){
            foreach($geolist as $geo){
                $point=$redis->geopos('agent',$geo);
                Yii::error("User::GetAgentList point is : ".print_r($point,1)."\n");
                // $res[$i]['lng']=$point[0][0];
                // $res[$i]['lat']=$point[0][1];
                $user[$i]=static::find()->where(['redis_id' => $geo])->asArray()->one();
                $i++;
            }
            $redis->close();
            return $user;
        }else{
            Yii::error("User::GetAgentList no geolist find\n");
            $redis->close();
        }
    }

    public static function UpdateUserToAgent($jsonObj)
    {
        Yii::error("User::UpdateUserToAgent jsonObj is : ".print_r($jsonObj,1)."\n");
        $user=static::findOne($jsonObj->id);

        $Time = dechex(time());
        Yii::error("User::UpdateUserToAgent Time is $Time\n");
        $Seq = dechex(rand());
        Yii::error("User::UpdateUserToAgent Seq is $Seq\n");
        $Seq = str_pad($Seq,8,"0",STR_PAD_LEFT);
        Yii::error("User::UpdateUserToAgent Seq is $Seq\n");
        $redis_id=$Time.$Seq;
        Yii::error("User::UpdateUserToAgent redis_id is $redis_id\n");

        $user->agentflag=1;
        $user->lng=static::getSubNumber($jsonObj->lng,5);
        $user->lat=static::getSubNumber($jsonObj->lat,5);
        $user->redis_id=$redis_id;
        $user->update();

        $redis = new \Redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->geoadd('agent',$jsonObj->lng,$jsonObj->lat,$redis_id);
        $redis->close();

        $arr['rscode']=0;
        return $arr;
    }
    

}

?>