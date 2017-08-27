<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Message\LockMessage;
use App\Message\UnlockMessage;
use App\StorageKeys;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Predis\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UnlockConsumer implements ConsumerInterface
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
        // @var UnlockMessage $unlock
        $unlock = $this->serializer->deserialize(
            $msg->body,
            UnlockMessage::class,
            'json'
        );
        if ($this->redis->exists(StorageKeys::LOCK_PREFIX.$unlock->getId()) !== 1) {
            return false;
        }
        if ($unlock->getSubmit() !== true) {
            $transactionRaw = $this->redis->get(
                StorageKeys::LOCK_PREFIX.$unlock->getId()
            );
            // @var LockMessage $originalTransaction
            $originalTransaction = $this->serializer->deserialize(
                $transactionRaw,
                LockMessage::class,
                'json'
            );
            $this->redis->incrby(
                StorageKeys::USER_PREFIX.$originalTransaction->getUid(),
                $originalTransaction->getAmount()
            );
        }
        $this->redis->del(StorageKeys::LOCK_PREFIX.$unlock->getId());

        return true;
    }
}
