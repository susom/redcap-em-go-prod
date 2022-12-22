<?php

namespace Stanford\GoProd;

class are_positive_negative_consistent implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    public $modalHeader = array("Instrument", "Variable / Field Name", "Field Label", "Options/Choices", "Edit");

    public $dataDictionary = [];
    public $inconsistentFields = [];

    private $positiveWords = "Positive, Positive*, + ";
    private $negativeWords = "Negative, Negative*, -";

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

        $positive_list = Validations::Filter($all_list_questions, $this->positiveWords);
        $negative_list = Validations::Filter($all_list_questions, $this->negativeWords);

        $positive= Validations::FindProblems($positive_list);
        $negative= Validations::FindProblems($negative_list);
        $this->inconsistentFields =  array_merge($positive,$negative);
        if(!empty($this->inconsistentFields)){
            return false;
        }
        return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['POSITIVE_NEGATIVE_TITLE'],
            'body'  => $this->getNotifications()['POSITIVE_NEGATIVE_BODY'],
            'type'  => $this->getNotifications()['WARNING'],
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