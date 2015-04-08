<?php

class Micab_schedule_settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'micab_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function micab_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings ', 
            'Settings', 
            'manage_options', 
            'micab-settings', 
            array( $this, 'micab_admin_page' )
        );
    }

     /**
     * Options page callback
     */
    public function micab_admin_page()
    {
        // Set class property
        $this->options = get_option( 'schedules' );
        ?>
        <div class="wrap">
            <h2>My Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'schedules' );   
                do_settings_sections( 'micab-settings' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'micab', // Option group
            'schedules', // Option name
        );

        add_settings_field(
            'micab_schedule_name',      
            'Schedule Name',             
            'micab_schedule_name_input',    
            'micab-settings',                 
            'default'                  
        );

        add_settings_field(
            'micab_schedule_config',      
            'Schedule Configuration',             
            'micab_schedule_config_input',    
            'micab-settings',                 
            'default'                  
        );

    }


    function micab_schedule_name_input(){
        print '<label for="micab_schedule_name">Name</label><input id="micab_schedule_name" name="micab_schedule_name" type="text"><br>';
    }

    function micab_schedule_config_input(){
        print '<label for="micab_schedule_config">Schedule</label><input id="micab_schedule_config" name="micab_schedule_config" class="datetimepicker"><br>';
    }
}

if( is_admin() )
    $my_settings_page = new Micab_schedule_settings();