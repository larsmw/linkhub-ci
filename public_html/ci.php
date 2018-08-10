<?php

class Request {
  private $client_ip;
  private $trusted_proxies;
  private $post_data;

  public function __construct() {

    $this->trusted_proxies = ["127.0.0.1"];

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
      AND isset($_SERVER['REMOTE_ADDR'])
      AND in_array($_SERVER['REMOTE_ADDR'], $this->trusted_proxies))
    {
      // Use the forwarded IP address, typically set when the
      // client is using a proxy server.
      // Format: "X-Forwarded-For: client1, proxy1, proxy2"
      $client_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

      $this->client_ip = array_shift($client_ips);

      unset($client_ips);
    }
    elseif (isset($_SERVER['HTTP_CLIENT_IP'])
      AND isset($_SERVER['REMOTE_ADDR'])
      AND in_array($_SERVER['REMOTE_ADDR'], Request::$trusted_proxies))
    {
      // Use the forwarded IP address, typically set when the
      // client is using a proxy server.
      $client_ips = explode(',', $_SERVER['HTTP_CLIENT_IP']);

      $this->client_ip = array_shift($client_ips);

      unset($client_ips);
    }
    elseif (isset($_SERVER['REMOTE_ADDR']))
    {
      // The remote IP address
      $this->client_ip = $_SERVER['REMOTE_ADDR'];
    }

    $this->post_data = file_get_contents('php://input');
  }

  public function getIp() {
    return $this->client_ip;
  }

  public function getPostdata() {
    return $this->post_data;
  }
}

echo "CI started...\n";
$req = new Request();
error_log(var_export($req->getIp(), true));
error_log(var_export($req->getPostdata(), true));
var_dump($req->getIp());
var_dump($req->getIp());
