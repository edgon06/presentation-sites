<?php
/**
 * Plugin Name: Sitios para presentaciones
 * Plugin URI: 
 * Description: Gestionar venta de puestos en ubicaciones variadas para un grupo dedicado a hacer presentaciones presenciales.
 * Version: 0.1
 * Text Domain: tbare-wordpress-plugin
 * Author: Edwin González
 * Author URI: https://github.com/edgon06
 */


 function prefix_create_custom_post_type() {

$labels = array(
    'name' => 'Ubicaciones',
    'singular_name' => 'Ubicación'
);

$supports = array(
    'title',
    'editor',
    'excerpt',
    'author',
    'thumbnail',
    'revisions',
    'custom-fields'
);

    $args = array(
        'labels'              => $labels,
        'description'         => 'Post type post product', // Description
        'supports'            => $supports,
        'taxonomies'          => array( 'category', 'post_tag' ), // Allowed taxonomies
        'hierarchical'        => false, // Allows hierarchical categorization, if set to false, the Custom Post Type will behave like Post, else it will behave like Page
        'public'              => true,  // Makes the post type public
        'show_ui'             => true,  // Displays an interface for this post type
        'show_in_menu'        => true,  // Displays in the Admin Menu (the left panel)
        'show_in_nav_menus'   => true,  // Displays in Appearance -> Menus
        'show_in_admin_bar'   => true,  // Displays in the black admin bar
        'menu_position'       => 5,     // The position number in the left menu
        'menu_icon'           => true,  // The URL for the icon used for this post type
        'can_export'          => true,  // Allows content export using Tools -> Export
        'has_archive'         => true,  // Enables post type archive (by month, date, or year)
        'exclude_from_search' => false, // Excludes posts of this type in the front-end search result page if set to true, include them if set to false
        'publicly_queryable'  => true,  // Allows queries to be performed on the front-end part if set to true
        'capability_type'     => 'post' // Allows read, edit, delete like “Post”
    );
    register_post_type( 'ubicacion' , $args );
}
add_action( 'init', 'prefix_create_custom_post_type' );


// crear tablas en bd de wordpress
require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
function create_tables(){
    global $wpdb;

    $tablaSalas = $wpdb->prefix . "salas";
    $tablaAsientos = $wpdb->prefix . "asientos";
    $tablaEventos = $wpdb->prefix . "eventos";

  
    $createdTables = dbDelta(
        " CREATE TABLE $tablaSalas (
            ID BIGINT(10) unsigned NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(60) NOT NULL DEFAULT 'Sala',
            ubicacion VARCHAR(100),
            PRIMARY KEY (ID)
            )CHARACTER SET utf8 COLLATE utf8_general_ci;

            CREATE TABLE $tablaAsientos (
            ID BIGINT(10) unsigned NOT NULL AUTO_INCREMENT,
            cod_ref VARCHAR(10),
            sala BIGINT(10),
            estado VARCHAR(15) DEFAULT 'disponible',
            usuario VARCHAR(50),
            PRIMARY KEY (ID)
            )CHARACTER SET utf8 COLLATE utf8_general_ci;
            
            CREATE TABLE $tablaEventos (
            ID BIGINT(10) unsigned NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(60) NOT NULL DEFAULT 'Evento',
            sala BIGINT(10),
            fecha_inicio DATE,
            fecha_final DATE,
            PRIMARY KEY (ID)            
            )CHARACTER SET utf8 COLLATE utf8_general_ci;
        "
    );
}
register_activation_hook( __FILE__, 'create_tables');

//Eliminar tablas una vez desactivado el plugin
function delete_tables(){
    global $wpdb;

    $tablaSalas = $wpdb->prefix . "salas";
    $tablaAsientos = $wpdb->prefix . "asientos";
    $tablaEventos = $wpdb->prefix . "eventos";

    dbDelta("DROP TABLE $tablaSalas;");
    dbDelta("DROP TABLE $tablaAsientos;");  
    dbDelta("DROP TABLE $tablaEventos;");
        
}
register_deactivation_hook(__FILE__, 'delete_tables');

// Crear página de administración en escritorio de Wordpress
function create_admin_menu(){
$pageTitle = "Plugin control panel";
$menuTitle = "Presentaciones";
$capability = "administrator";
$menuSlug = "presentaciones-admin";
$function = "admin_menu_content";
$icon = "dashicons-admin-multisite"; // From https://developer.wordpress.org/resource/dashicons/#admin-multisite
$position = 9;

    add_menu_page(
       $pageTitle,//__($pageTitle, 'my-textdomain'),
       $menuTitle,//__($menuTitle, 'my-textdomain'),
       $capability,
       $menuSlug,
       $function,
       $icon,
       $position 
    );    
}
add_action('admin_menu', 'create_admin_menu');

function admin_menu_content(){

    ?>
			<h1>
				<?php esc_html_e( 'Administración de presentaciones.', 'my-plugin-textdomain' ); ?>
			</h1>
		<?php

    global $wpdb;
    $tablaSalas = $wpdb->prefix . "salas";
    $tablaAsientos = $wpdb->prefix . "asientos";
    $tablaEventos = $wpdb->prefix . "eventos";

    $salas = $wpdb->get_results("SELECT * FROM `$tablaSalas`");
    $result = '';
    // 
    foreach ($salas as $sala) {
        $result .= '<tr>
            <td>'.$sala->ID.'</td>
            <td>'.$sala->nombre.'</td>
            <td>'.$sala->ubicacion.'</td>
        </tr>';
    }

    $result .= '<br>';
    $asientos = $wpdb->get_results("SELECT * FROM `$tablaAsientos`");
    // 
    foreach ($asientos as $asiento) {
        $result .= '<tr>
            <td>'.$asiento->ID.'</td>
            <td>'.$asiento->sala.'</td>
            <td>'.$asiento->estado.'</td>
        </tr>';
    }

    $result .= '<br>';
    $eventos = $wpdb->get_results("SELECT * FROM `$tablaEventos`");
    // 
    foreach ($eventos as $evento) {
        $result .= '<tr>
            <td>'.$evento->ID.'</td>
            <td>'.$evento->nombre.'</td>
            <td>'.$evento->sala.'</td>
        </tr>';
    }
    
    echo $result;
}

# TODO: CREAR TABLA PARA ALMACENAR DATOS DE COMPRAS 
# CREAR INTERFAZ PARA COMPRAR ASIENTOS,
# CREAR INTERFAZ PARA CREAR NUEVAS SALAS Y EVENTOS
#   - Y CONVERTIRLOS EN CUSTOM TYPES POSTS AUTOMÁTICAMENTE
# ALTERAR TABLAS PARA AÑADIR LLAVES FORÁNEAS
# ELIMINAR TABLAS AL DESACTIVAR PLUGIN