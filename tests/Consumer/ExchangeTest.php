<?php

declare(strict_types=1);

namespace App\Tests\Consumer;

use App\Consumer\ExchangeConsumer;
use App\Message\ExchangeMessage;
use App\StorageKeys;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @covers \App\Consumer\ExchangeConsumer
 */
class ExchangeTest extends TestCase
{
    public function test()
    {
        $rawMsg = '{"amount":10,"fromUid":1,"toUid":2}';
        $msg = new AMQPMessage($rawMsg);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($rawMsg, ExchangeMessage::class, 'json')
            ->willReturn((new ExchangeMessage())->setAmount(10)->setFromUid(1)->setToUid(2))
        ;
        $redis = $this->getMockBuilder(Client::class)->setMethods(['incrby'])->getMock();
        $redis
            ->expects($this->at(0))
            ->method('incrby')
            ->with(StorageKeys::USER_PREFIX.'1', -10)
        ;
        $redis
            ->expects($this->at(1))
            ->method('incrby')
            ->with(StorageKeys::USER_PREFIX.'2', 10)
        ;

        $consumer = new ExchangeConsumer($redis, $serializer);
        $result = $consumer->execute($msg);
        $this->assertTrue($result);
    }
}
