<?php

declare(strict_types=1);

namespace App\Message;

class UnlockMessage
{
    private $id;
    private $submit;

    public function setId(int $id): UnlockMessage
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setSubmit(bool $submit): UnlockMessage
    {
        $this->submit = $submit;

        return $this;
    }

    public function getSubmit(): bool
    {
        return $this->submit;
    }
}
