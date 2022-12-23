<?php

namespace Stanford\GoProd;

use ExternalModules\ExternalModules;

class check_other_or_unknown implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $otherWords = "Other, Unknown, Don't know/Not sure ,Don't know,Not sure, Not Reported, NA, N/A, uninterpretable, otro, otra, no se, Other please specify, not obtained, missing data, do not know or not sure, refused, no response was entered on form despite affirming that the patient was untestable, no response was entered on form despite affirming that the patient was testable, did not provide answer / not answered, other type, unclear, not gradable, other frequency, sent or stored other, no data available, unable to examine ";

    public $ids = "97,88,98,99,999,9999,888,8888,-1,777,7777";

    private $similarity = 95;

    public $modalHeader = array("Instrument", "Variable / Field Name", "Field Label", "Options/Choices", "Edit");

    public $inconsistentFields = [];

    public $prefix;

    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
        $this->similarity = ExternalModules::getSystemSetting($this->prefix, 'check_other_or_unknown_similarity');
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
        // We have our unique instrument name
        $List = $this->getLists();
        $REDCapList = Validations::Transform($List);
        $AllOther = $this->FindOtherOrUnknown($REDCapList, $this->otherWords, $this->similarity);
        $this->inconsistentFields = $this->ListOfOtherOrUnknownWithProblems($AllOther, $this->ids);
        if(!empty($this->inconsistentFields)){
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['YES_NO_TITLE'],
            'body' => $this->getNotifications()['YES_NO_BODY'],
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

    /**
     * @param $DataDictionary
     * @return array
     * //Get dropdown, Checkbox  and Radio buttons from the REDCap Data Dictionary
     */
    public static function getLists()
    {
        $var = array();
        $DataDictionary = \REDCap::getDataDictionary('array');
        // Loop through each field and do something with each
        foreach ($DataDictionary as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if ($field_attributes['field_type'] == "checkbox" || $field_attributes['field_type'] == "radio" || $field_attributes['field_type'] == "dropdown") {
                $FormName = $field_attributes['form_name'];
                $FieldName = $field_attributes['field_name'];
                $Choices = $field_attributes['select_choices_or_calculations'];
                array_push($var, array($FormName, $FieldName, $Choices));
            }
        }
        return $var;
    }

    public function EvaluateSimilarity($string1, $string2, $percentage)
    {

        // first preparing the strings.
        //for $string1
        //remove spaces at the end and convert in lowercase
        $word1 = trim(strtolower($string1));
        //remove spaces between words
        $word1 = str_replace(' ', '_', $word1);
        //remove tabs
        $word1 = preg_replace('/\s+/', '_', $word1);
        $word1 = str_replace('__', '_', $word1);
        // for $string2
        //remove spaces at the end and convert in lowercase
        $word2 = trim(strtolower($string2));
        //remove spaces between words
        $word2 = str_replace(' ', '_', $word2);
        //remove tabs
        $word2 = preg_replace('/\s+/', '_', $word2);
        $word2 = str_replace('__', '_', $word2);
        // obtain the % of similarity betwen words as a number and save the value on $similarity
        similar_text($word1, $word2, $similarity);

        return $similarity >= $percentage ? true : false;

    }

    public function FindOtherOrUnknown($array, $known_list, $percentage)
    {

        $to_fix_array = array();
        $OtherOrUnknownList = explode(",", $known_list);
        foreach ($array as $list) {
            foreach ($OtherOrUnknownList as $Other) {
                if ($this->EvaluateSimilarity($list[3], $Other, $percentage)) {   //TODO: check if   substrings should be part of the query or not strpos($list[3],$Other)

                    $table = Validations::getChoices($array, $list[1], $list[3]);

                    array_push($to_fix_array, array($list[0], $list[1], $list[2], $table));
                    //array_push( $to_fix_array, Array($list[0],$list[1],$list[2],$list[3]));
                }
            }
        }
        return $to_fix_array;
    }

    public function ListOfOtherOrUnknownWithProblems($array, $Ids)
    {

        global $Proj;
        $pid = $this->getProject()->project_id;
        $to_fix_array = array();
        $IdsList = explode(",", $Ids);
        foreach ($array as $list) {

            if (!in_array($list[2], $IdsList)) {
                //check if this project is in production mode
                if ($_SESSION["status"] == 1) {
                    $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $pid;


                } else {
                    $link_path = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . $pid . '&page=' . $list[0] . '&field=' . $list[1];

                }
                $link_to_edit = '<a href=' . $link_path . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                // Adding : Intrument Name, instrument


                $label = Validations::TextBreak($list[1]);


                array_push($to_fix_array, array(\REDCap::getInstrumentNames($list[0]), $list[1], $label, $list[3], $link_to_edit));


            }
        }
        return array_map("unserialize", array_unique(array_map("serialize", $to_fix_array)));

    }
}