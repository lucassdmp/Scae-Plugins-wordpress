<?php
function simulador_table_shortcode($atts) {
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
                <th class="table_header">Nome Do Simulador</th>
                <th class="table_header">Abreviação</th>
                <th class="table_header">Descrição</th>
                <th class="table_header">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($simuladores as $simulador) : 
                 ?>
                <tr>
                    <td class="table_field"><?php echo esc_html($simulador->sim_name); ?></td>
                    <td class="table_field"><?php echo esc_html($simulador->sim_abrev); ?></td>
                    <td class="table_field"><?php echo esc_html($simulador->sim_desc); ?></td>
                    <td class="table_field"> TEST </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
  
    // Return the output buffer
    return ob_get_clean();
}

?>
