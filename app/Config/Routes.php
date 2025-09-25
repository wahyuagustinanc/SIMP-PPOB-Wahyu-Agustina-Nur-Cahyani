<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('/register', 'Auth::register');
$routes->get('/login', 'Auth::login');
$routes->post('/doLogin', 'Auth::doLogin');

$routes->get('/homepage', 'Homepage::index');
$routes->get('/topup', 'Homepage::topup');
$routes->post('/doTopup', 'Homepage::doTopup');

$routes->get('/transaction', 'Transaction::index');
$routes->get('/transaction/loadMore', 'Transaction::loadMoreHistory');
$routes->get('/pembayaran/(:segment)', 'Transaction::pembayaran/$1');
$routes->post('/pembayaran/process', 'Transaction::prosesBayar');


$routes->get('/akun', 'Akun::index');
$routes->post('/akun/update', 'Akun::updateAkun');
$routes->post('/akun/image', 'Akun::updateAkunImage');
$routes->get('/logout', 'Akun::logout');