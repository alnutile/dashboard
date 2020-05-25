<?php

namespace Tests\Feature;

use App\EpicSprintRecord;
use ArrayObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use JiraRestApi\Epic\EpicService;
use Mockery;
use JiraRestApi\Epic\Epic;
use Tests\TestCase;

class EpicSprintCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateApi()
    {
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

        $response = $this->json('POST', '/api/epic_stories', [
            'jira_type' => "epic",
            'jira_key' => "CAP-555"           
        ]);

        $response->assertStatus(200);

        $record = EpicSprintRecord::where("jira_key", "FOO-1842")->first();

        $this->assertNotEmpty($record);
    }

}
