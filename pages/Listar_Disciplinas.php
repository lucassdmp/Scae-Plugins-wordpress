<?php
function listar_disciplinas_shortcode($atts) {
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
    global $wpdb;
    // Extract the attributes
    extract(shortcode_atts(array(), $atts));

    $SCAE_TABLE = $wpdb->prefix . 'scae_disciplinas';

    if (!empty($_GET['user-search'])) {
        $search_term = "%{$_GET['user-search']}%";
        $disciplinas = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE} WHERE disc_name LIKE %s ORDER BY disc_id", $search_term));
    } else {
        $disciplinas = $wpdb->get_results("SELECT * FROM {$SCAE_TABLE} ORDER BY disc_id");
    }
    
    // Start the output buffer
    ob_start();
  
    // Display the search form
    ?>
    <form method="get">
      <label for="user-search">Buscar:</label>
      <input type="text" name="user-search" id="user-search" value="<?php echo esc_attr(isset($_GET['user-search']) ? $_GET['user-search'] : ''); ?>">
      <input type="submit" value="Buscar">
    </form>
    <?php
  
    // Display the table
    ?>
    <table class="whole_table">
        <thead class="table_head">
            <tr>
                <th class="table_header">Nome Do Disciplina</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody class="table_body">
            <?php 
                $shortcode_posts = get_posts( array(
                    'posts_per_page' => -1, // get all posts
                    'post_type'      => 'page', // only search pages
                    's'              => '[aulas_disciplina]', // search for the shortcode
                ));
                
                $shortcode_post = $shortcode_posts[0];
                foreach ($disciplinas as $disciplina) : 
                 ?>
                <tr>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_name); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_abrev); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_desc); ?></td>
                    <td class="table_field">
                        <a href="<?php
                            echo home_url() . '/' . $shortcode_post->post_name . '?disciplina=' . $disciplina->disc_id;
                            ?>">Ver Aulas</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
  
    // Return the output buffer
    return ob_get_clean();
}
function enqueue_simple_form(){
    wp_enqueue_style('simple_form_css', ABSPATH . '/wp-content/plugins/Scae-Plugins-wordpress/css/simple_form.css');
}
add_action('wp_enqueue_scripts', 'enqueue_simple_form');

?>
