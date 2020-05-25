<?php

namespace Tests\Feature;

use App\EpicSprintRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IssueTest extends TestCase
{
    use RefreshDatabase;

    public function testCreatesIssue()
    {
        $issue = factory(\App\Issue::class)->create();

        $this->assertNotNull($issue->name);
        $this->assertNotNull($issue->seconds);
        $this->assertNotNull($issue->epic_sprint_record_id);
        $this->assertNotNull($issue->jira_key);
        $this->assertNotNull($issue->jira_id);
    }

    public function testRelation()
    {
        $issue = factory(\App\Issue::class)->create();
        $this->assertInstanceOf(
            EpicSprintRecord::class,
            $issue->epic_sprint_record
        );
    }
}
