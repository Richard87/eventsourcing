<?php

namespace App\Infrastructure;

use Doctrine\DBAL\Connection;
use EventSauce\EventSourcing\MessageRepository;
use EventSauce\MessageRepository\DoctrineV2MessageRepository\DoctrineUuidV4MessageRepository;
use EventSauce\MessageRepository\TableSchema\DefaultTableSchema;
use EventSauce\UuidEncoding\BinaryUuidEncoder;

class MessageRepositoryFactory
{
    public function __construct(
        private MessageSerializer $messageSerializer,
        private Connection $connection
    )
    {
    }

    public function create(): MessageRepository{
        return new DoctrineUuidV4MessageRepository(
            connection: $this->connection,
            tableName: "event_store",
            serializer: $this->messageSerializer,
            tableSchema: new DefaultTableSchema(), // optional
            uuidEncoder: new UuidTransformer(), // optional
        );
    }
}