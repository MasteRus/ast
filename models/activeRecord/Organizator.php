<?php

namespace app\models\activeRecord;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "organizators".
 *
 * @property int $id
 * @property string $fullname
 * @property string $email
 * @property string|null $phone
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Event[] $events
 */
class Organizator extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizators';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname', 'email'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['fullname', 'email', 'phone'], 'string', 'max' => 255],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fullname' => Yii::t('app', 'Fullname'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getEvents(): ActiveQuery
    {
        return $this
            ->hasMany(Event::class, ['id' => 'event_id'])
            ->viaTable('organizators_events', ['organizator_id' => 'id']);
    }
}
