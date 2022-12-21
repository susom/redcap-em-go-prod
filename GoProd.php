<?php

namespace Stanford\GoProd;

require_once('classes/ValidationsImplementation.php');
require_once('classes/Validations.php');
require_once('classes/Rules/just_for_fun_project.php');

class GoProd extends \ExternalModules\AbstractExternalModule
{

    const ALL_VALIDATIONS = 'ALL_VALIDATIONS';

    /**
     * @var \Project
     */
    private $project;


    /**
     * @var array
     */
    private $notifications = [];

    /**
     * @var \Stanford\GoProd\Validations;
     */
    private $validations;

    public function __construct()
    {
        parent::__construct();
        // Other code to run when object is instantiated

        if ((isset($_GET['pid']) && $_GET['pid'] != "") || (isset($_GET['projectid']) && $_GET['projectid'] != "")) {
            global $Proj;
            $this->setProject($Proj);
            $this->setValidations(new Validations($Proj));
            $this->setEnabledRules();
        }

    }

    private function setEnabledRules()
    {
        $settings = $this->getSystemSettings();
        $rules = array();
        foreach ($settings as $name => $setting) {
            if (in_array($name, array('enabled', 'discoverable-in-project', 'user-activate-permission')) || !is_bool($setting['value'])) {
                continue;
            }
            $temp = "Stanford\GoProd\\$name";
            if ($setting['value'] && class_exists($temp)) {
                // init validation class name which is coming from config.json settings.
                /** @var \Stanford\GoProd\just_for_fun_project $obj */
                $obj = new $temp();
                $obj->__constructor($this->getProject(), $this->getNotifications());
                $rules[$name] = $obj;
            }
        }
        $this->getValidations()->setEnabledRules($rules);
    }

    public function redcap_module_ajax($action, $payload, $project_id, $record, $instrument, $event_id, $repeat_instance,
                                       $survey_hash, $response_id, $survey_queue_hash, $page, $page_full, $user_id, $group_id)
    {
        if ($action == self::ALL_VALIDATIONS) {
            $result = [];
            foreach ($this->getValidations()->getEnabledRules() as $name => $validation) {
                if (!$validation->validate()) {
                    // if rule requires to break the loop and returns only results of this rule.
                    if($validation->break){
                        return array($name => $validation->getErrorMessage());
                    }else{
                        $result[$name] = $validation->getErrorMessage();
                    }
                }
            }
            return $result;
        } elseif (array_key_exists($action, $this->getValidations()->getEnabledRules())) {
            $validation = $this->getValidations()->getEnabledRules()[$action];
            if (!$validation->validate()) {

                return $validation->getErrorMessage();
            }
        } else {
            throw new \Exception("Action  $action is not defined");
        }
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

    /**
     * @return Validations
     */
    public function getValidations(): Validations
    {
        return $this->validations;
    }

    /**
     * @param Validations $validations
     */
    public function setValidations(Validations $validations): void
    {
        $this->validations = $validations;
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


}