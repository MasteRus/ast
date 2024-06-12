<?php
namespace app\modules\admin\models\forms;

use app\models\activeRecord\Event;
use app\models\activeRecord\Organizator;
use app\models\forms\ModelForm;
use RuntimeException;
use Yii;

class OrganizatorForm extends ModelForm
{
    public $id;
    public string $fullname;
    public string $email;
    public string $phone;

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            [['fullname', 'email'], 'required'],
            [['fullname', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels(): array
    {
        return [
            'fullname' => Yii::t('app', 'fullname'),
            'email' => Yii::t('app', 'email'),
            'phone' => Yii::t('app', 'phone'),
        ];
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
            $result = $organizator->id;
        } catch (RuntimeException $e) {
            $result = 0;
        }

        return $result;
    }
}
