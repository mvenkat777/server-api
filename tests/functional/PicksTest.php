<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class PicksTest extends AbstractTestCase
{

    public function setUp()
    {
        parent::setUp();
     
        $this->prepareForTests();
    }

    public function test_it_401s_if_unauthorized()
    {
        $this->post('/picks', [])
             ->assertResponseStatus(401);
    }

    public function test_it_returns_the_same_object_on_post()
    {
        $user = $this->loginUser();
        $userToken = $user['token'];
        $userId = $user['userId'];
        
        $this->post('/picks', $this->getSlug(['pickedBy' =>$userId]), ['access-token' => $userToken])
             ->seeJson($this->newItemResponseBluePrint($this->getSlug(['pickedBy' =>$userId])));
    }

    public function test_it_persists_data()
    {
        $user = $this->loginUser();
        $userToken = $user['token'];
        $userId = $user['userId'];

        $this->post('/picks', $this->getSlug(['pickedBy' =>$userId]), ['access-token' => $userToken])
             ->seeInDatabase('picks', $this->getSlug(['pickedBy' =>$userId]));
    }

    public function test_it_creates_unique_uuids_for_ids() 
    {
        $user = $this->loginUser();
        $userToken = $user['token'];
        $userId = $user['userId'];

        $pickOne = $this->post('/picks', $this->getSlug(['pickedBy' =>$userId]), ['access-token' => $userToken]);
        $pickOneId = $this->getPickId($pickOne);

        $pickTwo = $this->post('/picks', $this->getSlug([
            'title' => 'Second Pick', 
            'pickedBy' =>$userId]), 
            ['access-token' => $userToken]
        );
        $pickTwoId = $this->getPickId($pickTwo);

        $this->assertNotEquals($pickOneId, $pickTwoId);
    }

    public function getPickId($pick)
    {
        return json_decode($pick->response->getContent())->data->id;
    }
    
    private function getSlug($data = [])
    {
        $slug = [
            'image' => 'imagePath',
            'title' => 'The title',
            'description' => 'The Description',
            'pickedBy' => '234234',
        ];

        return array_merge($slug, $data);
    }

    
}
