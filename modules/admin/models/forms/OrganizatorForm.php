<?php
namespace app\modules\admin\models\forms;

use app\models\activeRecord\Event;
use app\models\activeRecord\Organizator;
use app\models\forms\ModelForm;
use RuntimeException;
use Yii;
use yii\helpers\ArrayHelper;

class OrganizatorForm extends ModelForm
{
    public $id;
    public $fullname;
    public $email;
    public $phone;

    public $eventIds;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            [['fullname', 'email'], 'required'],
            [['fullname', 'phone'], 'string'],
            [
                'phone',
                'match',
                'pattern' => '/^(\+7)[-](\d{3})[-](\d{3})[-](\d{2})[-](\d{2})/',
                'message' => 'Phone must be in format +7-XXX-XXX-XX-XX'
            ],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass'     => Organizator::class,
                'targetAttribute' => 'email',
                'filter'          => function ($query) {
                    if (!$this->isNewRecord) {
                        $query->andWhere(['not', ['id' => $this->id]]);
                    }
                },
                'message'         => 'This email already used'
            ],
            ['eventIds', 'each', 'rule' => ['integer']],
            ['eventIds', 'each', 'rule' => ['exist', 'targetClass' => Event::class, 'targetAttribute' => 'id']],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'fullname' => Yii::t('app', 'fullname'),
            'email'    => Yii::t('app', 'email'),
            'phone'    => Yii::t('app', 'phone'),
            'eventIds' => Yii::t('app', 'events'),
        ];
    }

    public function getEventOptions(): array
    {
        $organizator = Organizator::findOne($this->id);

        return ArrayHelper::map(
            $organizator->events,
            'id',
            'name'
        );
    }

    /**
     * @inheritDoc
     */
    protected function create(): int
    {
        try {
            $organizator = new Organizator();
            $organizator->fullname = $this->fullname;
            $organizator->email = $this->email;
            $organizator->phone = $this->phone;
            if (!$organizator->save()) {
                throw new RuntimeException(current($organizator->getFirstErrors()));
            }
            $this->saveEvents($organizator);
            $result = $organizator->id;
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
            $organizator = Organizator::findOne($this->id);
            if (null === $organizator) {
                throw new RuntimeException(Yii::t('app', 'Organizator not found'));
            }
            $organizator->fullname = $this->fullname;
            $organizator->email = $this->email;
            $organizator->phone = $this->phone;
            if (!$organizator->save()) {
                throw new RuntimeException(current($organizator->getFirstErrors()));
            }
            $this->saveEvents($organizator);
            $result = $organizator->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }

    private function saveEvents(Organizator $organizator): void
    {
        $organizator->unlinkAll('events', true);

        if (is_array($this->eventIds)) {
            foreach ($this->eventIds as $eventId) {
                if ($event = Event::findOne($eventId)) {
                    $organizator->link('events', $event);
                } else {
                    throw new RuntimeException(Yii::t('app', 'Event not found'));
                }
            }
        }
    }
}
