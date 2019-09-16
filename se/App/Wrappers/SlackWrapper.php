<?php

namespace Platform\App\Wrappers;

use Maknz\Slack\Client;

class SlackWrapper{

	/**
     * @var $client
     */
	protected $client;

	/**
     * @return mixed
     */
	public function __construct(){

		$settings = [
		    'username' => 'sedev',
		    'channel' => '#platform_events ',
		    'link_names' => true
		];

		$this->client = new Client(
			'https://hooks.slack.com/services/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',
			$settings
		);

	}

	/**
     * @param $eventName
     * @return mixed
     */
	public function send($eventName)
	{
		$this->setUserName();
		$this->client->send("Fired Event : {$eventName}");
		return; 	
	}

	/**
     * @return mixed
     */
	public function setUserName(){
		$user = isset(\Auth::user()->displayName) ? \Auth::user()->displayName : 'sedev';
		$this->client->setDefaultUsername($user);
		return $this;
	} 

	public function httpPost($url,$params)
	{
		// curl --data "Hello from Slackbot" $'https://my.slack.com/services/hooks/slackbot?token=oLqLmTpxS5rDKghRQe5qbd9K&channel=%23general'
	  $postData = '{"text": "This is posted to <#general> 
		and 
		comes from *monkey-bot*.", "channel": "@ashis", "username": "ashis", 
		"icon_emoji": ":monkey_face:"}';
	   $url = 'https://hooks.slack.com/services/T03MRR3UD/
		B0F940KGU/pDRrhTbwf7QH6hEzIhmbQ4Rb';
		// https://my.slack.com/services/hooks/slackbot?token=oLqLmTpxS5rDKghRQe5qbd9K&channel=%23general
	   $ch = curl_init();  
	 
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch,CURLOPT_HEADER, false); 
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	 
	    $output=curl_exec($ch);
	 
	    curl_close($ch);
	    return $output;
	 
	}
}