<?php

namespace Stanford\GoProd;

class data_quality_logic_variables_exists implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    public $inconsistentFields = [];

    public $modalHeader = array("Instrument", "Variable / Field Name", "Field Label", "Options/Choices", "Edit");

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
        $logic_fields = self::ExtractDataQualityLogic();
        $logic_fields_array = Validations::ExtractVariables($logic_fields);
        $fields = \REDCap::getFieldNames();
        $fields = Validations::AddCheckBoxes($fields);//adding the extra Checkbox variables
        foreach ($logic_fields_array as $variable) {
            if (!in_array($variable[2], $fields)) {
                $link_path = APP_PATH_WEBROOT . 'DataQuality/index.php?pid=' . $this->getProject()->project_id;
                $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                array_push($var, array($variable[0], $variable[1], '<strong style="color: red">[' . $variable[2] . ']</strong>', $link_to_edit));
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
            'title' => $this->getNotifications()['DATA_QUALITY_LOGIC_TITLE'],
            'body' => $this->getNotifications()['DATA_QUALITY_LOGIC_BODY'],
            'type' => $this->getNotifications()['WARNING'],
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
}