<?php
function simulador_form_page(){
    ?>
    <div class="wrap">
        <form method="post" action="">
            <div>
                <label for="sim_name">Nome do Simulador</label>
                <input type="text" name="sim_name" id="sim_name" required>
            </div>
            <div>
                <label for="sim_abrev">Abreviação</label>
                <input type="text" name="sim_abrev" id="sim_abrev" required>
            </div>
            <div>
                <label for="sim_desc">Descrição</label>
                <textarea name="sim_desc" id="sim_desc" cols="100" rows="5" required></textarea>
            </div>
            <div>
                <label for="sim_owner">Proprietário</label>
                <input type="text" name="sim_owner" id="sim_owner" required></input>
            </div>
            <div>
                <label for="sim_license">Tipo de Licença</label>
                <select name="sim_license" id="sim_license">
                    <option value="Open-Source">Open-Source</option>
                    <option value="Private">Private</option>
                    <option value="Personal">Personal</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>
            <div>
                <label for="sim_link">Link para o simulador</label>
                <input type="text" name="sim_link" id="sim_link" required></input>
            </div>
            <div>
                <label for="sim_complexity">Nivel de Complexidade</label>
                <select name="sim_complexity" id="sim_complexity">
                    <option value="1">Fundamental 1</option>
                    <option value="2">Fundamental 2</option>
                    <option value="3">Superior</option>
                    <option value="4">Mestrado</option>
                    <option value="5">Doutorado</option>
                </select>
            </div>
            <div>
                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
    </div>
    <?php
}

function simulador_shortcode(){
    ob_start();
    simulador_form_page();
    return ob_get_clean();
}

add_shortcode('simulador', 'simulador_shortcode');

function save_simulador_to_db(){
    global $wpdb;
    if (isset($_POST['submit'])) {
        $name = sanitize_text_field($_POST['sim_name']);
        $abrev = sanitize_text_field($_POST['sim_abrev']);
        $desc = sanitize_textarea_field($_POST['sim_desc']);
        $owner = sanitize_text_field($_POST['sim_owner']);
        $license = sanitize_text_field($_POST['sim_license']);
        $link = sanitize_text_field($_POST['sim_link']);
        $complexity = sanitize_text_field($_POST['sim_complexity']);

        $table_name = $wpdb->prefix . 'simulador';
        $wpdb->insert( 
        $table_name, 
        array( 
            'sim_id' => 0,
            'sim_name' => $name, 
            'sim_abrev' => $abrev,
            'sim_desc' => $desc, 
            'sim_owner' => $owner,
            'sim_license' => $license,
            'sim_link' => $link,
            'sim_complexity' => $complexity
            ) 
        );
    }
}

add_action('init', 'save_simulador_to_db');

?>