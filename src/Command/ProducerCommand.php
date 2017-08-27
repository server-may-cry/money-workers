<?php

declare(strict_types=1);

namespace App\Command;

use App\Message\ChangeMessage;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

// generate data for example
class ProducerCommand extends ContainerAwareCommand
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('producer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @var ProducerInterface $changeChannel
        $changeChannel = $this->getContainer()->get('old_sound_rabbit_mq.change_producer');
        $msg = new ChangeMessage();
        $msg->setUid(123)->setAmount(100);
        $serialized = $this->serializer->serialize($msg, 'json');
        $output->writeln($serialized);
        $changeChannel->publish($serialized);
    }
}
