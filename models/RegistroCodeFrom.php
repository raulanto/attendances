<?php
namespace app\models;

use yii\base\Model;
use app\models\Group;
class RegistroCodeFrom extends Model
{
    public $cod_fkgroup;
    public $cod_duration;
    
    public function rules() 
    {
        return [
            [['cod_fkgroup', 'cod_duration'], 'required'],
            [['cod_fkgroup', 'cod_duration'], 'integer'],
            [['cod_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['cod_fkgroup' => 'gro_id']],
        ];
    }
}

?>