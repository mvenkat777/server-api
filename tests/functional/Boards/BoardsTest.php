<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BoardsTest extends AbstractTestCase
{
    public function setUp()
    {
    	parent::setUp();

    	$this->prepareForTests();
    }

    public function test_it_401s_if_unauthorized()
    {
    	$this->post('/boards', [])
    		 ->assertResponseStatus(401);
    }

    public function test_it_returns_the_same_object_on_post()
    {
    	$user = $this->loginUser();
    	$userToken = $user['token'];
    	$userId = $user['userId'];

    	$this->post('/boards', $this->getSlug(['creator' => $userId]), ['access-token' => $userToken])
    		 ->seeJson($this->newItemResponseBluePrint($this->getSlug(['creator' => $userId])));
    }

    public function test_it_persists_data()
    {
        $user = $this->loginUser();
        $userToken = $user['token'];
        $userId = $user['userId'];

        $this->post('/boards', $this->getSlug(['creator' =>$userId]), ['access-token' => $userToken])
             ->seeInDatabase('boards', $this->getSlug(['creator' =>$userId]));
    }

    public function test_it_creates_unique_uuids_for_ids() 
    {
        $user = $this->loginUser();
        $userToken = $user['token'];
        $userId = $user['userId'];

        $boardOne = $this->post('/boards', $this->getSlug(['creator' =>$userId]), ['access-token' => $userToken]);
        $boardOneId = $this->getBoardId($boardOne);

        $boardTwo = $this->post('/boards', $this->getSlug([
            'name' => 'Second board', 
            'creator' =>$userId]), 
            ['access-token' => $userToken]
        );
        $boardTwoId = $this->getboardId($boardTwo);

        $this->assertNotEquals($boardOneId, $boardTwoId);
    }

    public function test_type_field_defaults_to_inspiration()
    {
    	$user = $this->loginUser();
    	$userToken = $user['token'];
    	$userId = $user['userId'];

    	$board = $this->post('/boards', $this->getSlug(['creator' =>$userId, 'type' => 'test']), ['access-token' => $userToken]);

    	$this->assertEquals($this->getBoardData($board)->type, 'inspiration');
    }

    public function test_it_invites_collaborators()
    {
    	$user = $this->loginUser();
    	$userToken = $user['token'];
    	$userId = $user['userId'];

    	$board = $this->post('/boards', $this->getSlug(['creator' =>$userId, 'type' => 'test']), ['access-token' => $userToken]);

    	$this->assertEquals($this->getBoardData($board)->type, 'inspiration');	
    }

    public function getBoardData($board)
    {
        return json_decode($board->response->getContent())->data;
    }

    public function getBoardId($board)
    {
        return json_decode($board->response->getContent())->data->id;
    }

    private function getSlug($data = [])
    {
        $slug = [
            'name' => 'Fall Inspiration',
            'description' => 'This is a inspiration for fall collections',
            'type' => 'collection',
            'creator' => 'creator',
        ];

        return array_merge($slug, $data);
    }
}
