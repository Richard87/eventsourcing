<?php

namespace App\Infrastructure;


use EventSauce\EventSourcing\Serialization\PayloadSerializer as Serializer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class PayloadSerializer implements Serializer
{

    public function __construct(
        private NormalizerInterface   $normalizer,
        private DenormalizerInterface $denormalizer,
        private Stopwatch $stopwatch,
    )
    {
    }

    public function serializePayload(object $event): array
    {
        return $this->normalizer->normalize($event);
    }

    public function unserializePayload(string $className, array $payload): object
    {
        $this->stopwatch->start("unserialize payload", "serializer");

        $isSimpleConstructor = in_array(SimpleConstructorNormalizer::class, class_implements($className));

        if ($isSimpleConstructor) {
            $message = new $className(...$payload);
        } else {
            $message = $this->denormalizer->denormalize($payload, $className);
        }

        $this->stopwatch->stop("unserialize payload");
        return $message;
    }
}