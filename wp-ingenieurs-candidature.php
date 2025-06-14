<?php
/*
Plugin Name: WP Candidatures École d'Ingénieurs
Description: Un système complet de formulaire de candidature en plusieurs étapes avec gestion des soumissions et notifications pour les écoles d'ingénieurs.
Version: 1.0.0
Author: Khadija Har
Author URI: https://profiles.wordpress.org/khadijahr1/
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: ingenieurs-candidatures
*/

// Sécurité : empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définition des constantes
define('ING_CANDIDATURE_VERSION', '1.0');
define('ING_CANDIDATURE_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ING_CANDIDATURE_PLUGIN_URL', plugin_dir_url(__FILE__));

// Inclure les fichiers nécessaires
require_once ING_CANDIDATURE_PLUGIN_DIR . 'includes/shortcode.php';
require_once ING_CANDIDATURE_PLUGIN_DIR . 'includes/form-handler.php';

// Charger les assets (CSS/JS)
function ing_candidature_enqueue_assets() {
    // CSS
    wp_enqueue_style(
        'ing-candidature-tailwind', 
        'https://cdn.tailwindcss.com'
    );
    
    wp_enqueue_style(
        'ing-candidature-fontawesome', 
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
    );
    
    wp_enqueue_style(
        'ing-candidature-style', 
        ING_CANDIDATURE_PLUGIN_URL . 'includes/assets/css/c-wp-stylesss.css',
        array(),
        ING_CANDIDATURE_VERSION
    );
    
    // JS
    wp_enqueue_script('jquery');
    wp_enqueue_script(
        'ing-candidature-script', 
        ING_CANDIDATURE_PLUGIN_URL . 'includes/assets/js/canda-wp-script.js',
        array('jquery'),
        ING_CANDIDATURE_VERSION,
        true
    );    
        
    // Localisation pour AJAX
    wp_localize_script(
        'ing-candidature-script',
        'ing_candidature_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ing_candidature_nonce')
        )
    );
}
add_action('wp_enqueue_scripts', 'ing_candidature_enqueue_assets');

// Fonction d'activation
function ing_candidature_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ingenieurs_candidatures';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nom varchar(100) NOT NULL,
        prenom varchar(100) NOT NULL,
        telephone varchar(20) NOT NULL,
        email varchar(255) NOT NULL,
        date_naissance date NOT NULL,
        ville varchar(100) NOT NULL,
        bac_annee smallint(4) NOT NULL,
        lycee varchar(255) NOT NULL,
        bac_type varchar(50) NOT NULL,
        annee_integration varchar(50) NOT NULL,
        parcours_postbac text,
        a_diplome tinyint(1),
        type_diplome varchar(255),
        date_soumission datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    
    // Ajouter les colonnes si elles n'existent pas
    $columns_to_add = [
        'telephone' => "ALTER TABLE $table_name ADD telephone varchar(20) NOT NULL AFTER prenom",
        'email' => "ALTER TABLE $table_name ADD email varchar(255) NOT NULL AFTER telephone"
    ];
    
    foreach ($columns_to_add as $column => $query) {
        $column_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = %s AND COLUMN_NAME = %s",
            $table_name,
            $column
        ));
        
        if (!$column_exists) {
            $wpdb->query($query);
        }
    }
}
register_activation_hook(__FILE__, 'ing_candidature_activate');


// Ajouter un menu admin
function ing_candidature_admin_menu() {
    add_menu_page(
        'Candidatures Ingénieurs', 
        'Candidatures', 
        'manage_options', 
        'ingenieurs-candidatures', 
        'ing_candidature_admin_page',
        'dashicons-clipboard',
        30
    );
}
add_action('admin_menu', 'ing_candidature_admin_menu');


// Page d'administration
function ing_candidature_admin_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ingenieurs_candidatures';
    $candidatures = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date_soumission DESC");
    
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'view' && isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $candidature = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
            
            if ($candidature) {
                ?>
                <div class="wrap">
                    <h1>Détails de la candidature #<?php echo $candidature->id; ?></h1>
                    <a href="<?php echo admin_url('admin.php?page=ingenieurs-candidatures'); ?>" class="button">Retour</a>
                    
                    <div class="card" style="margin-top: 20px; max-width: 600px;">
                        <h2>Informations personnelles</h2>
                        <p><strong>Nom :</strong> <?php echo esc_html($candidature->nom); ?></p>
                        <p><strong>Prénom :</strong> <?php echo esc_html($candidature->prenom); ?></p>
                        <p><strong>Date de naissance :</strong> <?php echo date_i18n('j F Y', strtotime($candidature->date_naissance)); ?></p>
                        <p><strong>Ville :</strong> <?php echo esc_html($candidature->ville); ?></p>
                        <p><strong>Téléphone :</strong> <?php echo esc_html($candidature->telephone); ?></p>
                        <p><strong>Email :</strong> <?php echo esc_html($candidature->email); ?></p>

                        <h2 style="margin-top: 20px;">Parcours académique</h2>
                        <p><strong>Année BAC :</strong> <?php echo esc_html($candidature->bac_annee); ?></p>
                        <p><strong>Lycée :</strong> <?php echo esc_html($candidature->lycee); ?></p>
                        <p><strong>Type de BAC :</strong> <?php echo esc_html($candidature->bac_type); ?></p>
                        <p><strong>Année d'intégration souhaitée :</strong> <?php echo esc_html($candidature->annee_integration); ?></p>
                        
                        <?php if (!empty($candidature->parcours_postbac)) : ?>
                        <p><strong>Parcours post-BAC :</strong> <?php echo esc_html($candidature->parcours_postbac); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($candidature->a_diplome) : ?>
                        <p><strong>Diplôme :</strong> Oui (<?php echo esc_html($candidature->type_diplome); ?>)</p>
                        <?php endif; ?>
                        
                        <p><strong>Date de soumission :</strong> <?php echo date_i18n('j F Y H:i', strtotime($candidature->date_soumission)); ?></p>
                    </div>
                </div>
                <?php
                return;
            }
        }
    }
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Candidatures reçues</h1>
        <hr class="wp-header-end">
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Télépnone</th>
                    <th>Email</th>
                    <th>Année d'intégration</th>
                    <th>Date de soumission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($candidatures as $candidature) : ?>
                <tr>
                    <td><?php echo $candidature->id; ?></td>
                    <td><?php echo esc_html($candidature->nom); ?></td>
                    <td><?php echo esc_html($candidature->prenom); ?></td>
                    <td><?php echo esc_html($candidature->telephone); ?></td>
                    <td><?php echo esc_html($candidature->email); ?></td>
                    <td><?php echo esc_html($candidature->annee_integration); ?></td>
                    <td><?php echo date_i18n('j F Y H:i', strtotime($candidature->date_soumission)); ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=ingenieurs-candidatures&action=view&id=' . $candidature->id); ?>" class="button button-primary">Voir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <form method="post" style="margin-top: 20px;">
        <input type="hidden" name="export_candidatures_csv" value="1" />
        <?php submit_button('Exporter en CSV'); ?>
    </form>

    <?php
}


add_action('admin_init', function () {
    if (isset($_POST['export_candidatures_csv'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'ingenieurs_candidatures';

        $candidatures = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        if (!empty($candidatures)) {
            // En-têtes HTTP pour forcer le téléchargement du fichier
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="candidatures.csv"');

            $output = fopen('php://output', 'w');

            // Écrire la ligne d'en-tête
            fputcsv($output, array_keys($candidatures[0]));

            // Écrire les données
            foreach ($candidatures as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
            exit;
        } else {
            wp_die('Aucune donnée à exporter.');
        }
    }
});
