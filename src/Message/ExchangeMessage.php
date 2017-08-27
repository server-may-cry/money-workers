<?php

declare(strict_types=1);

namespace App\Message;

class ExchangeMessage
{
    private $amount;
    private $fromUid;
    private $toUid;

    public function setAmount(int $amount): ExchangeMessage
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setFromUid(int $fromUid): ExchangeMessage
    {
        $this->fromUid = $fromUid;

        return $this;
    }

    public function getFromUid(): int
    {
        return $this->fromUid;
    }

    public function setToUid(int $toUid): ExchangeMessage
    {
        $this->toUid = $toUid;

        return $this;
    }

    public function getToUid(): int
    {
        return $this->toUid;
    }
}
