<?php
/**
 * Created by PhpStorm.
 * User: eftakhairul
 * Date: 6/23/14
 * Time: 11:46 AM
 */

namespace CRM\Voice\Model;

require_once __DIR__ . '/../../../lib/plivo/plivo.php';

class PlivoClient
{

    protected $authId;

    protected $authToken;

    protected $plivoRestClient;


    public function __construct($auth_id, $auth_token)
    {
        $this->authId           = $auth_id;
        $this->authToken        = $auth_token;

        $this->plivoRestClient  = new \RestAPI($auth_id, $auth_token);
    }

	public function broadcastVoice($to_number, $formNumber)
	{
        // Make Call
    $params = array(
            'to' => '14389852516',
            'from' => '16028122587',
            'ring_url' => 'http://example.com/ring_url',
            'answer_url' => 'http://www.cooksafe.com/answer.php',
            'hangup_url' => 'http://example.com/hangup_url',
        );

        $response = $this->plivoRestClient->make_call($params);
	}

    public function broadcastPlaySound()
    {
        	$r = new \Response();
            $r->addPlay('');


            $attributes = array (
                'loop' => 2,
            );


            $r->addPlay('https://dl.dropboxusercontent.com/u/6886596/good.wav', $attributes);

            $wait_attribute = array(
                'length' => 3,
            );
            $r->addWait($wait_attribute);

            header('Content-type: text/xml');
            echo( $r->toXML());
    }
} 