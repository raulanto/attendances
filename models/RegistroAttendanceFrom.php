<?php
namespace app\models;

use yii\base\Model;

class RegistroAttendanceFrom extends Model
{

    public $tea_name;
    public $tea_paternal;
    public $tea_maternal;
    public $tea_fkdegree;
    public $tea_mail;
    public $tea_phone;

    
    public function rules() 
    {
        return [
            [['att_date', 'att_time', 'att_commit', 'att_fklist', 'att_fkcode'], 'required'],
            [['att_date', 'att_time'], 'safe'],
            [['att_commit'], 'string'],
            [['att_fklist', 'att_fkcode'], 'integer'],
            [['att_fkcode'], 'exist', 'skipOnError' => true, 'targetClass' => Code::class, 'targetAttribute' => ['att_fkcode' => 'cod_id']],
            [['att_fklist'], 'exist', 'skipOnError' => true, 'targetClass' => Listg::class, 'targetAttribute' => ['att_fklist' => 'list_id']],
        ];
    }
}

?>