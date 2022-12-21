<?php

namespace Stanford\GoProd;

class has_r2p2_project implements ValidationsImplementation
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
        /** @var \Stanford\ProjectPortal\ProjectPortal $rma */
        $rma = \ExternalModules\ExternalModules::getModuleInstance('rit_dashboard');
        $rma->getPortal()->setProjectPortalSavedConfig($this->getProject()->project_id);

        // if REDCap project is not linked to R2P2 Project
        if (!empty($rma->getPortal()->projectPortalSavedConfig)) {
            return true;
        }
        return false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['LINKED_R2P2_TITLE'],
            'body' => $this->getNotifications()['LINKED_R2P2_BODY'],
            'type' => $this->getNotifications()['DANGER'],
            'links' => array(
                array(
                    'url' => 'https://medwiki.stanford.edu/x/dZeWCg',
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