<?php

abstract class AbstractTestCase extends TestCase
{
	protected function loginUser()
	{
		$user = factory(App\User::class)->create()->toArray();
		$user['password'] = 'test';
		return $this->post('/auth', $user, ['HTTP_USER_AGENT' => 'CHROME'])
					->response
					->getOriginalContent();
	}

	protected function prepareForTests()
	{
		putenv('DB_CONNECTION=sqlite');
	    Artisan::call('migrate');
	    Mail::pretend(true);
	}

	protected function newItemResponseBluePrint($data)
	{
		$response['data'] = $data;
		return json_decode('{' . json_encode($response) . '}', true);

	}
}