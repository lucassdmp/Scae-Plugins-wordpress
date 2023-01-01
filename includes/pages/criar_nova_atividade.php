<?php
class SCAE_Add_Activities_Form
{
    public function __construct()
    {
        add_shortcode(SCAE_ADD_ATIVIDADE_SHORTCODE, array($this, "atividades_shortcode"));
    }

    public function atividades_form_page()
    {
        global $wpdb;

        if (
            isset($_POST['submitAtividade']) &&
            isset($_POST["aula_sim_id"]) &&
            isset($_POST["aula_exerc"]) &&
            isset($_POST["aula_horas"]) &&
            isset($_POST["aula_type"])
        ) {
            $table_name = SCAE_TABLE_ATIVIDADES;
            $ads_table = SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR;
            $table_meta = SCAE_TABLE_ATIVIDADESMETA;

            $data = [
                "nome" => sanitize_text_field($_POST["aula_exerc"]),
                "duracao" => sanitize_text_field($_POST["aula_horas"]),
                "categoria" => sanitize_text_field($_POST["aula_type"]),
                "descricao" => sanitize_text_field($_POST["aula_desc"]),
            ];

            $result = $wpdb->insert($table_name, $data);
            $aula_id = $wpdb->insert_id;

            $aula_sim_id = sanitize_text_field($_POST["aula_sim_id"]);
            $aula_disc_id = sanitize_text_field($_POST["aula_disc_id"]);
            $wpdb->insert($ads_table, ["aula_id" => $aula_id, "sim_id" => $aula_sim_id, "disc_id" => $aula_disc_id]);

            if (isset($_POST['link_type_select']) && isset($_POST['link_content'])) {
                $links_type = $_POST['link_type_select'];
                $links_content = $_POST['link_content'];
                for ($i = 0; $i < count($links_type); $i++) {
                    if ($links_type[$i] && $links_content[$i]) {
                        $wpdb->insert(
                            $table_meta,
                            [
                                "id" => $aula_id,
                                "meta_tag" => $links_type[$i],
                                "meta_content" => $links_content[$i]
                            ]
                        );
                    }
                }
            }
            wp_safe_redirect(home_url());
            exit;
            
        }
?>
        <div class="wrap">
            <form class="whole_form" method="post">
                <label class="whole_form_label" for="aula_sim_id">Simulador</label>
                <select class="whole_form_select" name="aula_sim_id" id="aula_sim_id">
                    <?php
                    $table_name = SCAE_TABLE_SIMULADORES;
                    $simuladores = $wpdb->get_results("SELECT * FROM $table_name");
                    foreach ($simuladores as $simulador) {
                        echo "<option value='$simulador->id'>$simulador->nome</option>";
                    }
                    ?>
                </select>
                <label class="whole_form_label" for="aula_disc_id">Disciplina</label>
                <select class="whole_form_select" name="aula_disc_id" id="aula_disc_id">
                    <?php
                    $table_name = SCAE_TABLE_DISCIPLINAS;
                    $disciplinas = $wpdb->get_results("SELECT * FROM $table_name");
                    foreach ($disciplinas as $disciplina) {
                        echo "<option value='$disciplina->id'>$disciplina->nome</option>";
                    } ?>
                </select>
                <label class="whole_form_label" for="aula_exerc">Nome da Atividade</label>
                <input class="whole_form_input" type="text" name="aula_exerc" id="aula_exerc" required>
                <label class="whole_form_label" for="aula_horas">Horas</label>
                <input class="whole_form_input" type="text" name="aula_horas" id="aula_horas" required>
                <label class="whole_form_label" for="aula_type">Tipo</label>
                <select class="whole_form_select" name="aula_type" id="aula_type">
                    <option value="Dignosticas">Dignosticas</option>
                    <option value="Formadouras">Formadouras</option>
                    <option value="Exercícios">Exercícios</option>
                    <option value="Resoluções">Resoluções</option>
                </select>

                <label class="whole_form_label" for="aula_desc">Descrição</label>
                <textarea class="whole_form_textarea" name="aula_desc" id="aula_desc" cols="100" rows="10"></textarea>

                <div class="whole_form_links"></div>
                <button class="add_link">Adicionar Video/Artigo</button>

                <input class="whole_form_submit" type="submit" name="submitAtividade" value="Adicionar Atividade">
            </form>

        </div>
        <script>
            var linksSection = document.querySelector(".whole_form_links");
            var addlink_btn = document.querySelector(".add_link");
            var linkCount = 0;

            addlink_btn.addEventListener("click", function(e) {
                e.preventDefault();
                linkCount++;
                var link = document.createElement("div");
                link.classList.add("whole_form_link");
                link.innerHTML = `
                    <select class="link_type_select" name="link_type_select[]">
                        <option class="link_option" value="video">Video</option>
                        <option class="link_option" value="artigo">Artigo</option>
                    </select>
                    <input class="link_content" name="link_content[]">
                    <button class="remove_link">Remover</button>
                `;
                link.querySelector(".remove_link").addEventListener("click", removeLink);
                linksSection.appendChild(link);
            });

            function removeLink(e) {
                e.preventDefault();
                var link = e.target.parentNode;
                linksSection.removeChild(link);
            }
        </script>
<?php
    }

    public function atividades_shortcode()
    {
        if (!is_user_logged_in()) {
            wp_safe_redirect(home_url());
            exit();
        }
        ob_start();
        $this->atividades_form_page();
        return ob_get_clean();
    }
}

new SCAE_Add_Activities_Form();
?>