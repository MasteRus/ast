<?php

namespace app\models\activeRecord;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $planned_date
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Organizator[] $organizators
 */
class Event extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'events';
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
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['planned_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'planned_date' => Yii::t('app', 'Planned Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getOrganizators(): ActiveQuery
    {
        return $this
            ->hasMany(Organizator::class, ['id' => 'organizator_id'])
            ->viaTable('organizators_events', ['event_id' => 'id']);
    }
}
