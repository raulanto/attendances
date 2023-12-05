<?php
namespace app\models;

use yii\base\Model;

class RegistroCodeFrom extends Model
{
    public $cod_fkgroup;
    public $cod_duration;
    
    public function rules() 
    {
        return [
            [['cod_code', 'cod_fkgroup', 'cod_time', 'cod_date', 'cod_duration'], 'required'],
            [['cod_fkgroup', 'cod_duration'], 'integer'],
            [['cod_time', 'cod_date'], 'safe'],
            [['cod_code'], 'string', 'max' => 10],
            [['cod_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['cod_fkgroup' => 'gro_id']],
        ];
    }
}

?>