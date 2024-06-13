<?php

namespace app\modules\admin\models\forms;

use app\models\activeRecord\Event;
use app\models\activeRecord\Organizator;
use app\models\forms\ModelForm;
use RuntimeException;
use Yii;
use yii\helpers\ArrayHelper;

class EventForm extends ModelForm
{
    public $id;
    public $name;
    public $description;
    public $planned_date;
    public $organizatorIds;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['planned_date'], 'date', 'format' => 'yyyy-MM-dd'],
            ['organizatorIds', 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'name'           => Yii::t('app', 'name'),
            'description'    => Yii::t('app', 'description'),
            'planned_date'   => Yii::t('app', 'planned date'),
            'organizatorIds' => Yii::t('app', 'organizators'),
        ];
    }

    public function getOrganizatorOptions(): array
    {
        $event = Event::findOne($this->id);

        return ArrayHelper::map(
            $event->organizators,
            'id',
            'fullname'
        );
    }

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
            $this->saveOrganizators($event);
            $result = $event->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }

    private function saveOrganizators(Event $event): void
    {
        $event->unlinkAll('organizators', true);

        if (is_array($this->organizatorIds)) {
            foreach ($this->organizatorIds as $organizatorId) {
                if ($organizator = Organizator::findOne($organizatorId)) {
                    $event->link('organizators', $organizator);
                } else {
                    throw new RuntimeException(Yii::t('app', 'Organizator not found'));
                }
            }
        }
    }

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
            $this->saveOrganizators($event);
            $result = $event->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }
}
