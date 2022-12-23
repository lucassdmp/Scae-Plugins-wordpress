<?php
function atividades_form_page(){
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
    global $wpdb;
    ?>
    <div class="wrap">
        <form method="post" action="">
            <div>
                <label for="aula_sim_id">Simulador</label>
                <select name="aula_sim_id" id="aula_sim_id">
                    <?php
                        $table_name = $wpdb->prefix . 'scae_simuladores';
                        $simuladores = $wpdb->get_results("SELECT * FROM $table_name");
                        foreach ($simuladores as $simulador) {
                            echo "<option value='$simulador->sim_id'>$simulador->sim_name</option>";
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="aula_disc_id">Disciplina</label>
                <select name="aula_disc_id" id="aula_disc_id">
                    <?php
                        $table_name = $wpdb->prefix . 'scae_disciplinas';
                        $disciplinas = $wpdb->get_results("SELECT * FROM $table_name");
                        foreach ($disciplinas as $disciplina) {
                            echo "<option value='$disciplina->disc_id'>$disciplina->disc_name</option>";
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
                    <option value="Dignosticas">Dignosticas</option>
                    <option value="Formadouras">Formadouras</option>
                    <option value="Exercícios">Exercícios</option>
                    <option value="Resoluções">Resoluções</option>
                </select>
            </div>
            <div>
                <label for="aula_artigo">Artigo:</label>
                <input type="url" name="aula_artigo" id="aula_artigo">
            </div>
            <div>
                <label for="aula_video">Video:</label>
                <input type="url" name="aula_video" id="aula_video">
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

function save_atividade_to_db(){
    global $wpdb;
    if (isset($_POST['aula_sim_id']) && isset($_POST['aula_exerc']) && isset($_POST['aula_type']) && isset($_POST['aula_desc'])) {
        $aula_sim_id = sanitize_text_field($_POST['aula_sim_id']);
        $aula_disc_id = sanitize_text_field($_POST['aula_disc_id']);
        $aula_exerc = sanitize_text_field($_POST['aula_exerc']);
        $aula_type = sanitize_text_field($_POST['aula_type']);
        $aula_desc = sanitize_textarea_field($_POST['aula_desc']);
        $aula_horas = intval($_POST['aula_horas']);
        $aula_video = sanitize_text_field($_POST['aula_video']);
        $aula_artigo = sanitize_text_field($_POST['aula_artigo']);

        $table_name = $wpdb->prefix . 'scae_aulas';
        $aulas_result = $wpdb->get_results("SELECT * FROM $table_name WHERE aula_sim_id = $aula_sim_id AND aula_disc_id = $aula_disc_id AND aula_exerc = $aula_exerc");
        if (!empty($aulas_result)) {
            echo "Aula já existe";
        } else {
            $results = $wpdb->insert($table_name, array(
                'aula_exerc' => $aula_exerc,
                'aula_horas' => $aula_horas, // 'aula_horas' => '0
                'aula_type' => $aula_type,
                'aula_desc' => $aula_desc
            ));
    
            if ($results){
                $aula_id = $wpdb->insert_id;
                $aula_disc_id = intval($aula_disc_id);

                $aula_sim_id = intval($aula_sim_id);
                $wpdb->insert($wpdb->prefix . 'scae_aulasdisciplinas', array(
                    'aula_id' => $aula_id,
                    'disc_id' => $aula_disc_id
                ));
                $wpdb->insert($wpdb->prefix . 'scae_aulasimuladores', array(
                    'aula_id' => $aula_id,
                    'sim_id' => $aula_sim_id
                ));
                if($aula_video){
                    $wpdb->insert($wpdb->prefix . 'scae_aulameta', array(
                        'aula_id' => $aula_id,
                        'aula_meta_type' => 'video',
                        'aula_meta_content' => $aula_video
                    ));
                }
                if($aula_artigo){
                    $wpdb->insert($wpdb->prefix . 'scae_aulameta', array(
                        'aula_id' => $aula_id,
                        'aula_meta_type' => 'artigo',
                        'aula_meta_content' => $aula_artigo
                    ));
                }
            }
            wp_safe_redirect(home_url());
            exit;
        }
    }
}

?>