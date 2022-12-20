<?php

namespace Stanford\GoProd;

class GoProd extends \ExternalModules\AbstractExternalModule
{

    /**
     * @var \Project
     */
    private $project;


    /**
     * @var array
     */
    private $notifications = [];

    public function __construct()
    {
        parent::__construct();
        // Other code to run when object is instantiated

        if (isset($_GET['pid']) || isset($_GET['projectid'])) {
            global $Proj;
            $this->setProject($Proj);
        }
    }

    public function redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance,
                                       $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id)
    {
        \Plugin::log(func_get_args());

        // Return is left as php object, is converted automatically
        return ["success" => true];
    }

    /**
     * @return \Project
     */
    public function getProject(): \Project
    {
        return $this->project;
    }

    /**
     * @param \Project $project
     */
    public function setProject(\Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        if (!$this->notifications) {
            $this->setNotifications();
        }
        return $this->notifications;
    }

    /**
     * @param array $notifications
     */
    public function setNotifications(): void
    {
        $path = dirname(__DIR__) . '/' . $this->PREFIX . '_' . $this->VERSION . "/language/notifications.ini";
        $temp = parse_ini_file($path);
        $temp['YES'] = 'Yes';
        $temp['NO'] = 'No';
        $this->notifications = $temp;;
    }
}