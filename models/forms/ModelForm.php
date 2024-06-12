<?php

namespace app\models\forms;

use yii\base\Model;

/**
 * @property bool $isNewRecord
 */
abstract class ModelForm extends Model
{
    public $id;

    /**
     * @return bool
     */
    public function getIsNewRecord(): bool
    {
        return empty($this->id);
    }

    /**
     * Create or update model
     * @return int
     */
    public function save(): int
    {
        return $this->isNewRecord ? $this->create() : $this->update();
    }

    /**
     * Create new model
     * @return int
     */
    abstract protected function create(): int;

    /**
     * Update model
     * @return int
     */
    abstract protected function update(): int;

    protected function getEntityType():string {
        return static::class;
    }
}