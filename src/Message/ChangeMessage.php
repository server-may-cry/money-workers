<?php

declare(strict_types=1);

namespace App\Message;

class ChangeMessage
{
    private $uid;
    private $amount;

    public function setUid(int $uid): ChangeMessage
    {
        $this->uid = $uid;

        return $this;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setAmount(int $amount): ChangeMessage
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
