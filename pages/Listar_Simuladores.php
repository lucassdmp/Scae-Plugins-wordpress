<?php

//enqueue style
function load_simple_form_style() {
    wp_enqueue_style( 'simple_form_style', plugins_url( 'css/simple_form.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'load_simple_form_style' );

function simulador_table_shortcode($atts) {
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
    global $wpdb;
    // Extract the attributes
    extract(shortcode_atts(array(), $atts));
    $SCAE_TABLE = $wpdb->prefix . "scae_simuladores";

    if (!empty($_GET['user-search'])) {
        $search_term = "%{$_GET['user-search']}%";
        $simuladores = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE} WHERE sim_name LIKE %s ORDER BY sim_id", $search_term));
    } else {
        $simuladores = $wpdb->get_results("SELECT * FROM {$SCAE_TABLE} ORDER BY sim_id");
    }

        
    // Start the output buffer
    ob_start();
  
    // Display the search form
    ?>
    <div class="flex">
        <form class="search_form" method="get">
            <input class="user_search" type="text" name="user-search" id="user-search" value="<?php echo esc_attr(isset($_GET['user-search']) ? $_GET['user-search'] : ''); ?>">
            <input type="submit" value="Buscar">
        </form>
    </div>
    <table class="whole_table">
        <thead class="table_head">
            <tr>
                <th class="table_header">Nome Do Simulador</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody class="table_body">
            <?php foreach ($simuladores as $simulador) : 
                 ?>
                <tr>
                    <td class="table_field"><?php echo esc_html($simulador->sim_name); ?></td>
                    <td class="table_field"><?php echo esc_html($simulador->sim_abrev); ?></td>
                    <td class="table_field"><?php echo esc_html($simulador->sim_desc); ?></td>
                    <td class="table_field">
                        <a href="<?php echo home_url() . '/aulas-para-simulador/?simulador=' . $simulador->sim_id ?>">Aulas Com Simulador</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
  
    // Return the output buffer
    return ob_get_clean();
}

?>
