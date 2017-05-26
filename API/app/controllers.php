<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

//Request::setTrustedProxies(array('127.0.0.1'));

$app->get('/', function () use ($app) {
    return new Response("Hello World");
})
->bind('homepage')
;


$app->post('/login', function(Request $request) use ($app){

    try {
        if (empty($request->get('_username')) || empty($request->get('_password'))) {
            throw new UsernameNotFoundException('Invalid credentials');
        }

        $user = $app['users']->loadUserByUsername($request->get('_username'));

        if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $request->get('_password'), '')) {
          throw new UsernameNotFoundException('Invalid credentials');
        } else {
            $response = [
                'success' => true,
                'token' => $app['security.jwt.encoder']->encode(['name' => $user->getUsername()]),
            ];
        }
    } catch (UsernameNotFoundException $e) {
        $response = [
            'success' => false,
            'error' => 'Invalid credentials',
        ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
})->bind('login');

$app->get('/protected_resource', function() use ($app){
    return new JsonResponse(['hello' => 'protected world']);
});


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    $data = ['code' => $code, 'Message' => $e->getMessage()];
    return new JsonResponse($data);
});
