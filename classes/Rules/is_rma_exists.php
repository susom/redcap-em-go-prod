<?php

namespace Stanford\GoProd;

class is_rma_exists implements ValidationsImplementation
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
        $status = $rma->getPortal()->getRMAStatus();
        $monthlyFees = $rma->getEntity()->getTotalMonthlyPayment($this->getProject()->project_id);

        /** @var \Stanford\ExternalModuleManager\ExternalModuleManager $manager */
        $manager = \ExternalModules\ExternalModules::getModuleInstance('external_module_manager');
        $monthlyFees += $manager->getProjectTotalCustomCharges($this->getProject()->project_id);
        // if no monthly charges found ignore RMA status
        if ($monthlyFees <= 0) {
            return true;
        }

        //$rma='3';
        $valid_status = array(2, 6, 7);
        return in_array(strval($status), $valid_status) ? true : false;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['RMA_TITLE'],
            'body' => $this->getNotifications()['RMA_BODY'],
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