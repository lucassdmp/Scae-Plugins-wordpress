<?php
function aulasSimulador_form(){
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit();
    }
    ob_start();
    global $wpdb;

    $simulador_id = isset($_GET['simulador']) ? intval($_GET['simulador']) : 0;

    // if($simulador_id == 0){
    //     wp_safe_redirect(home_url());
    //     exit();
    // }

    $simulador = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_simuladores WHERE sim_id = $simulador_id");
    $aulas_simulador = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulas JOIN {$wpdb->prefix}scae_aulasimuladores ON {$wpdb->prefix}scae_aulas.aula_id = {$wpdb->prefix}scae_aulasimuladores.aula_id JOIN {$wpdb->prefix}scae_aulasdisciplinas ON {$wpdb->prefix}scae_aulas.aula_id = {$wpdb->prefix}scae_aulasdisciplinas.aula_id WHERE {$wpdb->prefix}scae_aulasimuladores.sim_id = $simulador_id;");

    $html = '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th class="table_header">Aula</th>';
    $html .= '<th class="table_header">Descrição de Aula</th>';
    $html .= '<th class="table_header">Tempo de Aula</th>';
    $html .= '<th class="table_header">Disciplina</th>';
    $html .= '<th class="table_header">Descrição da Disciplina</th>';
    $html .= '<th class="table_header">Ações</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    foreach ($aulas_simulador as $aula):
        $disciplina = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_disciplinas WHERE disc_id = $aula->disc_id");
        $html .= '<tr>';
        $html .= '<td class="table_field">' . $aula->aula_exerc . '</td>';
        $html .= '<td class="table_field">' . $aula->aula_desc . '</td>';
        $html .= '<td class="table_field">' . $aula->aula_horas . '</td>';
        $html .= '<td class="table_field">' . $disciplina[0]->disc_name . '</td>';
        $html .= '<td class="table_field">' . $disciplina[0]->disc_desc . '</td>';
        $html .= "</td>";
        $html .= '</tr>';
    endforeach;
    $html .= '</tbody>';
    $html .= '</table>';

    return ob_clean() . $html;
}

add_shortcode('aulas_simulador', 'aulasSimulador_form');

?>