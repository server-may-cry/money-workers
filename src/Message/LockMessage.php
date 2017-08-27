<?php

declare(strict_types=1);

namespace App\Message;

class LockMessage
{
    /**
     * lock id.
     */
    private $id;
    private $uid;
    private $amount;

    public function setId(int $id): LockMessage
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUid(int $uid): LockMessage
    {
        $this->uid = $uid;

        return $this;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setAmount(int $amount): LockMessage
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
