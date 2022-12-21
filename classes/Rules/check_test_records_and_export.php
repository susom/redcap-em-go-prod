<?php

namespace Stanford\GoProd;

class check_test_records_and_export implements ValidationsImplementation
{
    private $project;

    private $notifications = [];

    public $break = false;

    private $exportCount = 0;

    private $recordsCount = 0;
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
        //project information
        $create_record_array=Array(Validations::CleanString('Create survey response (Auto calculation)'),Validations::CleanString('Create survey response'),Validations::CleanString('Created Response'),Validations::CleanString('Create record'),Validations::CleanString('Created Record'),Validations::CleanString('Create record (API)'),Validations::CleanString( 'Create record (API) (Auto calculation)'),Validations::CleanString('Create record (Auto calculation)'),Validations::CleanString('Create record (import)'));
        $export_data_array=Array(Validations::CleanString('Export data'),Validations::CleanString('Export data (API Playground)'),Validations::CleanString( 'Export data (API)'),Validations::CleanString('Export data (CSV raw with return codes)'));
        $count_records=0;
        $count_exports=0;
        $total= Array();
        $pid = filter_var($_GET['pid'],FILTER_SANITIZE_NUMBER_INT);
        $sql = "SELECT description FROM redcap_log_event  where project_id=$pid
                UNION All
                SELECT description FROM redcap_log_event2 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event3 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event4 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event5 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event6 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event7 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event8 where project_id=$pid
                UNION  All
                SELECT description FROM redcap_log_event9 where project_id=$pid";
                

        $result = db_query( $sql );
        while ( $result1 = db_fetch_assoc( $result ) )
        {
            if(in_array(Validations::CleanString($result1['description']), $create_record_array)           ){
                $count_records++;
            }elseif (in_array(Validations::CleanString($result1['description']),$export_data_array)){
                $count_exports++;
            }
        }
          if($count_records < 3 or $count_exports < 1){
              $this->exportCount = $count_exports;
              $this->recordsCount = $count_records;
              return false;
          }
            return true;
    }

    public function getErrorMessage()
    {
        return array(
            'title' => $this->getNotifications()['TEST_RECORDS_TITLE'],
            'body' => $this->getNotifications()['TEST_RECORDS_BODY'],
            'type' => $this->getNotifications()['WARNING'],
            'extra' => '<u>Exports:</u>'.$this->exportCount.'<br><u> Records: </u>'.$this->recordsCount,
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