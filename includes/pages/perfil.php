<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCAE_User_Profile {

	public function __construct() {
		add_shortcode( 'user_profile', array( $this, 'scae_user_profile_shortcode' ) );
	}

	public function scae_get_field( $field_name ) {
		return get_user_meta( get_current_user_id(), $field_name, true );
	}

	public function scae_user_profile_shortcode() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		global $wpdb;
		ob_start();
		$user = get_userdata( get_current_user_id() );

		if ( isset( $_POST['submitProfile'] ) ) {
			$first_name_input  = $_POST['first_name'];
			$last_name_input   = $_POST['last_name'];
			$nickname_input    = $_POST['nickname'];
			$display_name_input = $_POST['display_name'];

			update_user_meta( get_current_user_id(), 'first_name', $first_name_input );
			update_user_meta( get_current_user_id(), 'last_name', $last_name_input );
			update_user_meta( get_current_user_id(), 'nickname', $nickname_input );
			wp_update_user(
				array(
					'ID'           => get_current_user_id(),
					'display_name' => $display_name_input,
				)
			);

			if ( $_POST['password'] != '' && $_POST['password'] == $_POST['password_confirm'] && strlen( $_POST['password'] ) >= 8 ) {
				wp_set_password( $_POST['password'], get_current_user_id() );
			}

			$user_email = $user->user_email;

			if ( $_POST['email'] != '' && is_email( $_POST['email'] ) && $_POST['email'] != $user_email ) {
				wp_update_user(
					array(
						'ID'         => get_current_user_id(),
						'user_email' => $_POST['email'],
					)
				);
			}
		}
        $nickname = trim( $this->scae_get_field( 'nickname' ) );
        $first_name = trim( $this->scae_get_field( 'first_name' ) );
        $last_name = trim( $this->scae_get_field( 'last_name' ) );
        $display_name = $user->display_name;
        $first_last_name = $first_name . ' ' . $last_name;
        $last_first_name = $last_name . ' ' . $first_name;

        ?>
        <form action="<?php echo home_url( '/perfil' ) ?>" class="form_profile" method="post">
            <section class="name_section">
                <h1 class="name_header">Informações Pessoais</h1>
                <label class="profile_label" for="first_name">Nome:</label><br>
                <input class="profile_input" type="text" name="first_name" value="<?php echo $this->scae_get_field( 'first_name' ) ?>"><br>
                <label class="profile_label" for="last_name">Sobrenome:</label><br>
                <input class="profile_input" type="text" name="last_name" value="<?php echo $last_name ?>"><br>
                <label class="profile_label" for="nickname">Nickname:</label><br>
                <input class="profile_input" type="text" name="nickname" value="<?php echo $this->scae_get_field( 'nickname' ) ?>" required><br>
                <label class="profile_label" for="display_name">Nome visível no site:</label><br>
                <select class="profile_select" type="text" name="display_name">
                    <?php if ( $nickname != '' ) : ?>
                        <option value="<?php echo $nickname ?>" <?php echo $display_name == $nickname ? 'selected' : '' ?>><?php echo $nickname ?></option>
                    <?php endif; ?>
                    <?php if ( $this->scae_get_field( 'username' ) != '' ) : ?>
                        <option value="<?php echo $this->scae_get_field( 'username' ) ?>" <?php echo $display_name == $this->scae_get_field( 'username' ) ? 'selected' : '' ?>><?php echo $this->scae_get_field( 'username' ) ?></option>
                    <?php endif; ?>
                    <?php if ( $first_last_name != '' ) : ?>
                        <option value="<?php echo $first_last_name ?>" <?php echo $display_name == $first_last_name ? 'selected' : '' ?>><?php echo $first_last_name ?></option>
                    <?php endif; ?>
                    <?php if ( $last_first_name != '' ) : ?>
                        <option value="<?php echo $last_first_name ?>" <?php echo $display_name == $last_first_name ? 'selected' : '' ?>><?php echo $last_first_name ?></option>
                    <?php endif; ?>
                    </select>
            </section>
            <section class="password_section">
                <h1 class="password_header">Alterar senha</h1>
                <label class="profile_label" for="password">Nova senha:</label><br>
                <input class="profile_input" type="password" name="password"><br>
                <label class="profile_label" for="password_confirm">Confirme a nova senha:</label><br>
                <input class="profile_input" type="password" name="password_confirm"><br>
            </section>
            <section class="email_section">
                <h1 class="email_header">Informações de contato</h1>
                <label class="profile_label" for="email">Email:</label><br>
                <input class="profile_input" type="email" name="email" value="<?php echo $user->user_email ?>"><br>
            </section>
            <input class="profile_submit" type="submit" name="submitProfile" value="Atualizar Perfil">
            </form>
            <style>
                .form_profile {
                width: 80%;
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                }

                .name_header, .contact_header {
                font-size: 1.5em;
                color: #000000;
                margin: 0;
                margin-bottom: 15px;
                margin-top: 25px;
                }

                .profile_label {
                font-size: 1.2em;
                color: #000000;
                margin-bottom: 15px;
                }

                .profile_input, .profile_select {
                font-size: 1.2em;
                padding: 10px;
                border: 1px solid #09ABEC;
                border-radius: 5px;
                margin-bottom: 15px;
                width: 100%;
                }

                .subheader {
                font-size: 0.8em;
                color: #777777;
                margin-bottom: 20px;
                }
                input[type="submit"] {
                font-size: 1.2em;
                color: white;
                background-color: #09ABEC;
                border: none;
                border-radius: 5px;
                padding: 10px 20px;
                cursor: pointer;
                }

                input[type="submit"]:hover {
                background-color: #71CFF5;
                }
            </style>
            <?php
            return ob_get_clean();
        }
}

new SCAE_User_Profile();



?>