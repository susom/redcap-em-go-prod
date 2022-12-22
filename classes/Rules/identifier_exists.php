<?php

namespace Stanford\GoProd;

class identifier_exists implements ValidationsImplementation
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
        foreach (\REDCap::getDataDictionary() as $field_name => $field_attributes) {
            if ($field_attributes['identifier'] == "y") {
                return true;
            }
        }
        return false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['IDENTIFIERS_TITLE'],
            'body' => $this->getNotifications()['IDENTIFIERS_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'links' => array(
                array(
                    'url' => APP_PATH_WEBROOT . 'ProjectSetup/index.php?pid=' . $this->getProject()->project_id . '&route=IdentifierCheckController:index',
                    'title' => $this->getNotifications()['EDIT']
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