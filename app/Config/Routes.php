<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Quiz::index');
$routes->get('/solution/(:segment)', 'Quiz::solution/$1');
$routes->get('/gallery', 'Home::gallery');
$routes->get('/blog', 'Home::blog');
$routes->get('/blog/(:any)', 'Home::blogD/$1');
$routes->get('/tests', 'Home::tests');

// $routes->get('/', 'Home::index');
$routes->get('/access', 'Quiz::quizcode');
$routes->get('/quiz', 'Quiz::processcode');
$routes->get('/login', 'Quiz::login');
$routes->get('/logout', 'Quiz::logout');
$routes->get('/questions', 'Quiz::questions');
$routes->get('/solution/(:any)', 'Quiz::solution/$1');
$routes->get('/gensol/(:any)', 'Quiz::genSolution/$1');
$routes->get('/test/(:segment)/(:num)', 'Quiz::test/$1/$2');
$routes->get('/test/(:segment)', 'Quiz::test/$1');
$routes->post('/quizlet', 'Quiz::postquiz');
$routes->post('/login', 'Quiz::postlogin');
$routes->post('/rlogin/(:segment)', 'Quiz::postrlogin/$1');

    $routes->get('adminer/', 'Adminer::index');
    $routes->get('adminer/db', 'Adminer::dashboard');
    $routes->get('adminer/reg', 'Adminer::registrations');
    $routes->post('adminer/login', 'Adminer::postlogin');
    $routes->get('adminer/logout', 'Adminer::logout');
    $routes->get('adminer/delete/(:segment)', 'Adminer::deletereg/$1');


    $routes->get('adminer/que', 'Adminer::questions');
    $routes->get('adminer/testss', 'Adminer::testss');
    $routes->get('adminer/sque', 'Adminer::ssquestions');
    $routes->get('adminer/editque', 'Adminer::editquestions');
    $routes->get('adminer/sendscores', 'Adminer::sendscores');
    $routes->get('adminer/quizinput', 'Adminer::quizinput');
    $routes->get('adminer/cards', 'Adminer::cards');
    $routes->post('adminer/updatequestions', 'Adminer::updatequestions');
    $routes->post('adminer/questions', 'Adminer::postquestions');
    $routes->post('adminer/squestions', 'Adminer::postquestionss');
    $routes->post('adminer/getsc', 'Adminer::getscoresheet');

// $routes->group('adminer', static function ($routes) {
//     $routes->get('/', 'Adminer::index');
//     $routes->get('/db', 'Adminer::dashboard');
//     $routes->get('/reg', 'Adminer::registrations');
//     $routes->post('/login', 'Adminer::postlogin');
//     $routes->get('/logout', 'Adminer::logout');
//     $routes->get('/delete/(:segment)', 'Adminer::deletereg/$1');


//     $routes->get('/que', 'Adminer::questions');
//     $routes->get('/testss', 'Adminer::testss');
//     $routes->get('/sque', 'Adminer::ssquestions');
//     $routes->get('/editque', 'Adminer::editquestions');
//     $routes->get('/sendscores', 'Adminer::sendscores');
//     $routes->get('/quizinput', 'Adminer::quizinput');
//     $routes->get('/cards', 'Adminer::cards');
//     $routes->post('/updatequestions', 'Adminer::updatequestions');
//     $routes->post('/questions', 'Adminer::postquestions');
//     $routes->post('/squestions', 'Adminer::postquestionss');
//     $routes->post('/getsc', 'Adminer::getscoresheet');
// });

$routes->get('/(:any)', 'Home::pages/$1');
