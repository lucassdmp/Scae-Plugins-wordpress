<?php

//enqueue style
function simulador_table_shortcode($atts) {
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
    global $wpdb;
    // Extract the attributes
    extract(shortcode_atts(array(), $atts));
    $SCAE_TABLE = SCAE_TABLE_SIMULADORES;
    $SCAE_TABLE_META = SCAE_TABLE_SIMULADORESMETA;

    if (!empty($_GET['user-search'])) {
        $search_term = "%{$_GET['user-search']}%";
        $simuladores = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE} WHERE nome LIKE %s ORDER BY id", $search_term));
    } else {
        $simuladores = $wpdb->get_results("SELECT * FROM {$SCAE_TABLE} ORDER BY id");
    }

    $delete = isset($_GET['delete']) ? $_GET['delete'] : false;

    if ($delete) {
        $wpdb->delete($SCAE_TABLE, array('id' => $delete));
        $wpdb->delete($SCAE_TABLE_META, array('id' => $delete));
        wp_redirect(home_url(SCAE_LISTAR_SIMULADORES_PAGESLUG));
    }
        
    // Start the output buffer
    ob_start();
  
    // Display the search form
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
                <th class="table_header">Dono</th>
                <th class="table_header">Tipo de Licença</th>
                <th class="table_header">Link Do Simulador</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody class="table_body">
            <?php foreach ($simuladores as $simulador) : 
            $simuladormeta = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE_META} WHERE id = %d", $simulador->id));
                 ?>
                <tr>
                    <td class="row_field"><?php echo esc_html($simulador->nome); ?></td>
                    <td class="row_field"><?php echo esc_html($simulador->abreviacao); ?></td>
                    <td class="row_field"><?php echo esc_html($simulador->descricao); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[3]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[0]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[1]->meta_content); ?></td>
                    <td class="row_field"><?php echo esc_html($simuladormeta[2]->meta_content); ?></td>
                    <td class="row_field">
                        <a href="<?php echo home_url('/atividades-do-simulador/?simulador='. $simulador->id) ?>">Aulas Com Simulador</a>
                        <?php if(current_user_can('administrator')):?>
                            <a href="<?php echo home_url('/editar-simulador/?simulador='. $simulador->id) ?>">Editar</a>
                            <a href="<?php echo add_query_arg(array('delete' => $simulador->id)) ?>">Excluir</a>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
  
    // Return the output buffer
    return ob_get_clean();
}
add_shortcode('listar-simuladores', 'simulador_table_shortcode');

?>
