<?php
use Slim\App;
use App\Controller\AuthController;
use App\Controller\UserController;

return function (App $app) {
    // Auth
    $app->get('/login', [AuthController::class, 'showLogin']);
    $app->post('/login', [AuthController::class, 'doLogin']);
    $app->get('/register', [AuthController::class, 'showRegister']);
    $app->post('/register', [AuthController::class, 'doRegister']);
    $app->get('/logout', [AuthController::class, 'logout']);

    // CRUD Dashboard
    $app->get('/dashboard', [UserController::class, 'index']);
    $app->get('/user/create', [UserController::class, 'create']);
    $app->post('/user/store', [UserController::class, 'store']);
    $app->get('/user/edit/{id}', [UserController::class, 'edit']);
    $app->post('/user/update/{id}', [UserController::class, 'update']);
    $app->get('/user/delete/{id}', [UserController::class, 'delete']);

    // Default redirect
    $app->get('/', fn($req, $res) => $res->withHeader('Location', '/login')->withStatus(302));
};

