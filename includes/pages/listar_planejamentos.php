<?php

function add_listar_planejamento()
{
    ob_start();


    if (isset($_GET['delete_planejamento'])) {
        global $wpdb;
        $wpdb->delete(SCAE_TABLE_PLANEJAMENTOS, array('planejamento_id' => $_GET['delete_planejamento']));
        $wpdb->delete(SCAE_TABLE_AULAS_PLANEJAMENTO, array('planejamento_id' => $_GET['delete_planejamento']));
        wp_safe_redirect(remove_query_arg('delete_planejamento'));
        exit;
    }

    if (isset($_GET['copy_planejamento'])) {
        global $wpdb;
        $planejamento = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_PLANEJAMENTOS." WHERE id = {$_GET['copy_planejamento']}");
        $plan_user_atual = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_PLANEJAMENTOS." WHERE user_id = " . get_current_user_id());
        $disc_id_user_atual = array();
        foreach ($plan_user_atual as $plan) {
            $disc_id_user_atual[] = intval($plan->disc_id);
        }
        if (intval($planejamento[0]->user_id) == get_current_user_id()) {
            wp_safe_redirect(remove_query_arg('copy_planejamento'));
            echo '<div class="alert alert-danger" role="alert">Você não pode copiar um planejamento seu!</div>';
            exit;
        } else if (in_array(intval($planejamento[0]->disc_id), $disc_id_user_atual)) {
            wp_safe_redirect(remove_query_arg('copy_planejamento'));
            echo '<div class="alert alert-danger" role="alert">Você já possui um planejamento para essa disciplina!</div>';
            exit;
        } else {
            $aulas = $wpdb->get_results("SELECT * FROM ".SCAE_TABLE_AULAS_PLANEJAMENTO." WHERE planejamento_id = {$_GET['copy_planejamento']}");
            $wpdb->insert(
                SCAE_TABLE_PLANEJAMENTOS,
                array(
                    'disc_id' => $planejamento[0]->disc_id,
                    'user_id' => get_current_user_id(),
                )
            );
            $planejamento_id_insert = $wpdb->insert_id;
            foreach ($aulas as $aula) {
                $wpdb->insert(
                    SCAE_TABLE_AULAS_PLANEJAMENTO,
                    array(
                        'planejamento_id' => $planejamento_id_insert,
                        'aula_id' => $aula->aula_id,
                    )
                );
            }
            wp_safe_redirect(remove_query_arg('copy_planejamento'));
            exit;
        }
    }
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
                global $wpdb;
                $planejamentos = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_PLANEJAMENTOS);
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
                            <a href="<?php echo add_query_arg(array('copy_planejamento' => $planejamento->id)) ?>">Copiar Planejamento</a>
                            <a href="<?php echo add_query_arg(array('pdf' => $planejamento->id)) ?>">Visualizar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
    return ob_get_clean();
}

add_shortcode(SCAE_LISTAR_PLANEJAMENTOS_SHORTCODE, 'add_listar_planejamento');
?>