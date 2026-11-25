<?php
namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;
use Medoo\Medoo;

class UserController
{
    protected Twig $view;
    protected Medoo $db;

    public function __construct(Twig $view, Medoo $db)
    {
        $this->view = $view;
        $this->db = $db;
        session_start();
    }

    private function requireLogin(Response $response): ?Response
    {
        if (!isset($_SESSION['user_id'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        return null;
    }

    public function index(Request $request, Response $response): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        $users = $this->db->select('users', '*');
        return $this->view->render($response, 'dashboard.twig', ['users' => $users]);
    }

    public function create(Request $request, Response $response): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        return $this->view->render($response, 'form_user.twig', ['action' => '/user/store']);
    }

    public function store(Request $request, Response $response): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        $data = $request->getParsedBody();
        $this->db->insert('users', [
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

    public function edit(Request $request, Response $response, $args): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        $user = $this->db->get('users', '*', ['id' => $args['id']]);
        return $this->view->render($response, 'form_user.twig', [
            'user' => $user,
            'action' => '/user/update/' . $args['id']
        ]);
    }

    public function update(Request $request, Response $response, $args): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        $data = $request->getParsedBody();
        $update = ['username' => $data['username']];
        if (!empty($data['password'])) {
            $update['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        $this->db->update('users', $update, ['id' => $args['id']]);
        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }

    public function delete(Request $request, Response $response, $args): Response
    {
        if ($redirect = $this->requireLogin($response)) return $redirect;

        $this->db->delete('users', ['id' => $args['id']]);
        return $response->withHeader('Location', '/dashboard')->withStatus(302);
    }
}