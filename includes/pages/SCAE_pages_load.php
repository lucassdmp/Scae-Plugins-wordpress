<?php
// Path: includes\pages\SCAE_pages_load.php

//required all pages in this folder
require_once(SCAE_PAGES . 'criar_nova_atividade.php');
require_once(SCAE_PAGES . 'criar_nova_disciplina.php');
require_once(SCAE_PAGES . 'criar_novo_simulador.php');
require_once(SCAE_PAGES . 'criar_novo_planejamento.php');

require_once(SCAE_PAGES . 'listar_disciplinas.php');
require_once(SCAE_PAGES . 'listar_simuladores.php');
require_once(SCAE_PAGES . 'listar_planejamentos.php');
require_once(SCAE_PAGES . 'listar_atividades_disciplina.php');
require_once(SCAE_PAGES . 'listar_atividades_simulador.php');
require_once(SCAE_PAGES . 'listar_meus_simuladores.php');
require_once(SCAE_PAGES . 'listar_meus_planejamentos.php');
require_once(SCAE_PAGES . 'editar_simulador.php');

require_once(SCAE_PAGES . 'perfil.php');
require_once(SCAE_PAGES . 'scae_homepage.php');

?>