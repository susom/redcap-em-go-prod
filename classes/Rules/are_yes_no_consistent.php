<?php

namespace Stanford\GoProd;

class are_yes_no_consistent implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $dataDictionary = [];

    public $inconsistentFields = [];

    public $yesWords = "Yes, Si, yes, si, Yes*, Si*, 否";

    public $noWords = "No, no, No*, 是";
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

        $positive_negative_array= Validations::getLists();
        $all_list_questions=  Validations::Transform($positive_negative_array);

        $yes_list = Validations::Filter($all_list_questions, $this->yesWords);
        $no_list = Validations::Filter($all_list_questions, $this->noWords);

        $yes= Validations::FindProblems($yes_list);
        $no= Validations::FindProblems($no_list);
        $this->inconsistentFields =  array_merge($yes,$no);
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
}