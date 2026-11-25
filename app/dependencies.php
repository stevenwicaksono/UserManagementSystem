<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use App\Controller\BookController;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        // Twig Environment
        Environment::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            
            // Setup Twig loader - sesuai dengan struktur folder Anda
            $loader = new FilesystemLoader(__DIR__ . '/../templates');
            
            // Create Twig environment
            $twig = new Environment($loader, [
                'cache' => $settings->get('twig')['cache'] ?? false,
                'debug' => $settings->get('displayErrorDetails') ?? true,
            ]);

            return $twig;
        },

        // BookController - sesuai namespace yang benar
        BookController::class => function (ContainerInterface $c) {
            $logger = $c->get(LoggerInterface::class);
            $twig = $c->get(Environment::class);
            
            return new BookController($logger, $twig);
        },
    ]);
};