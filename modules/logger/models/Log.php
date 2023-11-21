<?php

declare(strict_types=1);

namespace app\modules\logger\models;

use yii\db\ActiveRecord;

class Log extends ActiveRecord
{
    public int $id;
    public string $message;
    public string $created_at;

    public static function tableName(): string
    {
        return '{{logs}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            // name, email, subject and body are required
            ['message', 'required'],
        ];
    }

}