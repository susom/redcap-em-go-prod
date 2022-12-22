<?php

namespace Stanford\GoProd;

class asi_logic_variables_exists implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    public $inconsistentReports = [];
    public $modalHeader = array("Report Title", "Real Time Executed?", "Missing Variable", "Edit");
    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
    }

    public function getProject(): \Project
    {
        return $this->project;
    }

    public function setProject(\Project $project): void
    {
        $this->project = $project;
    }

    public function validate(): bool
    {
        $var = array();
        $logic_fields = self::ExtractASILogic();
        $logic_fields_array = Validations::ExtractVariables($logic_fields);
        $fields = \REDCap::getFieldNames();
        $fields = Validations::AddCheckBoxes($fields);//adding the extra Checkbox variables
        foreach ($logic_fields_array as $variable) {
            if (!in_array($variable[2], $fields)) {
                $event = \REDCap::getEventNames(false, true, $variable[1]);
                if (!$event) {
                    $event = '-';
                }
                $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);
                $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                array_push($var, array(\REDCap::getInstrumentNames($variable[0]), $event, '<strong style="color: red">[' . $variable[2] . ']</strong>', $link_to_edit));
            }
        }
        $this->inconsistentReports = $var;
        if(!empty($this->inconsistentReports)){
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['ASI_LOGIC_TITLE'],
            'body'  => $this->getNotifications()['ASI_LOGIC_BODY'],
            'type'  => $this->getNotifications()['DANGER'],
            'links' => array(),
            'modal' => $this->inconsistentReports,
            'modalHeader' => $this->modalHeader
        );
    }

    /**
     * @return array
     */
    public function getNotifications(): array
    {
        return $this->notifications;
    }

    /**
     * @param array $notifications
     */
    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function ExtractASILogic()
    {

        $var = array();
        $sql = "SELECT 
	              SRV.form_name as form, RSS.event_id as event_id,  RSS.condition_logic as logic 
                FROM 
	              redcap_surveys as SRV, redcap_surveys_scheduler as RSS 
                WHERE
	               RSS.condition_logic IS NOT NULL and RSS.active=1 and SRV.survey_id=RSS.survey_id and SRV.project_id=" . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);
        $result = db_query($sql);
        while ($query_res = db_fetch_assoc($result)) {
            array_push($var, array($query_res['form'], $query_res['event_id'], $query_res['logic']));
        }
        return $var;
    }
}