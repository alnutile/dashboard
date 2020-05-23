<?php

namespace App\Http\Controllers;

use Facades\App\EpicSprintRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EpicSprintCreateController extends Controller
{
    public function __invoke(Request $request) {
        $request->validate([
                'jira_key' => "required",
                'jira_type' => "required"
            ]
        );

        try {
            $results = EpicSprintRepository::getEpicSprintAndInfo($request->jira_key);
            return response()->json($results);
        } catch(\Exception $e) {
            Log::error($e);
            abort(400, $message="Error creating Epic");
        }

    }
}
