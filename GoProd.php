<?php
namespace Stanford\GoProd;

class GoProd extends \ExternalModules\AbstractExternalModule {
	public function __construct() {
		parent::__construct();
		// Other code to run when object is instantiated
	}
	
    public function redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance,
        $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id)
    {
        \Plugin::log(func_get_args());

        // Return is left as php object, is converted automatically
        return ["success"=>true];
    }
}