<?php

namespace Stanford\GoProd;

class check_presence_of_calculated_fields implements ValidationsImplementation
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
        $var= array();
        $calculated_fields=Validations::getCalculatedFields();
        $calculated_fields_array=Validations::ExtractVariables($calculated_fields);
        $fields = \REDCap::getFieldNames();
        $fields= Validations::AddCheckBoxes($fields);//adding the extra Checkbox variables
        foreach ($calculated_fields_array as $variable){
            if(!in_array($variable[2],$fields) && strpos($variable[2],"-") === false){
                $label=TextBreak($variable[1]);
                $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $this->getProject()->project_id .
                    '&page=' . $variable[0] . '&field=' . $variable[1];
                $link_to_edit='<a href='.$link_path.' target="_blank" ><img src='.APP_PATH_IMAGES.'pencil.png></a>';
                array_push( $var, Array(\REDCap::getInstrumentNames($variable[0]),$variable[1],$label,'<strong style="color: red">['.$variable[2].']</strong>',$link_to_edit));
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
            'title' => $this->getNotifications()['CALCULATED_FIELDS_TITLE'],
            'body'  => $this->getNotifications()['CALCULATED_FIELDS_BODY'],
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