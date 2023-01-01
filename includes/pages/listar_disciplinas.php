<?php
function listar_disciplinas_shortcode($atts) {
    if(!is_user_logged_in()){
        wp_safe_redirect(home_url());
        exit;
    }
    global $wpdb;
    // Extract the attributes
    extract(shortcode_atts(array(), $atts));

    $SCAE_TABLE = SCAE_TABLE_DISCIPLINAS;

    if (!empty($_GET['user-search'])) {
        $search_term = "%{$_GET['user-search']}%";
        $disciplinas = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$SCAE_TABLE} WHERE nome LIKE %s ORDER BY id", $search_term));
    } else {
        $disciplinas = $wpdb->get_results("SELECT * FROM {$SCAE_TABLE} ORDER BY id");
    }

    // $shc = SCAE_LISTAR_ATIVIDADES_DISCIPLINA_SHORTCODE;
    // $shortcode_posts = get_posts( array(
    //     'posts_per_page' => -1, // get all posts
    //     'post_type'      => 'page', // only search pages
    //     's'              => "[$shc]", // search for the shortcode
    // ));
    
    // $shortcode_post = $shortcode_posts[0];
    
    // Start the output buffer
    ob_start();
  
    // Display the search form
    ?>
    <a class="add_new_bt" href="<?php echo home_url("/criar-disciplina")?>">Nova Disciplina</a>
    <form class="search_form" method="get">
        <input class="searchform_field" type="text" name="user-search" id="user-search" value="<?php echo esc_attr(isset($_GET['user-search']) ? $_GET['user-search'] : ''); ?>">
        <input class="searchform_bt" type="submit" value="Buscar">
    </form>
    <table class="whole_table">
        <thead class="table_head">
            <tr>
                <th class="table_header">Nome Do Disciplina</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Criador</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody class="table_body">
            <?php foreach ($disciplinas as $disciplina) : 
                $disc_user = get_user_by('id', $disciplina->user_id);
                ?>
                <tr>
                    <td class="table_field"><?php echo esc_html($disciplina->nome); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->abreviacao); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->descricao); ?></td>
                    <td class="table_field"><?php echo esc_html($disc_user->display_name); ?></td>
                    <td class="table_field">
                        <a href="#">Ver Aulas</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
  
    // Return the output buffer
    return ob_get_clean();
}
add_shortcode(SCAE_LISTAR_DISCIPLINAS_SHORTCODE, 'listar_disciplinas_shortcode');


?>
