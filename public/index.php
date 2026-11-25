<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Slim\Factory\ServerRequestCreatorFactory;
use App\Application\Settings\SettingsInterface;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
(require __DIR__ . '/../app/settings.php')($containerBuilder);
(require __DIR__ . '/../app/dependencies.php')($containerBuilder);

$container = $containerBuilder->build();
AppFactory::setContainer($container);
$app = AppFactory::create();

(require __DIR__ . '/../app/routes.php')($app);

$app->addBodyParsingMiddleware();

$app->run();
