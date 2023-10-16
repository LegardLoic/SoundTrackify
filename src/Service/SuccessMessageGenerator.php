<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

/**
 * Generates random messages
 */
class SuccessMessageGenerator {

    private $messages = [
        'Succès déverrouillé : vous avez réussi le niveau !',
        'Congratulation !',
    ];

    private $logger;

    /**
     * The useful services are retrieved via the
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        // this service will be usable in all methods of the MessageGenerator service
        $this->logger = $logger;
    }

    /**
     * Get a random message
     *
     * @return void
     */
    public function getRandomSuccessMessage()
    {
        // a random message
        $randomMessage = $this->messages[array_rand($this->messages)];

        // we log this message for debugging
        $this->logger->info('Message généré', ['message' => $randomMessage]);

        return $randomMessage;
    }
}
