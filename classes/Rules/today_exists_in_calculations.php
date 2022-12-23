<?php

namespace Stanford\GoProd;

class today_exists_in_calculations implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $modalTableHeader = array("Instrument", "Variable / Field Name", "Field Label", "Missing Variable", "Edit");
    public $inconsistentVariables = [];

    public $dataDictionary = [];

    private $dateTypes = array('date_ymd', 'date_mdy', 'date_dmy', 'datetime_dmy', 'datetime_mdy', 'datetime_ymd', 'datetime_seconds_mdy', 'datetime_seconds_ymd');

    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
        $this->dataDictionary = \REDCap::getDataDictionary('array');;
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
     * @return void
     */
    public function setProject(\Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function validate()
    {
        $var = array();
        $today_fields = Validations::getCalculatedFields();
        $today_list = $this->getTodayCalculations($today_fields);
        foreach ($today_list as $today) {
            $label = Validations::TextBreak($today[1]);
            $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $this->getProject()->project_id .
                '&page=' . $today[0] . '&field=' . $today[1];
            $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
            array_push($var, array(\REDCap::getInstrumentNames($today[0]), $today[1], $label, $today[2], $link_to_edit));
        }
        $this->inconsistentVariables = $var;
        if (!empty($this->inconsistentVariables)) {
            return false;
        }
        return true;
    }


    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['CALCULATED_TODAY_TITLE'],
            'body' => $this->getNotifications()['CALCULATED_TODAY_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'modal' => $this->inconsistentVariables,
            'modalHeader' => $this->modalTableHeader,
            'links' => array(),
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
     * @return void
     */
    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }

    public function getTodayCalculations($CalcFieldsArray)
    {

        //$haystack = "foo bar baz";
        $today_var = strtolower("\"today\"");
        $today_var2 = strtolower('\'today\'');
        $var = array();
        foreach ($CalcFieldsArray as $item) {
            if ((strpos(strtolower($item[2]), $today_var) !== false) or (strpos(strtolower($item[2]), $today_var2) !== false)) {
                array_push($var, $item);
            }
        }
        return $var;
    }
}