<?php

namespace Stanford\GoProd;

class are_dates_consistent implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $modalTableHeader = array("Instrument", "Variable / Field Name", "Field Label", "Date Format", "Edit");
    public $inconsistentDates = [];

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
        $array = array();
        $array = $this->getDateQuestions();
        $array = $this->FindDateConsistencyProblems($array);
        if (!empty($array)) {
            $this->inconsistentDates = $array;
            return false;
        }
        return true;
    }

    public function FindDateConsistencyProblems($array)
    {
        $pid = $this->getProject()->project_id;
        $FilteredOut = array();
        foreach ($array as $item1) {
            foreach ($array as $item2) {

                if ($item1[3] != $item2[3] and !in_array($item1, $FilteredOut)) {

                    $link_path1 = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $pid . '&page=' . $item1[0] . '&field=' . $item1[1];
                    $link_path2 = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $pid . '&page=' . $item2[0] . '&field=' . $item2[1];
                    $link_to_edit1 = '<a href=' . $link_path1 . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                    $link_to_edit2 = '<a href=' . $link_path2 . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';


                    $label1 = Validations::TextBreak($item1[1]);
                    $label2 = Validations::TextBreak($item2[1]);


                    array_push($FilteredOut, array($item1[0], $item1[1], $label1, '<strong style="color: red">' . $item1[3] . '</strong>', $link_to_edit1), array($item2[0], $item2[1], $label2, '<strong style="color: red">' . $item2[3] . '</strong>', $link_to_edit2));
                    break;
                }

            }
            if (!empty($FilteredOut)) {

                break;
            }


        }

        return array_map("unserialize", array_unique(array_map("serialize", $FilteredOut))); //return just the unique values found
    }

    public function getDateQuestions()
    {
        $var = array();
        // Loop through each field and do something with each
        foreach ($this->dataDictionary as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if ($field_attributes['field_type'] == "text" and in_array($field_attributes['text_validation_type_or_show_slider_number'], $this->dateTypes)) {
                $FormName = $field_attributes['form_name'];
                $FieldName = $field_attributes['field_name'];
                $DateFormatLong = $field_attributes['text_validation_type_or_show_slider_number'];
                $DateFormatShort = substr($field_attributes['text_validation_type_or_show_slider_number'], -3);
                array_push($var, array($FormName, $FieldName, $DateFormatLong, $DateFormatShort));
            }
        }
        return $var;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['DATE_CONSISTENT_TITLE'],
            'body' => $this->getNotifications()['DATE_CONSISTENT_BDOY'],
            'type' => $this->getNotifications()['WARNING'],
            'modal' => $this->inconsistentDates,
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
}