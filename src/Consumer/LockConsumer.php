<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Message\LockMessage;
use App\StorageKeys;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Predis\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class LockConsumer implements ConsumerInterface
{
    private $redis;
    private $serializer;

    public function __construct(ClientInterface $redis, SerializerInterface $serializer)
    {
        $this->redis = $redis;
        $this->serializer = $serializer;
    }

    public function execute(AMQPMessage $msg)
    {
        // @var LockMessage $lock
        $lock = $this->serializer->deserialize(
            $msg->body,
            LockMessage::class,
            'json'
        );
        $this->redis->incrby(
            StorageKeys::USER_PREFIX.$lock->getUid(),
            -$lock->getAmount()
        );
        $this->redis->set(
            StorageKeys::LOCK_PREFIX.$lock->getId(),
            $msg->body
        );

        return true;
    }
}
