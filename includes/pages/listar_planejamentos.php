<?php

function add_listar_planejamento()
{
    ob_start();
    $pdf = isset($_GET['pdf']) ? intval($_GET['pdf']) : null;

    if (isset($_GET['delete_planejamento'])) {
        global $wpdb;
        $wpdb->delete(SCAE_TABLE_PLANEJAMENTOS, array('planejamento_id' => $_GET['delete_planejamento']));
        $wpdb->delete(SCAE_TABLE_AULAS_PLANEJAMENTO, array('planejamento_id' => $_GET['delete_planejamento']));
        wp_safe_redirect(remove_query_arg('delete_planejamento'));
        exit;
    }

    if (isset($_GET['copy_planejamento'])) {
        global $wpdb;
        $planejamento = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_PLANEJAMENTOS . " WHERE id = {$_GET['copy_planejamento']}");
        $plan_user_atual = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_PLANEJAMENTOS . " WHERE user_id = " . get_current_user_id());
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
            $aulas = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_AULAS_PLANEJAMENTO . " WHERE planejamento_id = {$_GET['copy_planejamento']}");
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
    if ($pdf === null) :
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
                    $planejamentos = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_PLANEJAMENTOS);
                    foreach ($planejamentos as $planejamento) :
                        $disciplina = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_DISCIPLINAS . " WHERE id = {$planejamento->disc_id}");
                        $aulas_in_planning = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_AULAS_PLANEJAMENTO . " WHERE planejamento_id = {$planejamento->id}");
                        $horas_in_planning = 0;
                        foreach ($aulas_in_planning as $aula_in_planning) :
                            $aula = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_ATIVIDADES . " WHERE id = {$aula_in_planning->aula_id}");
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
    endif;
    if (is_numeric($pdf)) :
        global $wpdb;
        $planejamento = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_PLANEJAMENTOS . " WHERE id = {$pdf}")[0];
        $aulas_in_planning = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_AULAS_PLANEJAMENTO . " JOIN " . SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR . " ON " . SCAE_TABLE_AULAS_PLANEJAMENTO . ".aula_id = " . SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR . ".aula_id WHERE planejamento_id = {$planejamento->id}");
        $disciplina = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_DISCIPLINAS . " WHERE id = {$planejamento->disc_id}");
        $simuladores = array();
        $aulas = array();
        foreach ($aulas_in_planning as $aula_in_planning) :
            $aulas[] = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_ATIVIDADES . " WHERE id = {$aula_in_planning->aula_id}")[0];
            $simuladores[] = $wpdb->get_results("SELECT * FROM " . SCAE_TABLE_SIMULADORES . " WHERE id = {$aula_in_planning->sim_id}")[0];
        endforeach;
    ?>
        <div class="pdf_table">
            <div class="pdf_header">
                <img src="<?php echo SCAE_IMG . '/logo.png' ?>" alt="logo" class="pdf_logo_medium">
                <h1 class="pdf_title">Planejamento de Aulas</h1>
                <h2 class="pdf_subtitle"><?php echo $disciplina[0]->nome; ?></h2>
                <h3 class="pdf_subsubtitle">Criado em scae.academy</h3>
                <h4 class="pdf_descricao">Esse é uma planejamento de aula criado com base no simulador: </h4>
            </div>
            <table class="table_pdf">
                <thead class="table_pdf_header">
                    <td class="table_pdf_header_field">Data</td>
                    <td class="table_pdf_header_field">Duração</td>
                    <td class="table_pdf_header_field">Nome</td>
                    <td class="table_pdf_header_field">Descrição</td>
                    <td class="table_pdf_header_field">Simulador</td>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count($aulas); $i++) : ?>
                        <tr class="table_pdf_row">
                            <td class="table_pdf_field"><input class="table_pdf_date" type="date" name="atividade_date[]"></td>
                            <td class="table_pdf_field"><?php echo $aulas[$i]->duracao; ?></td>
                            <td class="table_pdf_field"><?php echo $aulas[$i]->nome; ?></td>
                            <td class="table_pdf_field"><?php echo $aulas[$i]->descricao; ?></td>
                            <td class="table_pdf_field"><?php echo $simuladores[$i]->nome ?></td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
            <button class="generate_pdf" onclick="generatePDF()">Generate PDF</button>
        </div>
        <script>
            function generatePDF() {
                const atividadeDateInputs = document.querySelectorAll('.table_pdf_date');
                // For each atividade_date input
                atividadeDateInputs.forEach(input => {
                    // Get the value of the input
                    const date = input.value;
                    // Split the value by '-' to get the year, month, and day
                    const [year, month, day] = date.split('-');
                    // Create a new p element with the DD/MM/YYYY format of the date
                    const p = document.createElement('p');
                    p.textContent = `${day}/${month}/${year}`;
                    // Replace the input with the p element
                    input.parentNode.replaceChild(p, input);
                });

                //get all elements from .pdf_table except 
                const div = document.querySelector('.pdf_table');
                //remove generate_pdf button specificy
                const generate_pdf_button = document.querySelector('.generate_pdf');
                generate_pdf_button.parentNode.removeChild(generate_pdf_button);
                //remove everything from body
                document.body.innerHTML = div.innerHTML;

                // Open the print dialogue
                window.print();

                // // Show all hidden elements again
                // elements.forEach(element => {
                //     element.style.display = 'block';
                // });
            }
        </script>
<?php
    endif;
    return ob_get_clean();
}

add_shortcode(SCAE_LISTAR_PLANEJAMENTOS_SHORTCODE, 'add_listar_planejamento');
?>