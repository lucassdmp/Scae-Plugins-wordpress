<?php

class DB{
    public function __construct(){
        $this->check_for_tables();
    }

    private function check_for_tables(){
        global $wpdb;

        $table_name = "wp_scae_simuladores";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_simuladores_table();
        }
        $table_name = "wp_scae_simuladormeta";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_simulador_meta();
        }
        $table_name = "wp_scae_disciplinas";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_disciplinas_table();
        }
        $table_name = "wp_scae_aulas";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aulas_table();
        }
        $table_name = "wp_scae_aulameta";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aula_meta();
        }
        $table_name = "wp_scae_aulasdisciplinas";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aulas_disciplinas_table();
        }
        $table_name = "wp_scae_aulasimuladores";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aula_simulador_table();
        }

        $table_name = "wp_scae_planning";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_planning_table();
        }

        $table_name = "wp_scae_aulas_planejamento";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aulas_planejamento_table();
        }
    }

   
    private function create_simuladores_table(){
        global $wpdb;
        $table_name = "wp_scae_simuladores";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            sim_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            sim_name varchar(100) NOT NULL,
            sim_abrev varchar(30) NOT NULL,
            sim_desc varchar(1000) NOT NULL,
            sim_owner varchar(100) NOT NULL,
            sim_license varchar(100) NOT NULL,
            sim_link varchar(150) NOT NULL,
            sim_complexity varchar(30) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_simulador_meta(){
        global $wpdb;
        $table_name = "wp_scae_simuladormeta";
        $foreign_key = "wp_scae_simuladores";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            sim_id int(11) NOT NULL,
            sim_meta_type varchar(30) NOT NULL,
            sim_meta_content varchar(500) NOT NULL,
            FOREIGN KEY (sim_id) REFERENCES $foreign_key(sim_id)
            ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    private function create_disciplinas_table(){
        global $wpdb;
        $table_name = "wp_scae_disciplinas";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            disc_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            disc_name varchar(100) NOT NULL,
            disc_abrev varchar(30) NOT NULL,
            disc_desc varchar(300) NOT NULL,
            disc_tier varchar(30) NOT NULL,
            disc_total_hours varchar(8) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_aulas_table(){
        global $wpdb;
        $table_name = "wp_scae_aulas";
        $charset_collate = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
        $sql = "CREATE TABLE $table_name (
            aula_id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            aula_exerc varchar(100) NOT NULL,
            aula_horas int(11) NOT NULL,
            aula_type varchar(30) NOT NULL,
            aula_desc varchar(1000) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }


    private function create_aula_meta(){
        global $wpdb;
        $table_name = "wp_scae_aulameta";
        $charset_collate = $wpdb->get_charset_collate();
        $foreign_key = "wp_scae_aulas";
        $sql = "CREATE TABLE $table_name (
            aula_id int(11) NOT NULL,
            aula_meta_type varchar(30) NOT NULL,
            aula_meta_content varchar(500) NOT NULL,
            FOREIGN KEY (aula_id) REFERENCES $foreign_key(aula_id)
            ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_aulas_disciplinas_table(){
        global $wpdb;
        $table_name = "wp_scae_aulasdisciplinas";
        $charset_collate = $wpdb->get_charset_collate();
        $foreign_key1 = "wp_scae_aulas";
        $foreign_key2 = "wp_scae_disciplinas";
        $sql = "CREATE TABLE $table_name (
            aula_id int(11) NOT NULL,
            disc_id int(11) NOT NULL,
            FOREIGN KEY (aula_id) REFERENCES $foreign_key1(aula_id),
            FOREIGN KEY (disc_id) REFERENCES $foreign_key2(disc_id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_aula_simulador_table(){
        global $wpdb;
        $table_name = "wp_scae_aulasimuladores";
        $charset_collate = $wpdb->get_charset_collate();
        $foreign_key1 = "wp_scae_aulas";
        $foreign_key2 = 'wp_scae_simuladores';
        $sql = "CREATE TABLE $table_name (
            aula_id int(11) NOT NULL,
            sim_id int(11) NOT NULL,
            FOREIGN KEY (aula_id) REFERENCES $foreign_key1(aula_id),
            FOREIGN KEY (sim_id) REFERENCES $foreign_key2(sim_id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_planning_table(){
        global $wpdb;
        $table_name = "wp_scae_planning";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table_name} ( 
            planning_id INT NOT NULL AUTO_INCREMENT, 
            user_id INT NOT NULL, 
            disc_id INT NOT NULL, 
            PRIMARY KEY (planning_id) 
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_aulas_planejamento_table(){
        global $wpdb;
        $table_name = "wp_scae_aulas_planejamento";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table_name} (
            ap_id bigint(20) NOT NULL AUTO_INCREMENT,
            planning_id bigint(20) NOT NULL,
            aula_id bigint(20) NOT NULL,
            PRIMARY KEY (ap_id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}

function create_DB(){
    $db = new DB();
}

?>