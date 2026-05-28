<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman utama
$routes->get('/', 'HomeController::index');

// Halaman peta (terpisah dari landing page)
$routes->get('/peta', 'PetaController::index');

// CRUD Puskesmas (Admin)
$routes->get('/admin/puskesmas',           'PuskesmasController::index');
$routes->get('/admin/puskesmas/create',    'PuskesmasController::create');
$routes->post('/admin/puskesmas/store',    'PuskesmasController::store');
$routes->get('/admin/puskesmas/edit/(:num)',   'PuskesmasController::edit/$1');
$routes->post('/admin/puskesmas/update/(:num)', 'PuskesmasController::update/$1');
$routes->get('/admin/puskesmas/delete/(:num)', 'PuskesmasController::delete/$1');

// API endpoint — dipakai Leaflet/JS di frontend
$routes->get('/api/puskesmas',             'PuskesmasController::apiAll');
$routes->get('/api/spatial/terdekat',      'SpatialController::terdekat');
$routes->get('/api/spatial/radius',        'SpatialController::radius');