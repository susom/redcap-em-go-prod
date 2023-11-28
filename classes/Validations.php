<?php

namespace Stanford\GoProd;

use REDCap;

class Validations
{
    /**
     * @var \Project
     */
    private $project;

    /**
     * @var array
     */
    private $enabledRules;

    private static $smartVariables = array(
        'last-instance',
        'previous-instance',
        'current-instance',
        'first-instance',
        'next-instance',
        'new-instance',
    );
    public function __construct($project)
    {
        $this->setProject($project);
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
    public function getEnabledRules(): array
    {
        return $this->enabledRules;
    }

    /**
     * @param array $enabledRules
     */
    public function setEnabledRules(array $enabledRules): void
    {
        $this->enabledRules = $enabledRules;
    }

    public static function CleanString($string)
    {

        // first preparing the strings.
        //for $string1
        //remove spaces at the end and convert in lowercase
        $word1 = trim(strtolower($string));
        //remove spaces between words
        $word1 = str_replace(' ', '_', $word1);
        //remove tabs
        $word1 = preg_replace('/\s+/', '_', $word1);
        $word1 = str_replace('__', '_', $word1);

        return $word1;
    }

    public static function CleanArray($array)
    {
        $CleanedArray = array();
        foreach ($array as $item) {
            array_push($CleanedArray, Validations::CleanString($item));
        }

        return $CleanedArray;
    }

    public static function getChoices($array, $variable_name, $to_highlight)
    {
        $table = '<table id="gp-results-table" class="table table-sm"  style="width:80%; border-color: inherit;" border="1">';

        foreach ($array as $list) {
            if ($variable_name == $list[1]) {
                if ($to_highlight == $list[3]) {
                    $table .= '<tr><td class="bg-info" style="color: red" ><strong>' . $list[2] . '</strong></td><td class="bg-info" style="color: red"><strong>' . $list[3] . '</strong></td></tr>';

                } else {
                    $table .= '<tr><td>' . $list[2] . '</td><td>' . $list[3] . '</td></tr>';
                }


            }
        }

        return $table .= '</table>';

    }

    /**
     * @param $array
     * @return array
     * //In: Array $FormName,$FieldName,$Choices  --- Return: Array $Form,$Field, Id, Value
     */
    public static function Transform($array)
    {

        $dd_array = array();
        foreach ($array as $item) {
            $form = $item[0];
            $field = $item[1];
            $arrayOptions = explode("|", $item[2]);
            foreach ($arrayOptions as $item2) {
                $arrayOptionsExplode = explode(",", $item2, 2);
                array_push($dd_array, array($form, $field, $arrayOptionsExplode[0], $arrayOptionsExplode[1]));

            }
        }
        return $dd_array;
    }


    public static function Filter($array, $known_list)
    {
        $FilteredOut = array();
        $FilteredOutList = explode(",", $known_list);// string to array
        //print_r($FilteredOutList);
        $FilteredOutList = Validations::CleanArray($FilteredOutList);
        foreach ($array as $item) {
            if (in_array(Validations::CleanString($item[3]), $FilteredOutList)) {

                $table = Validations::getChoices($array, $item[1], $item[3]);


                array_push($FilteredOut, array($item[0], $item[1], $item[2], $table));

            }

        }


        return $FilteredOut;
    }

    public static function FindProblems($array)
    {

        $FilteredOut = array();
        foreach ($array as $item1) {
            foreach ($array as $item2) {

                if ($item1[2] != $item2[2] and !in_array($item1, $FilteredOut)) {
                    $link_path1 = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT) . '&page=' . $item1[0] . '&field=' . $item1[1];
                    $link_path2 = APP_PATH_WEBROOT . 'Design/online_designer.php?pid=' . filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT) . '&page=' . $item2[0] . '&field=' . $item2[1];
                    $link_to_edit1 = '<a href=' . $link_path1 . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                    $link_to_edit2 = '<a href=' . $link_path2 . ' target="_blank" ><img src=' . APP_PATH_IMAGES . 'pencil.png></a>';
                    //array_push($FilteredOut,Array($item1[0],$item1[1],$item1[2],$item1[3]));


                    // Adding : Intrument Name, instrument
                    $label1 = self::TextBreak($item1[1]);
                    $label2 = self::TextBreak($item2[1]);


                    array_push($FilteredOut, array($item1[0], $item1[1], $label1, $item1[3], $link_to_edit1), array($item2[0], $item2[1], $label2, $item2[3], $link_to_edit2));
                    break;
                }

            }
            if (!empty($FilteredOut)) {

                break;
            }


        }

        return array_map("unserialize", array_unique(array_map("serialize", $FilteredOut))); //return just the unique values found
    }


    public static function getLists()
    {
        $var = array();
        // Loop through each field and do something with each
        foreach (\REDCap::getDataDictionary('array') as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if ($field_attributes['field_type'] == "yesno" || $field_attributes['field_type'] == "radio" || $field_attributes['field_type'] == "dropdown") {

                $FormName = $field_attributes['form_name'];
                $FieldName = $field_attributes['field_name'];
                $Choices = $field_attributes['select_choices_or_calculations'];
                if ($field_attributes['field_type'] == "yesno") {
                    array_push($var, array($FormName, $FieldName, "1, Yes | 0, No"));
                } else {
                    array_push($var, array($FormName, $FieldName, $Choices));
                }
            }
        }
        return $var;
    }

    public static function ExtractVariables($array)
    {

        $branching_logic_array = array();
        $re = '/\[(.*?)\]/';
        $longitudinal = REDCap::isLongitudinal();
        $events = REDCap::getEventNames(true, true);
        foreach ($array as $item) {
            preg_match_all($re, trim($item[2]), $matches);
            //array_push($branching_logic_array,$matches[1]);
            //$branching_logic_array=array_merge($branching_logic_array,$matches[1]);
            foreach ($matches[1] as $item2) {

                // if variable is smart variable then skip it because it not a real variable.
                if(in_array($item2, self::$smartVariables)){
                    continue;
                }

                if ($longitudinal) {
                    //do not remove if the Event name is also used as a Variable name.
                    if (!in_array($item2, self::VariableNamesWithTheSameNameAsAnEventName())) {
                        //if the variable name is in the list of events then is removed from the list of problems
                        if (!in_array($item2, $events)) {
                            array_push($branching_logic_array, array($item[0], $item[1], $item2));
                        }
                    }
                    //if the project is not longitudinal just add all the variables
                } else {
                    array_push($branching_logic_array, array($item[0], $item[1], $item2));
                }
            }
        }
        return array_map("unserialize", array_unique(array_map("serialize", $branching_logic_array)));
    }

    public static function VariableNamesWithTheSameNameAsAnEventName()
    {
        // Check if project is longitudinal
        $var = array();
        if (REDCap::isLongitudinal()) {
            $fields = REDCap::getFieldNames();
            $events = REDCap::getEventNames(true, true);
            $var = array_intersect($events, $fields);
        }
        return $var;
    }

    public static function AddCheckBoxes($fields)
    {

        $var = array();

        foreach ($fields as $field_name) {
            if (REDCap::getFieldType($field_name) == 'checkbox') {


                $checkbox_variable_array = REDCap::getExportFieldNames($field_name);
                foreach ($checkbox_variable_array[$field_name] as $checkbox___format) {
                    $checkbox_field = self::TransformCheckBoxField($checkbox___format);
                    array_push($var, $checkbox_field);

                }
            } else {
                array_push($var, $field_name);
            }
        }
        return $var;
    }

    public static function TransformCheckBoxField($field___format)
    {

        // check if is a negative code for example var(-2)
        if (strpos(trim($field___format), "____") !== false) {
            $number = substr(trim($field___format), strpos($field___format, "____") + 4);
            //echo $number;
            $underscore = "____" . $number;
            $parenthesis = "(-" . $number . ")";

        } else {
            $number = substr(trim($field___format), strpos($field___format, "___") + 3);
            //echo $number;
            $underscore = "___" . $number;
            $parenthesis = "(" . $number . ")";
        }


        $parenthesis_format = str_replace($underscore, $parenthesis, $field___format);


        return $parenthesis_format;
    }

    /**
     * @param $DataDictionary
     * @return array
     *
     * Extract the Calculated fields
     */
    public static function getCalculatedFields()
    {
        $var = array();
        // Loop through each field and do something with each
        foreach (\REDCap::getDataDictionary('array') as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if (strlen(trim($field_attributes['select_choices_or_calculations'])) > 0 and $field_attributes['field_type'] == 'calc') {
                $FormName = $field_attributes['form_name'];
                $FieldName = $field_attributes['field_name'];
                $Calculation = $field_attributes['select_choices_or_calculations'];
                array_push($var, array($FormName, $FieldName, $Calculation));
            }
        }
        return $var;
    }

    public static function TextBreak($question_variable)
    {
        global $Proj;
        // Adding : Intrument Name, instrument

        $label1 = $Proj->metadata[$question_variable];
        $label1 = $label1['element_label'];
        $label1 = REDCap::filterHtml($label1);
        $label1 = wordwrap($label1, 30, "<br />");

        return $label1;
    }
}