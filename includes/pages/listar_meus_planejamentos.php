<?php
function listar_meus_planejamentos(){
    ob_start();

    if(!is_user_logged_in()){
        wp_redirect(get_home_url());
        exit;
    }

    global $wpdb;
    $planejamentos = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_PLANEJAMENTOS . " WHERE user_id = " . get_current_user_id());

    ?>
    <div>
        <table class="whole_table">
            <thead class="table_head">
                <tr class="row_header">
                    <th class="table_header">Disciplina</th>
                    <th class="table_header">Horas Do Planejamento</th>
                    <th class="table_header">Autor</th>
                    <th class="table_header">Ações</th>
                </tr>
            </thead>
            <tbody class="table_body">
                <?php
                foreach ($planejamentos as $planejamento) :
                    $disciplina = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_DISCIPLINAS." WHERE id = {$planejamento->disc_id}");
                    $aulas_in_planning = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_AULAS_PLANEJAMENTO." WHERE planejamento_id = {$planejamento->id}");
                    $horas_in_planning = 0;
                    foreach ($aulas_in_planning as $aula_in_planning) :
                        $aula = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_ATIVIDADES." WHERE id = {$aula_in_planning->aula_id}");
                        $horas_in_planning += $aula[0]->duracao;
                    endforeach;
                ?>
                    <tr class="row_body">
                        <td class="row_field"><?php echo $disciplina[0]->nome; ?></td>
                        <td class="row_field"><?php echo $horas_in_planning ?></td>
                        <td class="row_field"><?php echo get_userdata($planejamento->user_id)->display_name; ?></td>
                        <td class="row_field">
                            <a href="<?php echo home_url(SCAE_CRIAR_PLANEJAMENTO_PAGESLUG. '/?disciplina='. $disciplina[0]->id) ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm("Tem certeza de que deseja excluir este planejamento?")) {
                window.location.href = "<?php echo add_query_arg(array('delete_planejamento' => $planejamento->planning_id)); ?>";
            }
        }
    </script>
    <?php
    return ob_get_clean();
}

add_shortcode(SCAE_LISTAR_MEUS_PLANEJAMENTOS_SHORTCODE, 'listar_meus_planejamentos');
?>