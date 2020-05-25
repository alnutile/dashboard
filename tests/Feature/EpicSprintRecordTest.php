<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EpicSprintRecordTest extends TestCase
{

    use RefreshDatabase;

    public function testIssueRelations()
    {
        $issues = factory(\App\Issue::class)->create();

        $epic = $issues->epic_sprint_record;

        $this->assertInstanceOf(
            \App\Issue::class,
            $epic->issues->first()
        );
    }
}
