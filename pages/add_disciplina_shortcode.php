<?php
function disciplina_form_page(){
    ?>
    <div class="wrap">
        <form method="post" action="">
            <div>
                <label for="disc_name">Nome da Disciplina</label>
                <input type="text" name="disc_name" id="disc_name" required>
            </div>
            <div>
                <label for="disc_abrev">Abreviação</label>
                <input type="text" name="disc_abrev" id="disc_abrev" required>
            </div>
            <div>
                <label for="disc_desc">Descrição</label>
                <textarea name="disc_desc" id="disc_desc" cols="100" rows="5" required></textarea>
            </div>
            <div>
                <label for="disc_tier">Nivel da Disciplina</label>
                <input type="text" name="disc_tier" id="disc_tier" required></input>
            </div>
            <div>
                <label for="disc_total_hours">Total de Horas</label>
                <input type="text" name="disc_total_hours" id="disc_total_hours" required></input>
            </div>
            <div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Submit">
            </div>
        </form>
    </div>
    <?php
}

function disciplinas_shortcode(){
    ob_start();
    disciplina_form_page();
    return ob_get_clean();
}

add_shortcode('disciplina-form', 'disciplinas_shortcode');

function save_disciplina_to_db(){
    if(isset($_POST['disc_name']) && isset($_POST['disc_abrev']) && isset($_POST['disc_desc']) && isset($_POST['disc_tier']) && isset($_POST['disc_total_hours'])){
        global $wpdb;
        $table_name = $wpdb->prefix . 'disciplina';
        $name = $_POST['disc_name'];
        $abrev = $_POST['disc_abrev'];
        $desc = $_POST['disc_desc'];
        $tier = $_POST['disc_tier'];
        $hours = $_POST['disc_total_hours'];
        $wpdb->insert(
            $table_name,
            array(
                'disc_name' => $name, 
                'disc_abrev' => $abrev, 
                'disc_desc' => $desc,
                'disc_tier' => $tier,
                'disc_total_hours' => $hours
            ) 
        );
    }
}

add_action('init', 'save_disciplina_to_db');