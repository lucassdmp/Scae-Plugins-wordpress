<?php
// Add AULAS
require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Add_Aulas.php');
add_shortcode('aula-form', 'atividades_shortcode');
add_action('init', 'save_atividade_to_db');
function create_add_aula_page(){
    $args = array(
        'post_title' => 'Criar Aula',
        'post_slug' => 'criar-aula',
        'post_content' => '[aula-form]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_add_aula_page(){
    $page_id = get_page_by_path('criar-aula')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_add_aula_page');
register_deactivation_hook(__FILE__, 'delete_add_aula_page');

// ADD Disciplinas

require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Add_Disciplina.php');
add_shortcode('disciplina-form', 'disciplinas_shortcode');
add_action('init', 'save_disciplina_to_db');

function create_add_disciplina_page(){
    $args = array(
        'post_title' => 'Criar Disciplina',
        'post_slug' => 'criar-disciplina',
        'post_content' => '[disciplina-form]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_add_disciplina_page(){
    $page_id = get_page_by_path('criar-disciplina')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_add_disciplina_page');
register_deactivation_hook(__FILE__, 'delete_add_disciplina_page');

// ADD SIMULADORES

require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Add_Simulador.php');
add_shortcode('simulador', 'simulador_shortcode');
add_action('init', 'save_simulador_to_db');

function create_add_simulador_page(){
    $args = array(
        'post_title' => 'Criar Simulador',
        'post_slug' => 'criar-simulador',
        'post_content' => '[simulador]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_add_simulador_page(){
    $page_id = get_page_by_path('criar-simulador')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_add_simulador_page');
register_deactivation_hook(__FILE__, 'delete_add_simulador_page');

// LISTAR SIMULADORES
require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Listar_Simuladores.php');
add_shortcode('list_simuladores', 'simulador_table_shortcode');
function create_list_simulador_page(){
    $args = array(
        'post_title' => 'Simuladores',
        'post_slug' => 'simuladores',
        'post_content' => '[list_simuladores]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_list_simulado_page(){
    $page_id = get_page_by_path('simuladores')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_list_simulador_page');
register_deactivation_hook(__FILE__, 'delete_list_simulado_page');

//Create listar disciplinas page
require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Listar_Disciplinas.php');
add_shortcode('listar_disciplinas', 'listar_disciplinas_shortcode');

function create_list_disciplina_page(){
    $args = array(
        'post_title' => 'Disciplinas',
        'post_slug' => 'disciplinas',
        'post_content' => '[listar_disciplinas]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_list_disciplina_page(){
    $page_id = get_page_by_path('disciplinas')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_list_disciplina_page');
register_deactivation_hook(__FILE__, 'delete_list_disciplina_page');

//Create listar aulas page
require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Listar_AulaDisciplina.php');


function create_listarAulasDisciplinas_page(){
    $args = array(
        'post_title' => 'Aulas',
        'post_slug' => 'aulas',
        'post_content' => '[aulas_disciplina]',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'page'
    );
    $page_id = wp_insert_post($args);
}

function delete_listarAulasDisciplinas_page(){
    $page_id = get_page_by_path('aulas')->ID;
    wp_delete_post($page_id, true);
}

register_activation_hook(__FILE__, 'create_listarAulasDisciplinas_page');
register_deactivation_hook(__FILE__, 'delete_listarAulasDisciplinas_page');


require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Listar_AulaSimulador.php');
require_once (ABSPATH . 'wp-content/plugins/Scae-Plugins-wordpress/pages/Criar_planejamento.php');
?>