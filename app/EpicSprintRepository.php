<?php

namespace App;

use EpicSprintRecords;
use JiraRestApi\Epic\EpicService;

class EpicSprintRepository 
{

    /**
     * @var EpicService $service
     */
    protected $service;

    protected $epic_id;

    protected $epic;

    protected $issues = [];

    public $name = false;

    public function __construct(EpicService $service)
    {
        $this->service = $service;
    }

    public function getEpicSprintAndInfo($id, $type = "epic") {
        if ($type == "epic") {
            $this->getEpicType($id);
        }
        $this->issues = $this->getIssuesFromJira();
        return $this;
    }

    protected function getEpicType($id) {
        $this->epic_id = $id;
        
        if($record = EpicSprintRecord::where("jira_key", $id)->first()) {
            $this->epic = $record;
        } else {
            $this->epic = $this->getEpicFromJira();
            EpicSprintRecord::create(
                [
                    'name' => $this->epic->name,
                    'jira_key' => $this->epic->key,
                    'jira_id' => $this->epic->id,
                    'summary' => $this->epic->summary,
                    'jira_type' => 'epic',
                    'done' => $this->epic->done
                ]
            );
        }

        $this->setPropertiesBasedOnEpic();
    }
    
    protected function setPropertiesBasedOnEpic() {
        $this->name = $this->epic->name;
    }

    protected function getEpicFromJira() {
        return $this->service->getEpic($this->epic_id);
    }

    protected function getIssuesFromJira() {
        return $this->service->getEpicIssues($this->epic_id);
    }

    public function getIssues() {
        return $this->issues;
    }

    public function getEpic() {
        return $this->epic;
    }
    
}
