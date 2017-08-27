<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Message\ExchangeMessage;
use App\StorageKeys;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Predis\ClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ExchangeConsumer implements ConsumerInterface
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
        // @var ExchangeMessage $exchange
        $exchange = $this->serializer->deserialize(
            $msg->body,
            ExchangeMessage::class,
            'json'
        );
        $this->redis->incrby(
            StorageKeys::USER_PREFIX.$exchange->getFromUid(),
            -$exchange->getAmount()
        );
        $this->redis->incrby(
            StorageKeys::USER_PREFIX.$exchange->getToUid(),
            $exchange->getAmount()
        );

        return true;
    }
}
