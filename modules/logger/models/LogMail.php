<?php

declare(strict_types=1);

namespace app\modules\logger\models;

use Yii;
use yii\base\Model;

class LogMail extends Model
{
    public string $email;
    public string $subject;
    public string $body;


    public function rules(): array
    {
        return [
            [['email', 'subject', 'body'], 'required'],
            ['email', 'email']
        ];
    }

    public function send(): bool
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($this->email)
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}