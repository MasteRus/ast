<?php

namespace app\models\activeRecord;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

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
    public static function tableName()
    {
        return 'events';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
    public function attributeLabels()
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

    /**
     * Gets query for [[Organizators]].
     *
     * @return ActiveQuery
     */
    public function getOrganizators()
    {
        return $this
            ->hasMany(Organizator::class, ['id' => 'organizator_id'])
            ->viaTable('organizators_events', ['event_id' => 'id']);
    }
}
