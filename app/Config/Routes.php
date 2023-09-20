<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Sementara arahkan ke Preview kasir dulu
$routes->get('/store/previewkasir', 'Store::previewkasir');
$routes->get('/store/cekpromo', 'Store::cekpromo');
$routes->get('/store/monitoringpromo/(:segment)', 'Store::tampildatapromo/$1');
$routes->get('/store/transaksiproduk/transaksi', 'Store::transaksi');   
$routes->post('/store/kompetisikasir/tampilkompkasir', 'Store::tampilkompkasir');
$routes->post('/store/slklik/tampilslklik', 'Store::tampilslklik');
$routes->get('/store/slklik/detailpbklik', 'Store::detailpbklik');
$routes->get('/store/kompetisikasir/detailitemfokus', 'Store::detailitemfokus');

$routes->post('/logistik/stokdep/tampilstokdep', 'Logistik::tampilstokdep');

$routes->post('/omi/monitoringpbomi/tampilmonitoringomi', 'Omi::tampilmonitoringomi');
$routes->post('/omi/cekprosespbomi', 'Omi::cekprosespbomi');
$routes->post('/omi/historybkl/tampilbkl', 'Omi::tampilbkl');
$routes->post('/omi/slomi/tampilslomi', 'Omi::tampilslomi');

$routes->get('/member/cekmember/(:segment)', 'Member::tampildatatransaksi/$1');
$routes->get('/member/salesmember/tampilsalesmember', 'Member::tampilsalesmember');
$routes->post('/member/salesperdep/tampilsalesperdep', 'Member::tampilsalesperdep');

$routes->get('/logistik/lppvsplanodetail/(:segment)', 'Logistik::tampildatalppplanodetail/$1');

$routes->get('/mplano/spbo', 'Mplano::spbo');



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
