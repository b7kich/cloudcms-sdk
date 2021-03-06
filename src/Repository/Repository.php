<?php

namespace Idealley\CloudCmsSDK\Repository;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;

abstract class Repository{

  function __construct($client, $headers, $baseUrl, $deploymentUrl, $repositoryId, $branch)
    {
        $this->client = $client;
        $this->headers = $headers;
        $this->baseUrl = $baseUrl;
        //We use this temporarily until we can proxy requests
        $this->deploymentUrl = $deploymentUrl;
        $this->repositoryId = $repositoryId;
        $this->branch = $branch;
        $this->params = null;
    }

	/**
  * Setting the params for the request
  *
  * @param  array $params
  */
  public function addParams($params){

    foreach($params as $key => $value){
      if(is_null($this->params)){
        $this->params .= '?'.$key.'='.$value;
      } else {
         $this->params .= '&'.$key.'='.$value; 
      }
    }

    return $this;

  }

  public function get($array = true){

    if($this->method == 'GET') {
      $this->payload = null;
    }

    $request = new Request($this->method, $this->request.$this->params, $this->headers, $this->payload);

    try {
      $response = $this->client->send($request);
      $body = $response->getBody()->getContents();
      return json_decode($body, $array); 
    } catch (ClientException $e) {
        $error = $e->getResponse();
        $type = json_decode($error->getBody()->getContents())->error;
        return $type;
    }
    
     
  }

  public function set(){
    return $this->imageUrl.$this->params;
  }

}