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
use App\Issue;
use stdClass;
use Tests\TestCase;


/**
 * https://developer.atlassian.com/cloud/jira/software/rest/#api-agile-1-0-epic-epicIdOrKey-issue-get
 */
class EpicSprintRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateEpic()
    {
        //should add Epic to our system if not exist

        $this->mock(EpicService::class, function ($mock) {
            //$data = File::get(base_path("tests/fixtures/epic_issues.json"));
            //$data = json_decode($data, true);
            $data = new ArrayObject([]);
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

        $payload = [
            "number_of_devs" => 1,
            "jira_key" => "FOO-1842",
            "jira_type" => "epic",
            'due_date' => "2020-06-20"
        ];

        $client->getEpicSprintAndInfo($payload);

        $results = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($results->name);
        $this->assertNotEmpty($results->due_date);
        $this->assertNotEmpty($results->number_of_devs);
        $this->assertNotEmpty($results->jira_type);

        $this->assertEquals("This Epic Name", $results->name);

        $record = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($record);

        $this->assertEquals(0, Issue::all()->count());
    }

    public function testGetsEpicFromDbNotApi()
    {

        $epic = factory(\App\EpicSprintRecord::class)->create(
            ['jira_key' => "FOO-1842", "name" => "This Epic Name"]
        );

        $this->instance(EpicService::class, Mockery::mock(EpicService::class, function ($mock) {
            $data = new ArrayObject([]);
            /** @var Mockery::mock $mock */
            $mock->shouldReceive("getEpicIssues")->once()->andReturn($data);
            $mock->shouldReceive('getEpic')->never();
        }));

        $client = App::make(EpicSprintRepository::class);

        $client->getEpicSprintAndInfo(['jira_key' => "FOO-1842"]);

        $results = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($results->name);

        $this->assertEquals("This Epic Name", $results->name);

        $record = EpicSprintRecord::where("jira_key", "FOO-1842")->first();
    }
}
