<?php
//DB CONFIG
define('SCAE_PREFIX', $wpdb->prefix . 'scae_');
define('SCAE_TABLE_ATIVIDADES', SCAE_PREFIX . 'atividades');
define('SCAE_TABLE_ATIVIDADESMETA', SCAE_PREFIX . 'atividademeta');
define('SCAE_TABLE_SIMULADORES', SCAE_PREFIX . 'simuladores');
define('SCAE_TABLE_SIMULADORESMETA', SCAE_PREFIX . 'simuladormeta');
define('SCAE_TABLE_DISCIPLINAS', SCAE_PREFIX . 'disciplinas');
define('SCAE_TABLE_ATIVIDADES_DISCIPLINA_SIMULADOR', SCAE_PREFIX . 'ads');
define('SCAE_TABLE_PLANEJAMENTOS', SCAE_PREFIX . 'planejamentos');
define('SCAE_TABLE_AULAS_PLANEJAMENTO', SCAE_PREFIX . 'aulas_planejamento');

//PATHS
define('SCAE_PATH', plugin_dir_path(__FILE__));
define('SCAE_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
define('SCAE_CSS', SCAE_ASSETS . 'css/');
define('SCAE_JS', SCAE_ASSETS . 'js/');
define('SCAE_IMG', SCAE_ASSETS . 'img/');
define('SCAE_TEMPLATES', SCAE_PATH . 'templates/');
define('SCAE_INCLUDES', SCAE_PATH . 'includes/');
define('SCAE_PAGES', SCAE_INCLUDES . 'pages/');
define('SCAE_MODULES', SCAE_INCLUDES . 'modules/');

//SHORTCODES
define('SCAE_ADD_SIMULADOR_SHORTCODE', 'add_simulador');
define('SCAE_ADD_DISCIPLINA_SHORTCODE', 'add_disciplina');
define('SCAE_ADD_ATIVIDADE_SHORTCODE', 'add_atividade');
define('SCAE_ADD_PLANEJAMENTO_SHORTCODE', 'add_planejamento');

define('SCAE_LISTAR_SIMULADORES_SHORTCODE', 'listar_simuladores');
define('SCAE_LISTAR_DISCIPLINAS_SHORTCODE', 'listar_disciplinas');
define('SCAE_LISTAR_ATIVIDADES_SHORTCODE', 'listar_atividades');
define('SCAE_LISTAR_PLANEJAMENTOS_SHORTCODE', 'listar_planejamentos');
define('SCAE_LISTAR_ATIVIDADES_SIMULADOR_SHORTCODE', 'listar_atividades_simulador');
define('SCAE_LISTAR_ATIVIDADES_DISCIPLINA_SHORTCODE', 'listar_atividades_disciplina');
define('SCAE_LISTAR_MEUS_SIMULADORES_SHORTCODE', 'listar_meus_simuladores');
define('SCAE_LISTAR_MEUS_PLANEJAMENTOS_SHORTCODE', 'listar_meus_planejamentos');

define('SCAE_EDITAR_SIMULADOR_SHORTCODE', 'editar_simulador');

define('SCAE_USER_PROFILE_SHORTCODE', 'user_profile');
define('SCAE_HOMEPAGE_SHORTCODE', 'homepage');

//PAGES
define('SCAE_CRIAR_SIMULADOR_PAGE', 'Cadastrar Simulador');
define('SCAE_CRIAR_SIMULADOR_PAGESLUG', 'add-simulador');
define('SCAE_CRIAR_DISCIPLINA_PAGE', 'Cadastrar Disciplina');
define('SCAE_CRIAR_DISCIPLINA_PAGESLUG', 'add-disciplina');
define('SCAE_CRIAR_ATIVIDADE_PAGE', 'Cadastrar Atividade');
define('SCAE_CRIAR_ATIVIDADE_PAGESLUG', 'add-atividade');
define('SCAE_CRIAR_PLANEJAMENTO_PAGE', 'Cadastrar Planejamento');
define('SCAE_CRIAR_PLANEJAMENTO_PAGESLUG', 'add-planejamento');

define('SCAE_LISTAR_SIMULADORES_PAGE', 'Simuladores');
define('SCAE_LISTAR_SIMULADORES_PAGESLUG', 'simuladores');
define('SCAE_LISTAR_DISCIPLINAS_PAGE', 'Disciplinas');
define('SCAE_LISTAR_DISCIPLINAS_PAGESLUG', 'disciplinas');
define('SCAE_LISTAR_PLANEJAMENTOS_PAGE', 'Planejamentos');
define('SCAE_LISTAR_PLANEJAMENTOS_PAGESLUG', 'planejamentos');
define('SCAE_LISTAR_ATIVIDADES_SIMULADOR_PAGE', 'Atividades do Simulador');
define('SCAE_LISTAR_ATIVIDADES_SIMULADOR_PAGESLUG', 'atividades-simulador');
define('SCAE_LISTAR_ATIVIDADES_DISCIPLINA_PAGE', 'Atividades da Disciplina');
define('SCAE_LISTAR_ATIVIDADES_DISCIPLINA_PAGESLUG', 'atividades-disciplina');
define('SCAE_LISTAR_MEUS_SIMULADORES_PAGE', 'Meus Simuladores');
define('SCAE_LISTAR_MEUS_SIMULADORES_PAGESLUG', 'meus-simuladores');
define('SCAE_EDITAR_SIMULADOR_PAGE', 'Editar Simulador');
define('SCAE_EDITAR_SIMULADOR_PAGESLUG', 'editar-simulador');

define('SCAE_USER_PROFILE_PAGE', 'Perfil');
define('SCAE_USER_PROFILE_PAGESLUG', 'perfil');
define('SCAE_HOMEPAGE_PAGE', 'Home');
define('SCAE_HOMEPAGE_PAGESLUG', 'home');


?>