<?php
function atividades_form_page(){
    global $wpdb;
    ?>
    <div class="wrap">
        <form method="post" action="">
            <div>
                <label for="aula_sim_id">Simulador</label>
                <select name="aula_sim_id" id="aula_sim_id">
                    <?php
                        $table_name = $wpdb->prefix . 'simulador';
                        $simuladores = $wpdb->get_results("SELECT * FROM $table_name");
                        foreach ($simuladores as $simulador) {
                            echo "<option value='$simulador->sim_id'>$simulador->sim_name</option>";
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="aula_exerc">Exercício</label>
                <input type="text" name="aula_exerc" id="aula_exerc" required>
            </div>
            <div>
                <label for="aula_horas">Horas</label>
                <input type="text" name="aula_horas" id="aula_horas" required>
            </div>
           <div> 
                <label for="aula_type">Tipo</label>
                <select name="aula_type" id="aula_type">
                    <option value="1">Dignosticas</option>
                    <option value="2">Formadouras</option>
                    <option value="3">Exercício-Resposta</option>
                    <option value="4">Problema-Resposta</option>
                </select>
            </div>
            <div>
                <label for="aula_desc">Descrição</label>
                <textarea name="aula_desc" id="aula_desc" cols="100" rows="10"></textarea>
            </div>
            <div>
                <input type="submit" name="submit" value="Submit" class="button button-primary">
            </div>
        </form>
   </div>
    <?php
}


function atividades_shortcode(){
    ob_start();
    atividades_form_page();
    return ob_get_clean();
}

add_shortcode('aula-form', 'atividades_shortcode');

function save_atividade_to_db(){
    global $wpdb;
    if (isset($_POST['aula_sim_id']) && isset($_POST['aula_exerc']) && isset($_POST['aula_type']) && isset($_POST['aula_desc'])) {
        $aula_sim_id = sanitize_text_field($_POST['aula_sim_id']);
        $aula_exerc = sanitize_text_field($_POST['aula_exerc']);
        $aula_type = sanitize_text_field($_POST['aula_type']);
        $aula_desc = sanitize_textarea_field($_POST['aula_desc']);
        $aula_horas = intval($_POST['aula_horas']);

        $table_name = $wpdb->prefix . 'aulas';
        $wpdb->insert($table_name, array(
            'aula_id' => 0,
            'aula_sim_id' => $aula_sim_id,
            'aula_exerc' => $aula_exerc,
            'aula_horas' => $aula_horas, // 'aula_horas' => '0
            'aula_type' => $aula_type,
            'aula_desc' => $aula_desc
        ));
    }
}

add_action('init', 'save_atividade_to_db');

?>