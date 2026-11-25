<?php
// src/Controller/AuthController.php

namespace App\Controller;

use Slim\Views\Twig;
use Medoo\Medoo;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    protected Twig $view;
    protected Medoo $db;

    public function __construct(Twig $view, Medoo $db)
    {
        $this->view = $view;
        $this->db = $db;
        session_start();
    }

    public function showLogin(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'login.twig');
    }

    public function showRegister(Request $request, Response $response): Response
    {
        return $this->view->render($response, 'register.twig');
    }

    public function doRegister(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $username = trim($data['username'] ?? '');
        $password = password_hash($data['password'] ?? '', PASSWORD_DEFAULT);

        if ($username === '' || $data['password'] === '') {
            return $response->withHeader('Location', '/register?error=1')->withStatus(302);
        }

        $this->db->insert('users', ['username' => $username, 'password' => $password]);
        return $response->withHeader('Location', '/login')->withStatus(302);
    }

    public function doLogin(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $user = $this->db->get('users', '*', ['username' => $data['username']]);

        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            return $response->withHeader('Location', '/dashboard')->withStatus(302);
        }

        return $response->withHeader('Location', '/login?error=1')->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        session_destroy();
        return $response->withHeader('Location', '/login')->withStatus(302);
    }
}