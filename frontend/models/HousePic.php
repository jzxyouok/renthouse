<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;


class HousePic extends ActiveRecord
{
	public static function tableName()
	{
		return 't_house_picture';
	}
	
 
	public static function SubmitPicture($jsonObj)
	{
		
		try{
			
			Yii::error("jsonObj is ".print_r($jsonObj,1)."\n");		

			for($i=0; $i<count($jsonObj->picture); $i++)
			{
			    $pic = new HousePic();
				$pic->houseid = $jsonObj->houseid;
			    $pic->picture = $jsonObj->picture[$i]->picture;
			    $pic->sn = $jsonObj->picture[$i]->sn;
			    $pic->save();
			}

			$res['houseid']=$jsonObj->houseid;
	        $res['rscode']=0;
	        return $res;
		} catch(\Exception $e) {
			Yii::error("HousePic::SubmitPicture error is ".$e->getMessage()."\n");
			Supplyment::DelHouseByHouseid($jsonObj->houseid);
            return 1;
        }
	}


	public static function DeletePicture($jsonObj)
	{
		try{

			$connection = Yii::$app->db;
			$sql = sprintf("DELETE FROM t_house_picture where houseid=%s ", $jsonObj->houseid);
			Yii::error("sql is ".$sql."\n");
			$command = $connection->createCommand($sql);
			$houseid = $command->execute();

			return 0;
		} catch(\Exception $e) {
			Yii::error("HousePic::DeletePicture error is ".$e->getMessage()."\n");
            return 1;
        }
	}



	public static function GetHouseDetail($jsonObj)
    {
        try{
                Yii::error("request is ".print_r($jsonObj,1)."\n");
                $res = HousePic::find()->where(['houseid' => $jsonObj->supplymentId])->orderBy('id')->asArray()->all();
                Yii::error("res is ".print_r($res,1)."\n");
                return $res;
            } catch(\Exception $e) {
                Yii::error("HousePic::GetHouseDetail error is ".$e->getMessage()."\n");
                return $e->getMessage();
            }
    }
	
}


?>