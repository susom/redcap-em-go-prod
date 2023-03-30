<?php

namespace Stanford\GoProd;

class check_un_designated_longitudinal_forms implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break=false;

    public $modalTableHeader = array("Unique Instrument Name", "Instrument Label", "Designate Instruments for My Events");
    public $notDesignatedForms = [];
    public function __constructor($project, $notifications)
    {
        $this->setProject($project);
        $this->setNotifications($notifications);
    }


    /**
     * @return \Project
     */
    public function getProject(): \Project
    {
        return $this->project;
    }

    /**
     * @param \Project $project
     * @return void
     */
    public function setProject(\Project $project): void
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function validate()
    {
        $array = array();
        $events= $this->getProject()->eventsForms;
        if (\REDCap::isLongitudinal()){
                $instrument_names = \REDCap::getInstrumentNames();
                $instrument_unique_names = Array();
                $events_with_forms = Array();

                foreach ($instrument_names as $unique_name => $label) {
                    array_push($instrument_unique_names, $unique_name);
                }

                foreach ($instrument_unique_names as $form_name) {

                    foreach ($events as $event_id => $forms_array) {

                        if ( in_array($form_name, $forms_array)) array_push($events_with_forms, $form_name);
                    }
                }

            $events_with_forms = array_map("unserialize", array_unique(array_map("serialize", $events_with_forms)));

            $result = array_diff($instrument_unique_names, $events_with_forms);

            foreach ($result as $item) {
                $a = '<a  target="_blank" href=" ' . APP_PATH_WEBROOT . 'Design/designate_forms.php?pid=' . $this->getProject()->project_id .
                    '"  >' . $this->getNotifications()['VIEW'] . '</a>';
                array_push($array, array($item, \REDCap::getInstrumentNames($item), $a));
            }
        }
        $this->notDesignatedForms = $array;
        if (!empty($this->notDesignatedForms)) {

            return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['NOT_DESIGNATED_FORMS_TITLE'],
            'body'  => $this->getNotifications()['NOT_DESIGNATED_FORMS_BODY'],
            'type'  => $this->getNotifications()['WARNING'],
            'modal' => $this->notDesignatedForms,
            'modalHeader' => $this->modalTableHeader,
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
     * @return void
     */
    public function setNotifications(array $notifications): void
    {
        $this->notifications = $notifications;
    }
}