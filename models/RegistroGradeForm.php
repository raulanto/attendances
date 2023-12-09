<?php
namespace app\models;

use yii\base\Model;
use app\models\Group;
use app\models\Person;
class RegistroCodeFrom extends Model
{
    public $cod_fkgroup;
    public $cod_duration;
    
    public function rules() 
    {
        return [
            [['graper_score', 'graper_commit', 'graper_fkperson', 'graper_fkgrade'], 'required'],
            [['graper_score'], 'number'],
            [['graper_commit'], 'string'],
            [['graper_fkperson', 'graper_fkgrade'], 'integer'],
            [['gra_type', 'gra_date', 'gra_time', 'gra_fkgroup'], 'required'], //MOOOOD-------------------
            [['gra_date', 'gra_time'], 'safe'],
            [['gra_fkgroup'], 'integer'], //MOOOOD-------------------
            [['gra_type'], 'string', 'max' => 50],
            [['gra_fkgroup'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['gra_fkgroup' => 'gro_id']],
            [['graper_fkperson'], 'exist', 'skipOnError' => true, 'targetClass' => Person::class, 'targetAttribute' => ['graper_fkperson' => 'per_id']],
            [['graper_fkgrade'], 'exist', 'skipOnError' => true, 'targetClass' => Grade::class, 'targetAttribute' => ['graper_fkgrade' => 'gra_id']],
        ];
    }
}

?>