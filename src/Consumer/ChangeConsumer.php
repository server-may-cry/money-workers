<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Message\ChangeMessage;
use App\StorageKeys;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Predis\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ChangeConsumer implements ConsumerInterface
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
        // @var ChangeMessage $change
        $change = $this->serializer->deserialize(
            $msg->body,
            ChangeMessage::class,
            'json'
        );
        $this->redis->incrby(
            StorageKeys::USER_PREFIX.$change->getUid(),
            $change->getAmount()
          );

        return true;
    }
}
