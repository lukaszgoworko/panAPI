<?php

use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SecurityJWTServiceProvider;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\TwigServiceProvider;

$app = new Application();

$app['security.jwt'] = [
    'secret_key' => 'my_Very_secret_key',
    'life_time'  => 86400,
    'algorithm'  => ['HS256'],
    'options'    => [
        'header_name'  => 'X-Access-Token',
        'token_prefix' => 'Bearer',
    ]
];

$app['users'] = function () use ($app) {
    $users = [
        'luke' => array(
            'roles' => array('ROLE_ADMIN', 'ROLE_SUPER_ADMIN'),
            // raw password is foo
            'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
            'enabled' => true
        ),
    ];
    return new InMemoryUserProvider($users);
};

$app['security.firewalls'] = array(
    'login' => [
        'pattern' => 'login|register|oauth',
        'anonymous' => true,
    ],
    'secured' => array(
        'pattern' => '^.*$',
        'logout' => array('logout_path' => '/logout'),
        'users' => $app['users'],
        'jwt' => array(
            'use_forward' => true,
            'require_previous_session' => false,
            'stateless' => true,
        )
    ),
);

$app->register(new SecurityServiceProvider());
$app->register(new SecurityJWTServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new TwigServiceProvider());


//ACCEPT application/json
$app->before(function (Request $request, Application $app) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : ['data' => $data]);
    }
});

return $app;
