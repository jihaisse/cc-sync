<?php

namespace ChambeCarnet\WeezEvent\Api;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of Client
 *
 * @author Jérôme Fath
 */
class Client 
{
    protected $apiKey;
    
    protected $accessToken;
    
    protected $client;
    
    public function __construct() 
    {
        $this->client = new GuzzleClient(['base_uri' => 'https://api.weezevent.com/']);
        $this->apiKey = CC_WEEZEVENT_KEY;
        $username = CC_WEEZEVENT_USER;
        $password = CC_WEEZEVENT_PWD;
        
        $response = $this->client->post('auth/access_token', [
            'form_params' => [
                'api_key' => $this->apiKey,
                'username' => $username,
                'password' => $password
            ]
        ]);

        $this->accessToken = $this->decode($response)->accessToken;
    }
    
    public function getParticipants(array $parameters)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'access_token' => $this->accessToken,
            'api_key'      => $this->apiKey,
            'id_event'     => [],
            'full'         => 1
        ]);
        $resolver->setAllowedTypes('id_event', ['array']);

        $response = $this->client->get('participants', [
            'query' => $resolver->resolve($parameters)
        ]);
        
        return $this->decode($response)->participants;
    }
    
    public function getEvents()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'access_token'          => $this->accessToken,
            'api_key'               => $this->apiKey,
            'include_not_published' => false,
            'include_closed'        => false,
            'include_without_sales' => false
        ]);
        
        $response = $this->client->get('events', [
            'query' => $resolver->resolve()
        ]);
        
        return $this->decode($response)->events;
    }
    
    public function getEvent($idEvent)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'access_token' => $this->accessToken,
            'api_key'      => $this->apiKey
        ]);
        
        $response = $this->client->get('event/'.$idEvent.'/details', [
            'query' => $resolver->resolve()
        ]);
        
        return $this->decode($response)->events;
    }
    
    protected function decode(ResponseInterface $response)
    {
        return json_decode($response->getBody()->getContents());
    }
}
