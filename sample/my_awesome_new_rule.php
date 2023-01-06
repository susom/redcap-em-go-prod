<?php

namespace Stanford\GoProd;

class my_awesome_new_rule implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    public $modalHeader = array("TABLE HEADER 1", "TABLE HEADER 2", "TABLE HEADER 3");

    public $inconsistentFields = [];

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
        /**
         * My awesome rule logic
         */
        $this->inconsistentFields = array(array(1, 2, 3), array(4, 5, 6));
        if (empty($this->inconsistentFields)) {
            return true;
        }
        return false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['MY_AWESOME_RULE_TITLE'],
            'body' => $this->getNotifications()['MY_AWESOME_RULE_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'modal' => $this->inconsistentFields,
            'modalHeader' => $this->modalHeader,
            'extra' => 'CUSTOM TEXT/HTML',
            'links' => array(
                array(
                    'url' => '[LINK_TO_EXTERNAL_PAGE]',
                    'title' => 'Read More'
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