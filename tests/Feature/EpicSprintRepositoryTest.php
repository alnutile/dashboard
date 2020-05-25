<?php

namespace Tests\Feature;

use App\EpicSprintRecord;
use App\EpicSprintRepository;
use ArrayObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use JiraRestApi\Epic\Epic;
use JiraRestApi\Epic\EpicService;
use Mockery;
use stdClass;
use Tests\TestCase;


/**
 * https://developer.atlassian.com/cloud/jira/software/rest/#api-agile-1-0-epic-epicIdOrKey-issue-get
 */
class EpicSprintRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEpic() {
        //should add Epic to our system if not exist

        $this->mock(EpicService::class, function($mock) {
            $data = File::get(base_path("tests/fixtures/epic_issues.json"));
            $data = json_decode($data, true);
            $data = new ArrayObject($data);
            /** @var  Mockery::mock $mock */
            $mock->shouldReceive("getEpicIssues")->once()->andReturn($data);
            $data = File::get(base_path("tests/fixtures/epic.json"));
            $data = json_decode($data, true);
            $epic = new Epic();
            $epic->name = $data['name'];
            $epic->done = $data['done'];
            $epic->key = $data['key'];
            $epic->id = $data['id'];
            $epic->summary = $data['summary'];
            $mock->shouldReceive("getEpic")->once()->andReturn($epic);
        });

        $client = App::make(EpicSprintRepository::class);

        $results = $client->getEpicSprintAndInfo("FOO-1842");
        
        $this->assertNotEmpty($results->name);
        
        $this->assertEquals("This Epic Name", $results->name);
        
        $record = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($record);
    }

    public function testGetsEpicFromDbNotApi() {

        factory(\App\EpicSprintRecord::class)->create(
            ['jira_key' => "FOO-1842", "name" => "This Epic Name"]
        );

        $this->instance(EpicService::class, Mockery::mock(EpicService::class, function ($mock) {
            $data = File::get(base_path("tests/fixtures/epic_issues.json"));
            $data = json_decode($data, true);
            $data = new ArrayObject($data);
            /** @var Mockery::mock $mock */
            $mock->shouldReceive("getEpicIssues")->once()->andReturn($data);
            $mock->shouldReceive('getEpic')->never();
        }));  

        $client = App::make(EpicSprintRepository::class);

        $results = $client->getEpicSprintAndInfo("FOO-1842");
        
        $this->assertNotEmpty($results->name);
        
        $this->assertEquals("This Epic Name", $results->name);
        
        $record = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($record);

    }

    public function testHasIssuesWithEpic() {
        ///
    }

    public function testDefaultEpicGetter()
    {
        $data = File::get(base_path("tests/fixtures/epic_issues.json"));
        $data = json_decode($data, true);
        $data = new ArrayObject($data);
        //instantiate

        $this->mock(EpicService::class, function($mock) use ($data) {
            $mock->shouldReceive("getEpicIssues")->once()->andReturn($data);
        });

        $client = App::make(EpicSprintRepository::class);

        $results = $client->getEpicAndInfo("FOO-1842");

        $this->assertNotEmpty($results->getIssues());
      
        //get info using jira client
        //take results and save to db 
        
    }

}
