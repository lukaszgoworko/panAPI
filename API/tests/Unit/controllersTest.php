<?php


use Silex\WebTestCase;

class controllersTest extends WebTestCase
{
    public function testGetHomepageAuthorisationFaild()
    {
        $client = $this->createClient();
        $client->followRedirects(true);
        $client->request('GET', '/');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    public function testGetHomepageAuthorisationOK()
    {
      $client = $this->createClient();
      $client->followRedirects(true);
      $client->request('POST', '/login', ['_username'=>'luke', '_password'=>'foo']);
      $data = json_decode($client->getResponse()->getContent());
      $this->assertNotEmpty($data->token);

      $client = $this->createClient();
      $client->followRedirects(true);
      $crawler = $client->request(
        'GET',
        '/',
        array(),
        array(),
        [ 'CONTENT_TYPE'   => 'application/json',
          'HTTP_X-Access-Token' => 'Bearer ' . $data->token]);

      $this->assertTrue($client->getResponse()->isOk());
      $this->assertContains('Hello World', $crawler->filter('body')->text());
    }



    public function createApplication()
    {
        $app = require __DIR__.'/../../app/app.php';
        require __DIR__.'/../../config/dev.php';
        require __DIR__.'/../../app/controllers.php';

        return $this->app = $app;
    }
}
