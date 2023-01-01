<?php

function editar_simulador()
{
    if (!is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }
    global $wpdb;

    $current_user_id = get_current_user_id();

    $simulador_id = isset($_GET['simulador']) ? $_GET['simulador'] : false;

    if (isset($_POST['submitSimulador'])) {
        // var_dump($_POST);
        $wpdb->update(SCAE_TABLE_SIMULADORES, array(
            'nome' => $_POST['sim_name'],
            'abreviacao' => $_POST['sim_abrev'],
            'descricao' => $_POST['sim_desc'],
        ), array('id' => $simulador_id));

        $wpdb->update(SCAE_TABLE_SIMULADORESMETA, array(
            'meta_content' => $_POST['sim_owner'],
        ), array('id' => $simulador_id, 'meta_tag' => 'owner'));

        $wpdb->update(SCAE_TABLE_SIMULADORESMETA, array(
            'meta_content' => $_POST['sim_link'],
        ), array('id' => $simulador_id, 'meta_tag' => 'link'));

        $wpdb->update(SCAE_TABLE_SIMULADORESMETA, array(
            'meta_content' => $_POST['sim_license'],
        ), array('id' => $simulador_id, 'meta_tag' => 'license'));

        $wpdb->update(SCAE_TABLE_SIMULADORESMETA, array(
            'meta_content' => $_POST['sim_complexity'],
        ), array('id' => $simulador_id, 'meta_tag' => 'escolaridade'));
        wp_redirect(remove_query_arg('simulador'));
    }

    ob_start();


    $simulador = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_SIMULADORES . " WHERE id =  {$simulador_id}")[0];

    if (!$simulador || !$simulador_id || (intval($simulador->user_id) != $current_user_id && !current_user_can('administrator'))) {
        wp_redirect(home_url('/meus-simuladores'));
        exit;
    } else {
        $simulador_meta = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_SIMULADORESMETA . " WHERE id = " . $simulador_id);
?>
        <div class="wrap">
            <form class="whole_form" method="post" action="">
                <label class="whole_form_label" for="sim_name">Nome do Simulador</label>
                <input class="whole_form_input" type="text" name="sim_name" id="sim_name" value="<?php echo $simulador->nome ?>" required>

                <label class="whole_form_label" for="sim_abrev">Abreviação</label>
                <input class="whole_form_input" type="text" name="sim_abrev" id="sim_abrev" value="<?php echo $simulador->abreviacao ?>" required>

                <label class="whole_form_label" for="sim_desc">Descrição</label>
                <textarea class="whole_form_textarea" name="sim_desc" id="sim_desc" cols="100" rows="5" required><?php echo $simulador->descricao ?></textarea>

                <label class="whole_form_label" for="sim_owner">Proprietário</label>
                <input class="whole_form_input" type="text" name="sim_owner" id="sim_owner" value="<?php echo $simulador_meta[0]->meta_content ?>" required></input>

                <label class="whole_form_label" for="sim_link">Link para o simulador</label>
                <input class="whole_form_input" type="text" name="sim_link" id="sim_link" value="<?php echo $simulador_meta[2]->meta_content ?>" required></input>

                <label class="whole_form_label" for="sim_license">Tipo de Licença</label>
                <select class="whole_form_select" name="sim_license" id="sim_license">
                    <option <?php if ($simulador_meta[1]->meta_content == "Open-Source") echo "selected" ?> value="Open-Source">Open-Source</option>
                    <option <?php if ($simulador_meta[1]->meta_content == "Private") echo "selected" ?> value="Private">Private</option>
                    <option <?php if ($simulador_meta[1]->meta_content == "Personal") echo "selected" ?> value="Personal">Personal</option>
                    <option <?php if ($simulador_meta[1]->meta_content == "Outros") echo "selected" ?> value="Outros">Outros</option>
                </select>

                <label class="whole_form_label" for="sim_complexity">Nivel de Complexidade</label>
                <select class="whole_form_select" name="sim_complexity" id="sim_complexity">
                    <option <?php if ($simulador_meta[3]->meta_content == "Fundamental") echo "selected" ?> value="Fundamental">Fundamental</option>
                    <option <?php if ($simulador_meta[3]->meta_content == "Médio") echo "selected" ?> value="Médio">Médio</option>
                    <option <?php if ($simulador_meta[3]->meta_content == "Superior") echo "selected" ?> value="Superior">Superior</option>
                </select>

                <input class="whole_form_submit" class="whole_form_input" type="submit" name="submitSimulador" value="Salvar Simulador">

            </form>
        </div>
<?php
    }
    return ob_get_clean();
}

add_shortcode(SCAE_EDITAR_SIMULADOR_SHORTCODE, 'editar_simulador');

?>