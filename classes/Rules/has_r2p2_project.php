<?php

namespace Stanford\GoProd;

class has_r2p2_project implements ValidationsImplementation
{

    private $project;

    private $notifications = [];

    public $break = false;

    /** @var \Stanford\ProjectPortal\ProjectPortal $r2p2Object */
    private $r2p2Object;

    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
        $this->r2p2Object = \ExternalModules\ExternalModules::getModuleInstance('rit_dashboard');
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
        $this->r2p2Object->getPortal()->setProjectPortalSavedConfig($this->getProject()->project_id);

        // if REDCap project is not linked to R2P2 Project
        if (!empty($this->r2p2Object->getPortal()->projectPortalSavedConfig)) {
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
                ),
                array(
                    'url' => $this->r2p2Object->getUrl('views/index.php') . '&open-linkage-modal=true',
                    'title' => 'Link to R2P2 Project'
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