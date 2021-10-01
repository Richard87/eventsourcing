<?php

namespace App\Infrastructure;


use EventSauce\EventSourcing\Serialization\PayloadSerializer as Serializer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PayloadSerializer implements Serializer
{

    public function __construct(
        private NormalizerInterface   $normalizer,
        private DenormalizerInterface $denormalizer,
    )
    {
    }

    public function serializePayload(object $event): array
    {
        return $this->normalizer->normalize($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        return $this->denormalizer->denormalize($payload, $className);
    }
}