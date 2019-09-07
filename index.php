<?php
include __DIR__ . '/vendor/autoload.php';

use \Express\Express;
use \Express\Router;

$express = new Express();
$router = new Router();

$express->set('view engine', 'mustache');
$express->set('views', './views/');

$router->get('/', function ($req, $res) {
    $res->render('index');
});

$router->get('/page/:slug', function ($req, $res) {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=express_php;charset=utf8mb4', 'root', '', [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);

        $stmt = $pdo->prepare('SELECT * FROM pages WHERE slug=:slug LIMIT 1');
        $stmt->execute(['slug' => $req->params->slug]);
        $page = $stmt->fetch();

        $res->render('page', [
            'page' => $page
        ]);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
});

$express->listen($router);
?>
