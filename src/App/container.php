<?php
declare(strict_types=1);

use CommsTest\Service\EntityService\MessageService;
use CommsTest\Service\EntityService\RoomService;
use CommsTest\Service\EntityService\UserService;
use CommsTest\Service\ValidatorService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Configuration;
use Psr\Container\ContainerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

return [
    Connection::class => function (ContainerInterface $container) {
        $config = $container->get('app.config');
        $connectionParams = [
            'dbname'   => $config['DB_NAME'],
            'user'     => $config['DB_USERNAME'],
            'password' => $config['DB_PASSWORD'],
            'host'     => $config['DB_HOST'],
            'driver'   => 'pdo_mysql',
        ];

        return DriverManager::getConnection($connectionParams);
    },
    Configuration::class => function(ContainerInterface $container) {
        $config = $container->get('app.config');
        return ORMSetup::createAttributeMetadataConfiguration(
            [$config['ROOT_DIR'] . '/src/Entity'],
            (bool) $config['IS_DEVELOPMENT']
        );
    },
    EntityManager::class => function(ContainerInterface $container) {
        return new EntityManager(
            $container->get(Connection::class),
            $container->get(Configuration::class)
        );
    },
    Serializer::class => function(ContainerInterface $container) {
        $normalizers = [new DateTimeNormalizer(), new ObjectNormalizer(),];
        $encoders = [new JsonEncoder(),];
        return new Serializer($normalizers, $encoders);
    },
    ValidatorService::class => function(ContainerInterface $container) {
        return new ValidatorService();
    },
    MessageService::class => function(ContainerInterface $container) {
        return new MessageService(
            $container->get(EntityManager::class)
        );
    },
    RoomService::class => function(ContainerInterface $container) {
        return new RoomService(
            $container->get(EntityManager::class)
        );
    },
    UserService::class => function(ContainerInterface $container) {
        return new UserService(
            $container->get(EntityManager::class)
        );
    },
];
