<?php

namespace App\Infrastructure;


use EventSauce\EventSourcing\Header;
use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Serialization\MessageSerializer as EventSauceSerializer;
use Symfony\Component\Serializer\Normalizer\DenormalizableInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MessageSerializer implements EventSauceSerializer
{

    public function __construct(
        private NormalizerInterface $normalizer, private DenormalizerInterface $denormalizer)
    {
    }

    public function serializeMessage(Message $message): array
    {
        return [
            'headers' => $message->headers(),
            'payload' => $this->normalizer->normalize($message->event())
        ];
    }

    public function unserializePayload(array $payload): Message
    {
        $event = $this->denormalizer->denormalize($payload['payload'],$payload['headers'][Header::EVENT_TYPE]);
        return new Message($event, $payload['headers']);
    }
}