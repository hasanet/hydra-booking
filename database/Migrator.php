<?php
namespace HydraBooking\DB;
 
use HydraBooking\DB\Availability;
use HydraBooking\DB\Host;
use HydraBooking\DB\Event;

class Migrator {

    public function __construct() { 
        $this->migrate();
    }

    /**
     * Run the database migration.
     */
    public function migrate() {
        // availability migration
        $Availability =  new Availability(); 
        $Availability->migrate();

        // Host migration
        $Host =  new Host();
        $Host->migrate();

        // Event migration
        $Event =  new Event();
        $Event->migrate();
    }

    /**
     * Rollback the database migration.
     */
    public function rollback() {
        // $this->availability();
    }

   


    /**
     * Availability migration.
     */
    private function availability() {
       
    }
} 