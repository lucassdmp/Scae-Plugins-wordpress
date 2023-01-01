<?php
class DB{
    public function __construct(){
        $this->check_for_tables();
    }

    private function check_for_tables(){
        global $wpdb;

        $table_name = SCAE_TABLE_SIMULADORES;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_simuladores_table();
        }
        $table_name = SCAE_TABLE_SIMULADORESMETA;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_simulador_meta();
        }
        $table_name = SCAE_TABLE_DISCIPLINAS;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_disciplinas_table();
        }
        $table_name = SCAE_TABLE_ATIVIDADES;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_atividades_table();
        }
        $table_name = SCAE_TABLE_ATIVIDADESMETA;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_atividades_meta_table();
        }
        $table_name = SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_ads_table();
        }

        $table_name = SCAE_TABLE_PLANEJAMENTOS;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_planejamento_table();
        }

        $table_name = SCAE_TABLE_AULAS_PLANEJAMENTO;
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $this->create_aulas_planejamento_table();
        }
    }


    private function create_simuladores_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_SIMULADORES;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            nome varchar(100) NOT NULL,
            abreviacao varchar(30) NOT NULL,
            descricao varchar(1000) NOT NULL,
            user_id int(11) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    //TODO CRIAR CODIGO PARA NORMALIZAR BANCO DE DADOS
    //META DO SIMULADOR: owrner, license, link, complexity

    private function create_simulador_meta(){
        global $wpdb;
        $table_name = SCAE_TABLE_SIMULADORESMETA;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL,
            meta_tag varchar(30) NOT NULL,
            meta_content varchar(500) NOT NULL
            ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    private function create_disciplinas_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_DISCIPLINAS;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            nome varchar(100) NOT NULL,
            abreviacao varchar(30) NOT NULL,
            descricao varchar(300) NOT NULL,
            escolaridade varchar(30) NOT NULL,
            carga_horaria varchar(8) NOT NULL,
            user_id int(11) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_atividades_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_ATIVIDADES;
        $charset_collate = 'ENGINE=InnoDB DEFAULT CHARSET=utf8';
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            nome varchar(100) NOT NULL,
            duracao int(11) NOT NULL,
            categoria varchar(50) NOT NULL,
            descricao varchar(1000) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
    private function create_atividades_meta_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_ATIVIDADESMETA;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL,
            meta_tag varchar(30) NOT NULL,
            meta_content varchar(500) NOT NULL
            ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_ads_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            aula_id int(11) NOT NULL,
            disc_id int(11) NOT NULL,
            sim_id int(11) NOT NULL
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_planejamento_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_PLANEJAMENTOS;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name ( 
            id INT NOT NULL AUTO_INCREMENT, 
            user_id INT NOT NULL, 
            disc_id INT NOT NULL, 
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    private function create_aulas_planejamento_table(){
        global $wpdb;
        $table_name = SCAE_TABLE_AULAS_PLANEJAMENTO;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table_name} (
            ap_id bigint(20) NOT NULL AUTO_INCREMENT,
            planejamento_id bigint(20) NOT NULL,
            aula_id bigint(20) NOT NULL,
            PRIMARY KEY (ap_id)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}    
new DB();

?>