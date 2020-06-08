<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use JiraRestApi\Issue\AgileIssue;

class IssuesImport
{
    protected $epic = false;

    public function handle(EpicSprintRepository $epic_sprint_repo)
    {
        $this->epic = $epic_sprint_repo->getEpic();

        //iterate over items
        foreach ($epic_sprint_repo->getIssues() as $issue) {
            $this->createIssue($issue);
        }

        //save items to the db
    }

    /**
     * @var \JiraRestApi\Issue\Issue $issue
     */
    protected function createIssue($issue)
    {

        Issue::firstOrCreate(
            [
                "jira_id" => $issue->id
            ],
            [
                "epic_sprint_record_id" => $this->epic->id,
                "name" => $issue->fields->summary,
                "jira_description" => ($issue->fields->description != null) ? $issue->fields->description->content : null,
                "seconds" => $issue->fields->timeTracking->originalEstimateSeconds,
                "jira_key" => $issue->key,
                'jira_status' => $issue->fields->status->name,
            ]
        );
    }
}
