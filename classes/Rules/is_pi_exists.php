<?php

namespace Stanford\GoProd;

class is_pi_exists implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break=false;

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
        $first_name = trim($this->getProject()->project['project_pi_firstname']);
        $last_name = trim($this->getProject()->project['project_pi_lastname']);
        $purpose = trim($this->getProject()->project['purpose']);
        if (isset($purpose) and isset($last_name) and isset($first_name)) {
            // if research project
            if ($purpose === "2") {
                return strlen($first_name) > 0 and strlen($last_name) > 0 ? true : false;
                // any other than research except just_for_fun
            } elseif ($purpose !== "0") {
                return true;
            }
        }
        return false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['PI_TITLE'],
            'body' => $this->getNotifications()['PI_BODY'],
            'type' => $this->getNotifications()['DANGER'],
            'links' => array(
                array(
                    'url' => APP_PATH_WEBROOT.'ProjectSetup/index.php?pid='.$this->getProject()->project_id,
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