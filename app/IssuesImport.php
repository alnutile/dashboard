<?php

namespace App;

use Illuminate\Support\Arr;

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

    protected function createIssue($issue)
    {
        Issue::firstOrCreate(
            [
                "jira_id" => Arr::get($issue, 'id')
            ],
            [
                "epic_sprint_record_id" => $this->epic->id,
                "name" => Arr::get($issue, 'fields.description'),
                "jira_description" => Arr::get($issue, 'fields.description'),
                "seconds" => Arr::get($issue, 'fields.timetracking.originalEstimateSeconds'),
                "jira_key" => Arr::get($issue, 'key'),
            ]
        );
    }
}
