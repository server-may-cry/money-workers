<?php

declare(strict_types=1);

namespace App\Tests\Consumer;

use App\Consumer\LockConsumer;
use App\Message\LockMessage;
use App\StorageKeys;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @covers \App\Consumer\LockConsumer
 */
class LockTest extends TestCase
{
    public function test()
    {
        $rawMsg = '{"id":10,"uid":1,"amount":2}';
        $msg = new AMQPMessage($rawMsg);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($rawMsg, LockMessage::class, 'json')
            ->willReturn((new LockMessage())->setId(10)->setUid(1)->setAmount(2))
        ;
        $redis = $this->getMockBuilder(Client::class)->setMethods(['incrby', 'set'])->getMock();
        $redis
            ->expects($this->once())
            ->method('incrby')
            ->with(StorageKeys::USER_PREFIX.'1', -2)
        ;
        $redis
            ->expects($this->once())
            ->method('set')
            ->with(StorageKeys::LOCK_PREFIX.'10', $rawMsg)
        ;

        $consumer = new LockConsumer($redis, $serializer);
        $result = $consumer->execute($msg);
        $this->assertTrue($result);
    }
}
