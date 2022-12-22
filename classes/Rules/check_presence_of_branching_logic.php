<?php

namespace Stanford\GoProd;

class check_presence_of_branching_logic implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $dataDictionary = [];

    public $inconsistentFields = [];

    public $modalHeader = array("Instrument", "Variable / Field Name", "Field Label", "Options/Choices", "Edit");

    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
        $this->dataDictionary = \REDCap::getDataDictionary('array');;
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
        // to ingnore smart variables
        $array_smart_variables = array("user-name",
            "user-fullname",
            "user-email",
            "user-dag-name",
            "user-dag-id",
            "user-dag-label",
            "user-role-id",
            "user-role-name",
            "user-role-label",
            "calendar-link",
            "calendar-url",
            "record-name",
            "record-dag-name",
            "record-dag-id",
            "record-dag-label",
            "is-form",
            "instrument-name",
            "instrument-label",
            "is-survey",
            "survey-queue-url",
            "event-id",
            "event-number",
            "event-name",
            "event-label",
            "previous-event-name",
            "previous-event-label",
            "next-event-name",
            "next-event-label",
            "first-event-name",
            "first-event-label",
            "last-event-name",
            "last-event-label",
            "arm-number",
            "arm-label",
            "previous-instance",
            "current-instance",
            "next-instance",
            "first-instance",
            "last-instance",
            "new-instance",
            "project-id",
            "redcap-base-url",
            "redcap-version",
            "redcap-version-url",
            "survey-base-url");
        $branching_fields = $this->getBranchingLogicFields();
        $BranchingLogicArray = Validations::ExtractVariables($branching_fields);
        $fields = \REDCap::getFieldNames();
        $fields = array_merge(Validations::AddCheckBoxes($fields), $array_smart_variables);//adding the extra Checkbox variables
        //error_log(print_r($fields, TRUE));
        foreach ($BranchingLogicArray as $variable) {
            if (!in_array($variable[2], $fields)) {


                $label = TextBreak($variable[1]);


                $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT) .
                    '&page=' . $variable[0] . '&field=' . $variable[1] . '&branching=1';
                $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'arrow_branch_side.png></a>';

                array_push($var, array(\REDCap::getInstrumentNames($variable[0]), $variable[1], $label, '<strong style="color: red">[' . $variable[2] . ']</strong>', $link_to_edit));
            }
        }


        $this->inconsistentFields = $var;
        if(!empty($this->inconsistentFields)){
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['BRANCHING_LOGIC_TITLE'],
            'body'  => $this->getNotifications()['BRANCHING_LOGIC_BODY'],
            'type'  => $this->getNotifications()['DANGER'],
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

    /**
     * @param $DataDictionary
     * @return array
     *
     * Extract the Fields with branching logic
     */
    public static function getBranchingLogicFields()
    {
        $var = array();
        // Loop through each field and do something with each
        foreach (\REDCap::getDataDictionary('array') as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if (strlen(trim($field_attributes['branching_logic'])) > 0) {
                $FormName = $field_attributes['form_name'];
                $FieldName = $field_attributes['field_name'];
                $BranchingLogic = $field_attributes['branching_logic'];
                array_push($var, array($FormName, $FieldName, $BranchingLogic));
            }
        }
        return $var;
    }
}