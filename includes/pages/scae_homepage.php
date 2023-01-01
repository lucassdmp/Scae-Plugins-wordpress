<?php

function scae_createhomepage(){
    ob_start();
    ?>
    <div class="homepage">
        <div class="homepage_title">
            <h1>SCAE</h1>
            <h2>Uso de Simuladores computacionais como ferramenta de apoio.</h2>
        </div>
        <div class="homepage_content">
            <div class="homepage_content_title">
                <h3>Seja bem-vindo ao SCAE!</h3>
            </div>
            <div class="homepage_content_text">
                <p>Este é um sistema de controle de aulas e estudos com o uso de aplicativo, que tem como objetivo auxiliar professores a organizar planos de aulas utilizando simuladores, afim de criar planos de aula com guias para ensino.</p>
            </div>
            <div class="homepage_content_text">
                <p>Para começar, você deve criar um planejamento de aulas e estudos, para isso, basta clicar no botão abaixo.</p>
            </div>
            <div class="homepage_content_button">
                <a href="<?php echo home_url('/criar-planejamento') ?>">Criar Planejamento</a>
            </div>
        </div>
    </div>
    <style>
        .homepage {
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        .homepage_title {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        }

        .homepage_title h1 {
        font-size: 2em;
        color: #000000;
        margin: 0;
        }

        .homepage_title h2 {
        font-size: 1.5em;
        color: #000000;
        margin: 0;
        }

        .homepage_content {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 80%;
        }

        .homepage_content_title {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        }

        .homepage_content_title h3 {
        font-size: 1.5em;
        color: #000000;
        margin: 0;
        }

        .homepage_content_text {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        }

        .homepage_content_text p {
        font-size: 1.2em;
        color: #000000;
        margin: 0;
        }

        .homepage_content_button {
        display: flex;
        flex-direction: column;
        align-items: center;
        }

        .homepage_content_button a {
        font-size: 1.2em;
        color: white;
        background-color: #09ABEC;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        text-decoration: none;
        }

        .homepage_content_button a:hover {
        color: #71CFF5;
        background-color: #09ABEC;
        text-decoration: underline;
        }
    </style>
    <?php
    return ob_get_clean();
}

add_shortcode('homepage', 'scae_createhomepage');

?>