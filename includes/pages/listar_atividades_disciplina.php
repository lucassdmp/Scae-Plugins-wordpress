<?php

function aulasDisciplina_form($atts){
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit();
    }
    ob_start();
    global $wpdb;

    $disciplina_id = isset($_GET['disciplina']) ? intval($_GET['disciplina']) : 0;
    $marks = [];

    $atts = shortcode_atts(array(
        'disciplina' => $disciplina_id,
        'marks' => '',
        'marcarble' => false,
        'title' => '',
    ), $atts);
    $marks = explode(',', $atts['marks']);
    $table = $atts['marcarble'];
    $title = $atts['title'];


    // if($disciplina_id == 0){
    //     wp_safe_redirect(home_url());
    //     exit();
    // }

    $disciplina = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_disciplinas WHERE disc_id = $disciplina_id");
    $aulas_disciplina = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulas JOIN {$wpdb->prefix}scae_aulasdisciplinas ON {$wpdb->prefix}scae_aulas.aula_id = {$wpdb->prefix}scae_aulasdisciplinas.aula_id WHERE disc_id = $disciplina_id");
    
    $html = '<div><h1 class="nome_disciplina">' . $disciplina[0]->disc_name . '</h1></div>';
    $html .= '<div class="flex_div"><h3 class="horas_disciplinas">Total de Horas da Disciplina: ' . $disciplina[0]->disc_total_hours . '</h3>';
    if($table){
        $html .= '<h5 class="Total_Horas">Total de Horas nas Aulas: 0</h5>';
    }
    $html .= '</div>';
    $html .= '<table class="whole_table">';
    $html .= '<thead class="table_head">';
    $html .= '<tr class="row_head">';
    if($table)
        $html .= '<th class="table_header">Marcar</th>';
    $html .= '<th class="table_header">Aula</th>';
    $html .= '<th class="table_header">Descrição</th>';
    $html .= '<th class="table_header">Simulador Usado</th>';
    $html .= '<th class="table_header">Tempo de Aula</th>';
    $html .= '<th class="table_header">Tipo de Aula</th>';
    $html .= '<th class="table_header">Links de Aula</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody class="whole_body">';
    foreach($aulas_disciplina as $aula):
        $aula_sim_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulasimuladores WHERE aula_id = {$aula->aula_id}");
        $aula_sim = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_simuladores WHERE sim_id = {$aula_sim_id[0]->sim_id}");
        $html .= '<tr class="row_body">';
        if ($table) {
            $html .= "<td class='row_field mark'><label><input type='checkbox' name='mark_row[]' value='" . $aula->aula_id . '/' . $aula->aula_horas . "'";
            if (in_array($aula->aula_id, $marks)) {
                $html .= ' checked';
            }
            $html .= "></label></td>";
        }
        $html .= '<td class="row_field">' . $aula->aula_exerc . '</td>';
        $html .= '<td class="row_field">' . $aula->aula_desc . '</td>';
        $html .= '<td class="row_field">' . $aula_sim[0]->sim_name . '</td>';
        $html .= '<td class="row_field">' . $aula->aula_horas . '</td>';
        $html .= '<td class="row_field">' . $aula->aula_type . '</td>';
        $html .= "<td class='row_field'>";
        $aulas_meta = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulameta WHERE aula_id = {$aula->aula_id}"); 
        foreach ($aulas_meta as $meta):
            $html .= "<a href='{$meta->aula_meta_content}' class='aula_links'>$meta->aula_meta_type</a>";
            $html .= "<br>";
        endforeach;
        $html .= "</td>";
        $html .= '</tr>';
    endforeach;
    $html .= '</tbody>';
    $html .= '</table>';
    if($table){
        $html .= "<button class='submitPlanejamento' type='submit' class='btn btn-primary' name='submitPlanejamento'>Salvar Planejamento</button>";
    }
    return ob_clean() . $html;
}
add_shortcode('listar-auladisciplina', 'aulasDisciplina_form');
?>