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
    ), $atts);
    $marks = explode(',', $atts['marks']);


    // if($disciplina_id == 0){
    //     wp_safe_redirect(home_url());
    //     exit();
    // }

    $disciplina = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_disciplinas WHERE disc_id = $disciplina_id");
    $aulas_disciplina = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulas JOIN {$wpdb->prefix}scae_aulasdisciplinas ON {$wpdb->prefix}scae_aulas.aula_id = {$wpdb->prefix}scae_aulasdisciplinas.aula_id WHERE disc_id = $disciplina_id");
    
    $html = '<h4>' . $disciplina[0]->disc_name . '</h4>';
    $html .= '<h5>Total de Horas: ' . $disciplina[0]->disc_total_hours . '</h5>';
    $html .= '<h5 class="Total_Horas">Total de Horas nas Aulas: 0</h5>';
    $html .= '<table class="table">';
    $html .= '<thead>';
    $html .= '<tr>';
    $html .= '<th class="table_header">Marcar</th>';
    $html .= '<th class="table_header">Aula</th>';
    $html .= '<th class="table_header">Descrição</th>';
    $html .= '<th class="table_header">Tempo de Aula</th>';
    $html .= '<th class="table_header">Tipo de Aula</th>';
    $html .= '<th class="table_header">Links de Aula</th>';
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    foreach($aulas_disciplina as $aula):
        $html .= '<tr>';
        $html .= "<td><label><input type='checkbox' name='mark_row[]' value='" . $aula->aula_id.'/'.$aula->aula_horas. "'";
        if (in_array($aula->aula_id, $marks)) {
            $html .= ' checked';
        }
        $html .= "></label></td>";
        $html .= '<td class="table_field">' . $aula->aula_exerc . '</td>';
        $html .= '<td class="table_field">' . $aula->aula_desc . '</td>';
        $html .= '<td class="table_field">' . $aula->aula_horas . '</td>';
        $html .= '<td class="table_field">' . $aula->aula_type . '</td>';
        $html .= "<td class='table_field'>";
        $aulas_meta = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_aulameta WHERE aula_id = {$aula->aula_id}"); 
        foreach ($aulas_meta as $meta):
            $html .= "<a href='{$meta->aula_meta_content}'>$meta->aula_meta_type</a>";
            $html .= "<br>";
        endforeach;
        $html .= "</td>";
        $html .= '</tr>';
    endforeach;
    $html .= '</tbody>';
    $html .= '</table>';  

    return ob_clean() . $html;
}
add_shortcode('aulas_disciplina', 'aulasDisciplina_form');
?>