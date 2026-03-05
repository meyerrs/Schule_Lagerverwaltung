<?php

declare(strict_types=1);

use App\Handler\HomePageHandler;
use App\Handler\LoginHandler;
use App\Handler\PingHandler;
use App\Handler\UserEditHandler;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/', HomePageHandler::class, 'home');
    $app->get('/api/ping', PingHandler::class, 'api.ping');
    $app->post('/api/login', App\Handler\LoginHandler::class, 'api.login');
    $app->get('/api/isAuth', App\Handler\AuthenticationHandler::class, 'api.isAuth');
    $app->get('/api/logout', App\Handler\LogoutHandler::class, 'api.logout');
    $app->get('/api/inventory', App\Handler\InventoryFetchHandler::class, 'api.inventoryFetch');
    $app->delete('/api/inventory', App\Handler\InventoryDeleteHandler::class, 'api.inventoryDelete');
    $app->put('/api/inventory', App\Handler\InventoryEditHandler::class, 'api.inventoryEdit');
    $app->put('/api/user', App\Handler\UserEditHandler::class,'api.userEdit');
    $app->get('/api/user', App\Handler\UserFetchHandler::class, 'api.userFetch');
};
