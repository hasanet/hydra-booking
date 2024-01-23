<?php
namespace HydraBooking\DB;
 
use HydraBooking\DB\Availability;

class Migrator {

    public function __construct() { 
        $this->migrate();
    }

    /**
     * Run the database migration.
     */
    public function migrate() {
        $this->availability();
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
        $Availability =  new Availability(); 
        $Availability->migrate();
    }
} 