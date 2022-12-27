<?php
function create_planning_form(){
    ob_start();
    global $wpdb;
    $table_name = "wp_scae_planning";
    $disciplina = isset($_GET['disciplina']) ? intval($_GET['disciplina']) : 0;
    if($disciplina == 0){
        ?>
        <form action="" method="get" class="form_center">
            <div class="select_field">
                <label for="disciplina">Disciplina</label>
                <select name="disciplina" id="disciplina" required>
                    <?php
                    $disciplinas = $wpdb->get_results("SELECT * FROM wp_scae_disciplinas");
                    foreach($disciplinas as $disciplina){
                        echo "<option value='{$disciplina->disc_id}'>{$disciplina->disc_name}</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Criar Planejamento">
        <?php
    }else{
        
        $planning = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = ".get_current_user_id()." AND disc_id = $disciplina");
        
        if($planning){
            $planning_id = $planning->planning_id;
            $disciplina = $planning->disc_id;
        }else{
            $wpdb->insert($table_name, array(
                'user_id' => get_current_user_id(),
                'disc_id' => $disciplina
            ));
            $planning_id = $wpdb->insert_id;
        }
        $aulas_in_planning = $wpdb->get_results("SELECT * FROM wp_scae_aulas_planejamento WHERE planning_id = $planning_id");
        $aulas_ids = array();
        foreach($aulas_in_planning as $aula){
            $aulas_ids[] = $aula->aula_id;
        }
        $aulas_ids = implode(',', $aulas_ids);
        ?>
            <form action="" method="post">
            <div>[aulas_disciplina marks="<?php echo $aulas_ids ?>" disciplina="<?php echo $disciplina?>"]</div>
            <div><button type="submit" class="btn btn-primary" name="submitPlanejamento">Salvar Planejamento</button></div>
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

            $total_horas_submit = 0;
            foreach($marked_rows as $row){
                $total_horas_submit += intval(explode('/', $row)[1]);
            }
            $disciplina_s = $wpdb->get_row("SELECT * FROM wp_scae_disciplinas WHERE disc_id = $disciplina");
            if ($total_horas_submit > $disciplina_s->disc_total_hours) {
                echo "<div class='alert alert-danger'>O total de horas nas aulas selecionadas é maior que o total de horas da disciplina.</div>";
            }else{
                foreach($marked_rows as $row){
                    $aula_id_s = intval(explode('/', $row)[0]);
                    //check if theres already a row with this planning_id and aula_id
                    $aula_in_planning = $wpdb->get_row("SELECT * FROM wp_scae_aulas_planejamento WHERE planning_id = $planning_id AND aula_id = $aula_id_s");
                    if(!$aula_in_planning){
                        $wpdb->insert('wp_scae_aulas_planejamento', array(
                            'planning_id' => $planning_id,
                            'aula_id' => $aula_id_s
                        ));
                    }
                }
                wp_safe_redirect(home_url(''));
            }
        }
    }
    return ob_get_clean();
}
add_shortcode('create_planning', 'create_planning_form');
?>