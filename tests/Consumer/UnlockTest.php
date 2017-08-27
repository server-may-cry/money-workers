<?php

declare(strict_types=1);

namespace App\Tests\Consumer;

use App\Consumer\UnlockConsumer;
use App\Message\LockMessage;
use App\Message\UnlockMessage;
use App\StorageKeys;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Predis\Client;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @covers \App\Consumer\UnlockConsumer
 */
class UnlockTest extends TestCase
{
    public function test_submit_true()
    {
        $rawMsg = '{"id":10,"submit":true}';
        $msg = new AMQPMessage($rawMsg);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($rawMsg, UnlockMessage::class, 'json')
            ->willReturn((new UnlockMessage())->setId(10)->setSubmit(true))
        ;
        $redis = $this->getMockBuilder(Client::class)->setMethods(['del'])->getMock();
        $redis
            ->expects($this->once())
            ->method('del')
            ->with(StorageKeys::LOCK_PREFIX.'10')
        ;

        $consumer = new UnlockConsumer($redis, $serializer);
        $result = $consumer->execute($msg);
        $this->assertTrue($result);
    }

    public function test_submit_false()
    {
        $rawMsg = '{"id":10,"submit":false}';
        $msg = new AMQPMessage($rawMsg);

        $serializer = $this->createMock(SerializerInterface::class);
        $serializer
            ->expects($this->at(0))
            ->method('deserialize')
            ->with($rawMsg, UnlockMessage::class, 'json')
            ->willReturn((new UnlockMessage())->setId(10)->setSubmit(false))
        ;
        $redis = $this->getMockBuilder(Client::class)->setMethods(['del', 'get', 'incrby'])->getMock();
        $oldTransaction = '{"id":10,"uid":1,"amount":2}';
        $redis
            ->expects($this->once())
            ->method('get')
            ->with(StorageKeys::LOCK_PREFIX.'10')
            ->willReturn($oldTransaction)
        ;
        $serializer
            ->expects($this->at(1))
            ->method('deserialize')
            ->with($oldTransaction, LockMessage::class, 'json')
            ->willReturn((new LockMessage())->setId(10)->setUid(1)->setAmount(2))
        ;
        $redis
            ->expects($this->once())
            ->method('incrby')
            ->with(StorageKeys::USER_PREFIX.'1', 2)
        ;
        $redis
            ->expects($this->once())
            ->method('del')
            ->with(StorageKeys::LOCK_PREFIX.'10')
        ;

        $consumer = new UnlockConsumer($redis, $serializer);
        $result = $consumer->execute($msg);
        $this->assertTrue($result);
    }
}
