<?php
namespace app\modules\admin\models\forms;

use app\models\activeRecord\Event;
use app\models\forms\ModelForm;
use RuntimeException;
use Yii;

class EventForm extends ModelForm
{
    public $id;
    public $name;
    public $description;
    public $planned_date;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['planned_date'], 'date'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'name'),
            'description' => Yii::t('app', 'description'),
            'planned_date' => Yii::t('app', 'planned date'),
        ];
    }
    
    /**
     * @inheritDoc
     */
    protected function create(): int
    {
        try {
            $event = new Event();
            $event->name = $this->name;
            $event->description = $this->description;
            $event->planned_date = $this->planned_date;
            if (!$event->save()) {
                throw new RuntimeException(current($event->getFirstErrors()));
            }
            $result = $event->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    protected function update(): int
    {
        try {
            $event = Event::findOne($this->id);
            if (null === $event) {
                throw new RuntimeException(Yii::t('app', 'Event not found'));
            }
            $event->name = $this->name;
            $event->description = $this->description;
            $event->planned_date = $this->planned_date;
            if (!$event->save()) {
                throw new RuntimeException(current($event->getFirstErrors()));
            }
            $result = $event->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }
}
