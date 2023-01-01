<?php 
function create_planning_form(){
    if(!is_user_logged_in()){
        //check if current page is diferent than home page or login page
        wp_redirect(home_url("/login"));
        exit;
    }
    ob_start();  
    global $wpdb;
    $table_name = SCAE_TABLE_PLANEJAMENTOS;
    $disciplina_id = isset($_GET['disciplina']) ? intval($_GET['disciplina']) : 0;
    if($disciplina_id == 0){
        ?>
        <div class="div_planejamento">
            <?php
            ?>
            <form action="" method="get" class="form_center">
                <div class="select_field">
                    <label class="label_menu" for="disciplina">Disciplina</label>
                    <select class="select_menu" name="disciplina" id="disciplina" required>
                        <?php
                        $disciplinas = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_DISCIPLINAS);
                        foreach($disciplinas as $disciplina){
                            echo "<option value='{$disciplina->id}'>{$disciplina->nome}</option>";
                        }
                        ?>
                    </select>
                </div>
                <input class="select_submit" type="submit" value="Criar Planejamento">
            </form>
        </div>
        <?php
    }else{
        $planning = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = ".get_current_user_id()." AND disc_id = $disciplina_id");
        
        if($planning){
            $planning_id = $planning->id;
            $disciplina = $planning->disc_id;
        }else{
            $wpdb->insert($table_name, array(
                'user_id' => get_current_user_id(),
                'disc_id' => $disciplina_id
            ));
            $planning_id = $wpdb->insert_id;
        }

        $aulas_in_planning = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_AULAS_PLANEJAMENTO . " WHERE planejamento_id = $planning_id");
        $aulas_ids = array();
        foreach($aulas_in_planning as $aula){
            $aulas_ids[] = $aula->aula_id;
        }
        // if($disciplina_id == 0){
        //     wp_safe_redirect(home_url());
        //     exit();
        // }
    
        $disc = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_DISCIPLINAS .  " WHERE id = $disciplina_id");
        $aulas_disciplina = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_ATIVIDADES . " JOIN ". SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR. " ON ". SCAE_TABLE_ATIVIDADES . ".id = ". SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR . ".aula_id WHERE disc_id = $disciplina_id");

        ob_clean();
        ?>
            <div>
                <h1 class="nome_disciplina"><?php echo $disc[0]->nome ?></h1></div>
                <div class="flex_div"><h3 class="horas_disciplinas">Total de Horas da Disciplina: <?php echo $disc[0]->carga_horaria ?></h3>
                <h5 class="Total_Horas">Total de Horas nas Aulas: 0</h5>
            </div>
            <form action="" method="post">
                <table class="whole_table">
                    <thead class="table_head">
                        <tr class="row_head">
                            <th class="table_header">Marcar</th>
                            <th class="table_header">Aula</th>
                            <th class="table_header">Descrição</th>
                            <th class="table_header">Simulador Usado</th>
                            <th class="table_header">Tempo de Aula</th>
                            <th class="table_header">Tipo de Aula</th>
                            <th class="table_header">Links da Aula</th>
                        </tr>
                    </thead>
                    <tbody class="whole_body"> 
                        <?php foreach($aulas_disciplina as $aula):
                            $aula_sim_id = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR ." WHERE aula_id = {$aula->id}");
                            $aula_sim = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_SIMULADORES . " WHERE id = {$aula_sim_id[0]->sim_id}");
                            ?>
                            <tr class="row_body"> 
                                <td class='row_field mark'>
                                    <label>
                                        <input type='checkbox' name='mark_row[]' value="<?php echo $aula->id . '/' .  $aula->duracao?>" <?php if (in_array($aula->id, $aulas_ids)) echo "checked" ?>>
                                    </label>
                                </td>
                                <td class="row_field"><?php echo $aula->nome?></td> 
                                <td class="row_field"><?php echo $aula->descricao?></td> 
                                <td class="row_field"><?php echo $aula_sim[0]->nome?></td> 
                                <td class="row_field"><?php echo $aula->duracao?></td> 
                                <td class="row_field"><?php echo $aula->categoria?></td> 
                                <td class='row_field'>
                                    <?php $aulas_meta = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_ATIVIDADESMETA ." WHERE id = {$aula->id}"); 
                                    foreach ($aulas_meta as $meta): ?>
                                        <a href="<?php echo $meta->meta_content?>"class='aula_links'><?php echo $meta->meta_tag?></a>
                                        <br>
                                    <?php endforeach ?>
                                </td>
                            </tr> 
                        <?php endforeach ?>
                    </tbody> 
                </table> 
            <button class='submitPlanejamento' type='submit' class='btn btn-primary' name='submitPlanejamento'>Salvar Planejamento</button>
            </form >
            <script>
                function updateTotalHoras() {
                    let totalHoras = 0;
                    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        if (checkbox.checked) {
                            totalHoras += parseInt(checkbox.value.split('/')[1]);
                        }
                    });
                    const totalHorasElement = document.querySelector('.Total_Horas');
                    totalHorasElement.innerHTML = `TOTAL DE HORAS NAS AULAS: ${totalHoras}`;
                }

                updateTotalHoras(); // Update total horas on page load

                document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                    checkbox.addEventListener('click', function() {
                        updateTotalHoras();
                    });
                });
            </script>
            </form>
        <?php
        if(isset($_POST['submitPlanejamento'])){
            $marked_rows = isset($_POST['mark_row']) ? $_POST['mark_row'] : array();
            // var_dump($marked_rows);

            $total_horas_submit = 0;
            foreach($marked_rows as $row){
                $total_horas_submit += intval(explode('/', $row)[1]);
            }
            $disciplina_s = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_DISCIPLINAS ." WHERE id = $disciplina_id")[0];
            if ($total_horas_submit > $disciplina_s->carga_horaria) {
                echo "<div class='alert alert-danger'>O total de horas nas aulas selecionadas é maior que o total de horas da disciplina.</div>";
            }else{
                $all_aulas_in_planning = $wpdb->get_results("SELECT * FROM ". SCAE_TABLE_AULAS_PLANEJAMENTO ." WHERE planejamento_id = $planning_id");
                          
                $marked_aula_ids = array();
                foreach($marked_rows as $row){
                    $marked_aula_ids[] = intval(explode('/', $row)[0]);
                   
                }
                // Delete any aulas in the planning that are not in the marked rows
                foreach ($all_aulas_in_planning as $aula_in_planning) {
                    if (!in_array(intval($aula_in_planning->aula_id), $marked_aula_ids)) {
                        $wpdb->delete(SCAE_TABLE_AULAS_PLANEJAMENTO, array('ap_id' => $aula_in_planning->ap_id));
                    }
                }
                
                foreach($marked_rows as $row){
                    $aula_id_s = intval(explode('/', $row)[0]);
                    //check if theres already a row with this planning_id and aula_id
                    $aula_in_planning = $wpdb->get_row("SELECT * FROM ".SCAE_TABLE_AULAS_PLANEJAMENTO . " WHERE planejamento_id = $planning_id AND aula_id = $aula_id_s");
                    if(!$aula_in_planning){
                        $wpdb->insert(SCAE_TABLE_AULAS_PLANEJAMENTO, array(
                            'planejamento_id' => $planning_id,
                            'aula_id' => $aula_id_s
                        ));
                    }
                }
            }
        }
    }
    return ob_get_clean();
}
add_shortcode('planejamento-form', 'create_planning_form');
?>