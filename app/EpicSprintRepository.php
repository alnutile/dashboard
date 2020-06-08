<?php

namespace App;

use EpicSprintRecords;
use JiraRestApi\Epic\EpicService;
use Facades\App\IssuesImport;
use Illuminate\Support\Arr;

class EpicSprintRepository
{

    /**
     * @var EpicService $service
     */
    protected $service;

    protected $epic_id;

    protected $epic;

    protected $payload = [];

    protected $issues = [];

    public $name = false;

    public function __construct(EpicService $service)
    {
        $this->service = $service;
    }

    public function getEpicSprintAndInfo($payload)
    {
        $this->payload = $payload;

        if ($this->getFromPayload('jira_type', 'epic') == "epic") {
            $this->getEpicType($this->getFromPayload("jira_key"));
            $this->issues = $this->getIssuesFromJira();
            IssuesImport::handle($this);
        }

        return $this;
    }

    protected function getEpicType($id)
    {
        $this->epic_id = $id;

        if ($record = EpicSprintRecord::where("jira_key", $id)->first()) {
            $this->epic = $record;
        } else {
            $this->epic = $this->getEpicFromJira($id);

            EpicSprintRecord::create(
                [
                    'name' => $this->epic->name,
                    'jira_key' => $this->epic->key,
                    'jira_id' => $this->epic->id,
                    'summary' => $this->epic->summary,
                    'jira_type' => 'epic',
                    'number_of_devs' => $this->getFromPayload("number_of_devs", 0),
                    'due_date' => $this->getFromPayload("due_date"),
                    'done' => $this->epic->done
                ]
            );
        }

        $this->setPropertiesBasedOnEpic();
    }

    protected function getFromPayload($key, $default = null)
    {
        return Arr::get($this->payload, $key, $default);
    }

    protected function setPropertiesBasedOnEpic()
    {
        $this->name = $this->epic->name;
    }

    protected function getEpicFromJira()
    {
        return $this->service->getEpic($this->epic_id);
    }

    protected function getIssuesFromJira()
    {
        return $this->service->getEpicIssues($this->epic_id);
    }

    public function getIssues()
    {
        return $this->issues;
    }

    public function getEpic()
    {
        return $this->epic;
    }
}
