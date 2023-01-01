<?php
function simulador_form_page()
{
    global $wpdb;
    if (!is_user_logged_in()) {
        wp_safe_redirect(home_url());
        exit;
    }

    ob_start();
    if (isset($_POST['submitSimulador']) && isset($_POST['sim_name']) && isset($_POST['sim_abrev']) && isset($_POST['sim_desc']) && isset($_POST['sim_owner']) && isset($_POST['sim_license']) && isset($_POST['sim_link']) && isset($_POST['sim_complexity'])) {
        $table_name = SCAE_TABLE_SIMULADORES;
        $table_meta = SCAE_TABLE_SIMULADORESMETA;

        $nome = sanitize_text_field($_POST['sim_name']);
        $abreviacao = sanitize_text_field($_POST['sim_abrev']);
        $descricao = sanitize_textarea_field($_POST['sim_desc']);
        $owner = sanitize_text_field($_POST['sim_owner']);
        $license = sanitize_text_field($_POST['sim_license']);
        $link = sanitize_text_field($_POST['sim_link']);
        $complexity = sanitize_text_field($_POST['sim_complexity']);
        $user_id = get_current_user_id();

        $simulador_name = $wpdb->get_results("SELECT * FROM {$table_name} WHERE nome = '$nome'");
        $simulador_abrev = $wpdb->get_results("SELECT * FROM {$table_name} WHERE abreviacao = '$abreviacao'");

        if (!empty($simulador_name)) {
            echo '<div class="error">Simulador com esse nome já existe!</div>';
            return;
        } else if (!empty($simulador_abrev)) {
            echo '<div class="error">Simulador com essa abreviação já existe!</div>';
            return;
        } else {
            $rows = $wpdb->insert(
                $table_name,
                array(
                    'nome' => $nome,
                    'abreviacao' => $abreviacao,
                    'descricao' => $descricao,
                    'user_id' => $user_id
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%d'
                )
            );
            $simulador_id = $wpdb->insert_id;

            $data = array(
                array(
                    'id' => $simulador_id,
                    'meta_tag' => 'owner',
                    'meta_content' => $owner
                ),
                array(
                    'id' => $simulador_id,
                    'meta_tag' => 'license',
                    'meta_content' => $license
                ),
                array(
                    'id' => $simulador_id,
                    'meta_tag' => 'link',
                    'meta_content' => $link
                ),
                array(
                    'id' => $simulador_id,
                    'meta_tag' => 'escolaridade',
                    'meta_content' => $complexity
                )
            );
            foreach ($data as $row) {
                $wpdb->insert(
                    $table_meta,
                    $row,
                    array(
                        '%d',
                        '%s',
                        '%s'
                    )
                );
            }
            wp_safe_redirect(home_url('/simuladores'));
            exit;
        }
    }

?>
    <div class="wrap">
        <form class="whole_form" method="post" action="">
            <label class="whole_form_label" for="sim_name">Nome do Simulador</label>
            <input class="whole_form_input" type="text" name="sim_name" id="sim_name" required>

            <label class="whole_form_label" for="sim_abrev">Abreviação</label>
            <input class="whole_form_input" type="text" name="sim_abrev" id="sim_abrev" required>

            <label class="whole_form_label" for="sim_desc">Descrição</label>
            <textarea class="whole_form_textarea" name="sim_desc" id="sim_desc" cols="100" rows="5" required></textarea>

            <label class="whole_form_label" for="sim_owner">Proprietário</label>
            <input class="whole_form_input" type="text" name="sim_owner" id="sim_owner" required></input>

            <label class="whole_form_label" for="sim_link">Link para o simulador</label>
            <input class="whole_form_input" type="text" name="sim_link" id="sim_link" required></input>

            <label class="whole_form_label" for="sim_license">Tipo de Licença</label>
            <select class="whole_form_select" name="sim_license" id="sim_license">
                <option value="Open-Source">Open-Source</option>
                <option value="Private">Private</option>
                <option value="Personal">Personal</option>
                <option value="Outros">Outros</option>
            </select>

            <label class="whole_form_label" for="sim_complexity">Nivel de Complexidade</label>
            <select class="whole_form_select" name="sim_complexity" id="sim_complexity">
                <option value="Fundamental">Fundamental</option>
                <option value="Médio">Médio</option>
                <option value="Superior">Superior</option>
            </select>

            <input class="whole_form_submit" class="whole_form_input" type="submit" name="submitSimulador" value="Adicionar Simulador">

        </form>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('simulador-form', 'simulador_form_page');
?>