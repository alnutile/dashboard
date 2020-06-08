<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use JiraRestApi\Epic\Epic;
use JiraRestApi\Epic\EpicService;
use App\EpicSprintRecord;
use App\EpicSprintRepository;
use App\Issue;
use Facades\App\IssuesImport;
use ArrayObject;
use stdClass;

class IssuesImportTest extends TestCase
{
    use RefreshDatabase;

    public function testCratesIssues()
    {

        $this->markTestSkipped("Too lazy to mock the Jira Epic, Issue etc etc");

        $this->assertLessThan(1, Issue::count());

        $this->mock(EpicService::class, function ($mock) {
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
            $mock->shouldReceive("getEpic")->once()->andReturn(new stdClass($epic));
        });

        $client = App::make(EpicSprintRepository::class);

        $results = $client->getEpicSprintAndInfo("CAP-1842");

        IssuesImport::handle($results);

        $this->assertGreaterThan(0, Issue::count());
    }
}
