<?php

namespace Stanford\GoProd;

class check_my_first_instrument_presence implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

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
        // We have our unique instrument name
        $unique_name = 'form_1';

        // Get the label of our instrument
        $instrument_label = \REDCap::getInstrumentNames($unique_name);


        return $instrument_label ? true : false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['MY_FIRST_INSTRUMENT_TITLE'],
            'body' => $this->getNotifications()['MY_FIRST_INSTRUMENT_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'links' => array(
                array(
                    'url' => APP_PATH_WEBROOT . 'ProjectSetup/index.php?pid=' . $this->getProject()->project_id,
                    'title' => $this->getNotifications()['PROJECT_SETUP']
                )
            ),
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