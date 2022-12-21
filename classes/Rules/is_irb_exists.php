<?php

namespace Stanford\GoProd;

class is_irb_exists implements ValidationsImplementation
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
        $purpose = trim($this->getProject()->project['purpose']);
        $irb_number = $this->getProject()->project['project_irb_number'];
        return $purpose === "2" and strlen(trim($irb_number)) > 0 ? true : false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['IRB_TITLE'],
            'body' => $this->getNotifications()['IRB_TITLE'],
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