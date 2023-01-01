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

    $TABLE_NAME = SCAE_TABLE_SIMULADORES;
    $TABLE_ATIVIDADES = SCAE_TABLE_ATIVIDADES;
    $TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR = SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR;
    $TABLE_DISCIPLINA = SCAE_TABLE_DISCIPLINAS;

    // $simulador = $wpdb->get_results("SELECT * FROM {$TABLE_NAME} WHERE id = $simulador_id");
    $atividades_simulador = $wpdb->get_results("SELECT * FROM 
    {$TABLE_ATIVIDADES} JOIN {$TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR} 
    ON {$TABLE_ATIVIDADES}.id = {$TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR}.aula_id 
    WHERE {$TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR}.sim_id = $simulador_id;");
    

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
    foreach ($atividades_simulador as $aula):
        $disciplina = $wpdb->get_results("SELECT * FROM {$TABLE_DISCIPLINA} WHERE id = $aula->disc_id")[0];
        $html .= '<tr>';
        $html .= '<td class="table_field">' . $aula->nome . '</td>';
        $html .= '<td class="table_field">' . $aula->descricao . '</td>';
        $html .= '<td class="table_field">' . $aula->duracao . '</td>';
        $html .= '<td class="table_field">' . $disciplina->nome . '</td>';
        $html .= '<td class="table_field">' . $disciplina->descricao . '</td>';
        $html .= "<td></td>";
        $html .= '</tr>';
    endforeach;
    $html .= '</tbody>';
    $html .= '</table>';

    return ob_clean() . $html;
}

add_shortcode(SCAE_LISTAR_ATIVIDADES_SIMULADOR_SHORTCODE, 'aulasSimulador_form');

?>