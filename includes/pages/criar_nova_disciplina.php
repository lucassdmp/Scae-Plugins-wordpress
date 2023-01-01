<?php
class SCAE_Disciplinas_Form
{
    public function __construct()
    {
        add_shortcode(SCAE_ADD_DISCIPLINA_SHORTCODE, [$this, "disciplinas_shortcode"]);
    }

    public function disciplina_form_page()
    {
        global $wpdb;
        if (!is_user_logged_in()) {
            wp_safe_redirect(home_url());
            exit();
        }
        
        if(isset($_GET['editar'])){
            $disc_id = intval($_GET['editar']);
            $table_name = SCAE_TABLE_DISCIPLINAS;
            $disciplina = $wpdb->get_results("SELECT * FROM $table_name WHERE id = $disc_id")[0];
        }

        if (isset($_POST['submitdisciplina']) && isset($_POST['disc_name']) && isset($_POST['disc_abrev']) && isset($_POST['disc_desc']) && isset($_POST['disc_tier']) && isset($_POST['disc_total_hours'])) {
            $table_name = SCAE_TABLE_DISCIPLINAS;
            $nome = sanitize_text_field($_POST['disc_name']);
            $abreviacao = sanitize_text_field($_POST['disc_abrev']);
            $descricao = sanitize_textarea_field($_POST['disc_desc']);
            $escolaridade = sanitize_text_field($_POST['disc_tier']);
            $carga_horaria = intval($_POST['disc_total_hours']);

            $disciplina_name = $wpdb->get_results("SELECT * FROM $table_name WHERE nome = '$nome'");
            $disciplina_abrev = $wpdb->get_results("SELECT * FROM $table_name WHERE abreviacao = '$abreviacao'");
            if (!empty($disciplina_name)) {
                echo "Disciplina já existe";
                return;
            }
            if (!empty($disciplina_abrev)) {
                echo "Abreviação já existe";
                return;
            } else {
                $wpdb->insert(
                    $table_name,
                    array(
                        'nome' => $nome,
                        'abreviacao' => $abreviacao,
                        'descricao' => $descricao,
                        'escolaridade' => $escolaridade,
                        'carga_horaria' => $carga_horaria,
                        'user_id' => get_current_user_id()
                    )
                );
            }
            wp_safe_redirect(home_url() . '/disciplinas');
            exit;
        }
?>
        <div class="wrap">
            <form class="whole_form" method="post" action="">
                <label class="whole_form_label" for="disc_name">Nome da Disciplina</label>
                <input class="whole_form_input" type="text" name="disc_name" id="disc_name" required>

                <label class="whole_form_label" for="disc_abrev">Abreviação</label>
                <input class="whole_form_input" type="text" name="disc_abrev" id="disc_abrev" required>

                <label class="whole_form_label" for="disc_desc">Descrição</label>
                <textarea class="whole_form_textarea" name="disc_desc" id="disc_desc" cols="100" rows="5" required></textarea>

                <label class="whole_form_label" for="disc_tier">Nivel da Disciplina</label>
                <select name="disc_tier" id="disc_tier" class="whole_form_select">
                    <option value="Fundamental" selected class="whole_form_option">Fundametal</option>
                    <option value="Médio" class="whole_form_option">Médio</option>
                    <option value="Superior" class="whole_form_option">Superior</option>
                </select>

                <label class="whole_form_label" for="disc_total_hours">Total de Horas</label>
                <input class="whole_form_input" type="text" name="disc_total_hours" id="disc_total_hours" required></input>

                <input class="whole_form_submit" type="submit" name="submitdisciplina" id="submitdisciplina" value="Criar Disciplina">
            </form>
        </div>
<?php
    }

    public function disciplinas_shortcode()
    {
        ob_start();
        $this->disciplina_form_page();
        return ob_get_clean();
    }
}

new SCAE_Disciplinas_Form();
?>