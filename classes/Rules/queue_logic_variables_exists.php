<?php

namespace Stanford\GoProd;

class queue_logic_variables_exists implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    public $inconsistentFields = [];

    public $modalHeader = array("Instrument", "Event Name (if Longitudinal)", "Missing Variable", "Edit");

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
        $logic_fields = $this->ExtractQueueLogic();
        $logic_fields_array = Validations::ExtractVariables($logic_fields);
        $fields = \REDCap::getFieldNames();
        $fields = Validations::AddCheckBoxes($fields);//adding the extra Checkbox variables
        foreach ($logic_fields_array as $variable) {
            if (!in_array($variable[2], $fields)) {
                $event = \REDCap::getEventNames(false, true, $variable[1]);
                if (!$event) {
                    $event = '-';
                }
                $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $this->getProject()->project_id;
                $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                array_push($var, array(\REDCap::getInstrumentNames($variable[0]), $event, '<strong style="color: red">[' . $variable[2] . ']</strong>', $link_to_edit));
            }
        }
        $this->inconsistentFields = $var;
        if (!empty($this->inconsistentFields)) {
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['QUEUE_LOGIC_TITLE'],
            'body' => $this->getNotifications()['QUEUE_LOGIC_TITLEBODY'],
            'type' => $this->getNotifications()['DANGER'],
            'links' => array(),
            'modal' => $this->inconsistentFields,
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

    public function ExtractDataQualityLogic()
    {
        $var = array();
        $sql = "SELECT  rule_name as name, real_time_execute as rtime, rule_logic as logic FROM redcap_data_quality_rules " .
            "as DQR where DQR.rule_logic IS NOT NULL and DQR.project_id=" . $this->getProject()->project_id;
        $result = db_query($sql);
        while ($query_res = db_fetch_assoc($result)) {

            $val = $query_res['rtime'] == 1 ? lang("YES") : lang("NO");

            array_push($var, array($query_res['name'], $val, $query_res['logic']));
        }
        return $var;
    }

    public function ExtractQueueLogic()
    { //TODO: for some reason in some projects the query returns an extra logic variable that does not exist and is created by REDCap.. (partially fixed). Update: looks like REDCap is not deleting (removing) the queue logic when is deactivated  in the survey_queue_setup.php (send Ticket to consortium)

        $var = array();
        /* $sql = "SELECT
                   SRV.form_name as form,  RSQ.event_id as event_id, RSQ.condition_logic as logic
                 FROM
                   redcap_surveys as SRV, redcap_surveys_queue as RSQ
                 WHERE
                    RSQ.condition_logic IS NOT NULL and RSQ.active=1 and SRV.survey_id=RSQ.survey_id and SRV.project_id=".$_GET['pid'];*/
        $sql = "SELECT
                      rs.form_name as form,  rsq.event_id as event_id, rsq.condition_logic as logic 
                    FROM
                      redcap_surveys_queue rsq
                      join redcap_surveys rs on rsq.survey_id = rs.survey_id
                    WHERE
                       rsq.active=1
                      AND rs.form_name in (
                        SELECT
                          DISTINCT rm.form_name
                        FROM
                          redcap_metadata rm
                        WHERE
                          rm.project_id = rs.project_id
                      ) and rs.project_id =" . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);

        $result = db_query($sql);
        while ($query_res = db_fetch_assoc($result)) {
            array_push($var, array($query_res['form'], $query_res['event_id'], $query_res['logic']));
        }
        return $var;
    }
}