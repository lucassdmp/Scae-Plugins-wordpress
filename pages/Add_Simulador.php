<?php
function simulador_form_page(){
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
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
                    <option value="Fundamental 1">Fundamental 1</option>
                    <option value="Fundamental 2">Fundamental 2</option>
                    <option value="Superior">Superior</option>
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

function save_simulador_to_db(){
    global $wpdb;
    if (isset($_POST['sim_name']) && isset($_POST['sim_abrev']) && isset($_POST['sim_desc']) && isset($_POST['sim_owner']) && isset($_POST['sim_license']) && isset($_POST['sim_link']) && isset($_POST['sim_complexity'])) {
        $name = sanitize_text_field($_POST['sim_name']);
        $abrev = sanitize_text_field($_POST['sim_abrev']);
        $desc = sanitize_textarea_field($_POST['sim_desc']);
        $owner = sanitize_text_field($_POST['sim_owner']);
        $license = sanitize_text_field($_POST['sim_license']);
        $link = sanitize_text_field($_POST['sim_link']);
        $complexity = sanitize_text_field($_POST['sim_complexity']);

        $simulador_name = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_simuladores WHERE sim_name = '$name'");
        if (!empty($simulador_name)) {
            echo '<div class="error">Simulador com esse nome já existe!</div>';
            return;
        }

        $simulador_abrev = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}scae_simuladores WHERE sim_abrev = '$abrev'");
        if (!empty($simulador_abrev)) {
            echo '<div class="error">Simulador com essa abreviação já existe!</div>';
            return;
        }
        else {
            $table_name = $wpdb->prefix . 'scae_simuladores';
            $wpdb->insert( 
            $table_name, 
            array( 
                'sim_name' => $name, 
                'sim_abrev' => $abrev,
                'sim_desc' => $desc, 
                'sim_owner' => $owner,
                'sim_license' => $license,
                'sim_link' => $link,
                'sim_complexity' => $complexity
                ) 
            );
            wp_safe_redirect(home_url());
            exit;
        }
       
    }
}
?>