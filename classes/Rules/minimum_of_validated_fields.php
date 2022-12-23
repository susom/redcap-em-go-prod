<?php

namespace Stanford\GoProd;

class minimum_of_validated_fields implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    private $minPercentage = 0.05;
    public $validatedFields = 0;
    public $textBoxFields = 0;

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

        // Loop through each field and do something with each
        foreach (\REDCap::getDataDictionary() as $field_name => $field_attributes) {
            // Do something with this field if it is a checkbox field
            if ($field_attributes['field_type'] == "text") {
                $this->textBoxFields++;
                if (strlen(trim($field_attributes['text_validation_type_or_show_slider_number'])) > 0 or strlen(trim($field_attributes['select_choices_or_calculations'])) > 0) {
                    $this->validatedFields++;

                }
            }


        }

        return ($this->validatedFields / $this->textBoxFields) < $this->minPercentage ? false : true;

    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['NUMBER_VALIDATED_RECORDS_TITLE'],
            'body' => $this->getNotifications()['NUMBER_VALIDATED_RECORDS_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'extra' => '<u>' . $this->getNotifications()['VALIDATED_FIELDS'] . '</u>' . $this->validatedFields . '<br><u>' . $this->getNotifications()['TEXT_BOX_FIELDS'] . '</u>' . $this->textBoxFields,
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
     */
    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }
}