<?php

function listar_disciplinas_shortcode($atts) {
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
    <table>
        <thead>
            <tr>
                <th class="table_header">Nome Do Disciplina</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($disciplinas as $disciplina) : 
                 ?>
                <tr>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_name); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_abrev); ?></td>
                    <td class="table_field"><?php echo esc_html($disciplina->disc_desc); ?></td>
                    <td class="table_field">
                        <a href="">Ver Aulas</a>
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
