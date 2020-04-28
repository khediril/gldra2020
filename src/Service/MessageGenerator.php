<?php
namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;
    private $name;
    public function __construct(LoggerInterface $logger,$name)
    {
        
        $this->logger=$logger;
        $this->name=$name;
    }
    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);
        $this->logger->info('heureux message...');

        return $messages[$index]."Par ".$this->name;
    }
}
