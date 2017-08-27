<?php

declare(strict_types=1);

namespace App\Tests\Consumer;

use App\Consumer\ChangeConsumer;
use App\Message\ChangeMessage;
use App\StorageKeys;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @covers \App\Consumer\ChangeConsumer
 */
class ChangeTest extends TestCase
{
    public function test()
    {
        $rawMsg = '{"uid":123,"amount":100}';
        $msg = new AMQPMessage($rawMsg);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($rawMsg, ChangeMessage::class, 'json')
            ->willReturn((new ChangeMessage())->setUid(123)->setAmount(100))
        ;
        $redis = $this->getMockBuilder(Client::class)->setMethods(['incrby'])->getMock();
        $redis
            ->expects($this->once())
            ->method('incrby')
            ->with(StorageKeys::USER_PREFIX.'123', 100)
        ;

        $consumer = new ChangeConsumer($redis, $serializer);
        $result = $consumer->execute($msg);
        $this->assertTrue($result);
    }
}
