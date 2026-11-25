<?php
use App\Application\Settings\Settings;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Medoo\Medoo;
use Monolog\Logger;
use Slim\Views\Twig;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        SettingsInterface::class => function () {
            $dbConfig = [
                'database_type' => 'mysql',
                'database_name' => 'db_steven',
                'server' => 'localhost',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
            ];
            return new Settings([
                'displayErrorDetails' => true,
                'logError' => false,
                'logErrorDetails' => false,
                'logger' => [
                    'name' => 'slim-app',
                    'path' => __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],
                'database' => $dbConfig,
            ]);
        },

        Medoo::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            return new Medoo($settings->get('database'));
        },

        Twig::class => function () {
            return Twig::create(__DIR__ . '/../templates', [
                'cache' => false,
                'debug' => true,
            ]);
        },
    ]);
};
