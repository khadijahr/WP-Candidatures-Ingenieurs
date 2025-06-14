<?php
// Gestion de la soumission du formulaire
function ing_candidature_process_form() {
    // Vérifier le nonce
    if (!isset($_POST['_wpnonce'])) {
        wp_die('Security check failed');
    }
    
    if (!wp_verify_nonce($_POST['_wpnonce'], 'ing_candidature_nonce')) {
        wp_die('Security check failed');
    }
    
    // Vérifier les champs obligatoires
    $required_fields = array(
        'lastName', 'firstName', 'telephone', 'email', 'birthDate', 'city', 
        'bacYear', 'highSchool', 'bacType', 'entryYear'
    );
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_die('Please fill all required fields');
        }
    }
    
    // Vérifier les champs conditionnels
    if ($_POST['entryYear'] >= 2 && empty($_POST['postBacPath'])) {
        wp_die('Please fill your post-bac path');
    }
    
    if ($_POST['entryYear'] >= 3) {
        if (!isset($_POST['hasDiploma'])) {
            wp_die('Please specify if you have a diploma');
        }
        
        if ($_POST['hasDiploma'] == 1 && empty($_POST['diplomaType'])) {
            wp_die('Please specify your diploma type');
        }
    }
    
    // Nettoyer les données
    $data = array(
        'nom' => sanitize_text_field($_POST['lastName']),
        'prenom' => sanitize_text_field($_POST['firstName']),
        'telephone' => sanitize_text_field($_POST['telephone']),
        'email' => sanitize_text_field($_POST['email']),
        'date_naissance' => sanitize_text_field($_POST['birthDate']),
        'ville' => sanitize_text_field($_POST['city']),
        'bac_annee' => intval($_POST['bacYear']),
        'lycee' => sanitize_text_field($_POST['highSchool']),
        'bac_type' => sanitize_text_field($_POST['bacType']),
        'annee_integration' => sanitize_text_field($_POST['entryYear']),
        'parcours_postbac' => isset($_POST['postBacPath']) ? sanitize_text_field($_POST['postBacPath']) : '',
        'a_diplome' => ($_POST['entryYear'] >= 3) ? intval($_POST['hasDiploma']) : NULL,
        'type_diplome' => ($_POST['entryYear'] >= 3 && $_POST['hasDiploma'] == 1) ? sanitize_text_field($_POST['diplomaType']) : NULL
    );
    
    // Enregistrer en base de données
    global $wpdb;
    $table_name = $wpdb->prefix . 'ingenieurs_candidatures';
    $columns = $wpdb->get_col("DESCRIBE $table_name", 0);
    
    if (!in_array('telephone', $columns)) {
        wp_send_json_error(array(
            'message' => 'La colonne telephone est manquante. Veuillez réactiver le plugin.'
        ));
    }
    
    $inserted = $wpdb->insert(
        $table_name,
        $data,        
        array(
            '%s', // nom
            '%s', // prenom
            '%s', // telephone
            '%s', // email
            '%s', // date_naissance
            '%s', // ville
            '%d', // bac_annee
            '%s', // lycee
            '%s', // bac_type
            '%s', // annee_integration
            '%s', // parcours_postbac
            '%d', // a_diplome
            '%s'  // type_diplome
        )
    );
    
    if ($inserted === false) {
        error_log('Erreur DB: ' . $wpdb->last_error);
        error_log('Requête: ' . $wpdb->last_query);
        
        wp_send_json_error(array(
            'message' => 'Erreur technique. Veuillez réessayer plus tard.'
        ));
    }

    if ($inserted) {
        // Envoyer un email de confirmation
        //$to = get_option('admin_email');
        $to = 'kharmouche95@gmail.com';
        $subject = 'Nouvelle candidature - ' . $data['prenom'] . ' ' . $data['nom'];
        $message = "Une nouvelle candidature a été soumise:\n\n";
        
        foreach ($data as $key => $value) {
            $message .= ucfirst(str_replace('_', ' ', $key)) . ": $value\n";
        }
        
        wp_mail($to, $subject, $message);
        
        // Réponse JSON pour AJAX
        wp_send_json_success(array(
            'message' => 'Votre candidature a été soumise avec succès !'
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'Une erreur est survenue lors de l\'enregistrement.'
        ));
    }
}
add_action('wp_ajax_ing_candidature_submit', 'ing_candidature_process_form');
add_action('wp_ajax_nopriv_ing_candidature_submit', 'ing_candidature_process_form');