<?php
function listarMeusSimuladores_form(){
    if(!is_user_logged_in()){
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
    $delete = isset($_GET['delete']) ? $_GET['delete'] : false;

    ob_start();
    global $wpdb;

    $simuladore_table = SCAE_TABLE_SIMULADORES;
    $SCAE_TABLE_META = SCAE_TABLE_SIMULADORESMETA;


    $user_id = get_current_user_id();
    $simuladores = $wpdb->get_results("SELECT * FROM {$simuladore_table} WHERE user_id = {$user_id}");

    $simuladores_id = array();
    foreach ($simuladores as $simulador) {
        $simuladores_id[] = $simulador->id;
    }
    if(in_array($delete, $simuladores_id)){
        $wpdb->query("DELETE FROM {$simuladore_table} WHERE id = {$delete}");
        $wpdb->query("DELETE FROM {$SCAE_TABLE_META} WHERE id = {$delete}");
        wp_redirect(remove_query_arg('delete'));
        exit;
    }

    ?>
    <a class="add_new_bt" href="<?php echo home_url("/criar-simulador")?>">Novo Simulador</a>
    <form class="search_form" method="get">
        <input class="searchform_field" type="text" name="user-search" id="user-search" value="<?php echo esc_attr(isset($_GET['user-search']) ? $_GET['user-search'] : ''); ?>">
        <input class="searchform_bt" type="submit" value="Buscar">
    </form> 
    <table class="whole_table">
        <thead class="table_head">
            <tr class="row_header">
                <th class="table_header">Nome Do Simulador</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Escolaridade</th>
                <th class="table_header">Proprietário</th>
                <th class="table_header">Tipo de Licença</th>
                <th class="table_header">Link Do Simulador</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody class="table_body">
            <?php foreach ($simuladores as $simulador) : 
            $simuladormeta = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE_META} WHERE id = %d", $simulador->id));
                 ?>
                <tr class="row_body">
                    <td class="row_field"><?php echo esc_html($simulador->nome); ?></td>
                    <td class="row_field"><?php echo esc_html($simulador->abreviacao); ?></td>
                    <td class="row_field"><?php echo esc_html($simulador->descricao); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[3]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[0]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[1]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[2]->meta_content); ?></td>
                    <td class="row_field">
                        <a href="<?php echo home_url('/editar-simulador/?simulador='. $simulador->id) ?>">Editar</a>
                        <a href="<?php echo add_query_arg(['delete' => $simulador->id]) ?>">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php

    return ob_get_clean();
}

add_shortcode(SCAE_LISTAR_MEUS_SIMULADORES_SHORTCODE, 'listarMeusSimuladores_form');
?>