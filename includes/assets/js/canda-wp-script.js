function nextStep(currentStep) {
    // Validation de l'étape actuelle
    if (currentStep === 1) {
        const requiredFields = document.querySelectorAll('#step1 [required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }
    } else if (currentStep === 2) {
        const entryYear = document.getElementById('entryYear').value;
        if (!entryYear) {
            alert('Veuillez sélectionner une année d\'intégration.');
            return;
        }
        
        const yearValue = parseInt(entryYear);
        if (yearValue >= 2 && !document.getElementById('postBacPath').value) {
            alert('Veuillez indiquer votre parcours post-BAC.');
            return;
        }
        
        if (yearValue >= 3 && !document.querySelector('input[name="hasDiploma"]:checked')) {
            alert('Veuillez indiquer si vous avez un diplôme ou une attestation.');
            return;
        }
        
        if (yearValue >= 3 && document.getElementById('hasDiplomaYes').checked && !document.getElementById('diplomaType').value) {
            alert('Veuillez préciser le type de diplôme obtenu.');
            return;
        }
    }
    
    // Passer à l'étape suivante
    showStep(currentStep + 1);
    
    // Mettre à jour l'aperçu uniquement si on passe à l'étape 3
    if (currentStep + 1 === 3) {
        updateReview();
    }
}

function prevStep(currentStep) {
    showStep(currentStep - 1);
}

function showStep(stepNumber) {
    // Mettre à jour l'indicateur d'étapes
    const indicators = document.querySelectorAll('.step-indicator li');
    indicators.forEach((indicator, index) => {
        indicator.classList.remove('active', 'completed');
        if (index + 1 === stepNumber) {
            indicator.classList.add('active');
        } else if (index + 1 < stepNumber) {
            indicator.classList.add('completed');
        }
    });
    
    // Afficher l'étape correspondante
    document.querySelectorAll('.form-step').forEach(step => {
        step.classList.remove('active');
    });
    document.getElementById('step' + stepNumber).classList.add('active');
}


function updateReview() {
    // Fonction helper pour mettre à jour un champ de manière sécurisée
    function setReviewValue(id, value) {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = value || '-';
        }
    }

    // Informations personnelles
    setReviewValue('reviewLastName', document.getElementById('lastName').value);
    setReviewValue('reviewFirstName', document.getElementById('firstName').value);
    setReviewValue('reviewBirthDate', document.getElementById('birthDate').value);
    setReviewValue('reviewTelephone', document.getElementById('telephone').value);    
    setReviewValue('reviewEmail', document.getElementById('email').value);
    setReviewValue('reviewCity', document.getElementById('city').value);
    setReviewValue('reviewBacYear', document.getElementById('bacYear').value);
    setReviewValue('reviewHighSchool', document.getElementById('highSchool').value);
    
    // Type de BAC
    const bacTypeSelect = document.getElementById('bacType');
    const bacTypeText = bacTypeSelect.options[bacTypeSelect.selectedIndex].text;
    setReviewValue('reviewBacType', bacTypeText);
    
    // Année d'intégration
    const entryYearSelect = document.getElementById('entryYear');
    const entryYearText = entryYearSelect.options[entryYearSelect.selectedIndex].text;
    setReviewValue('reviewEntryYear', entryYearText);
    
    // Parcours post-BAC (conditionnel)
    const postBacPath = document.getElementById('postBacPath').value;
    if (postBacPath) {
        setReviewValue('reviewPostBacPath', postBacPath);
        document.getElementById('reviewPathField').classList.remove('hidden');
    } else {
        document.getElementById('reviewPathField').classList.add('hidden');
    }
    
    // Diplôme (conditionnel)
    const entryYear = parseInt(entryYearSelect.value);
    if (entryYear >= 3) {
        document.getElementById('reviewDiplomaFields').classList.remove('hidden');
        
        const hasDiploma = document.querySelector('input[name="hasDiploma"]:checked');
        if (hasDiploma) {
            const diplomaText = hasDiploma.value === '1' ? 'Oui' : 'Non';
            setReviewValue('reviewHasDiploma', diplomaText);
            
            if (hasDiploma.value === '1') {
                setReviewValue('reviewDiplomaType', document.getElementById('diplomaType').value);
                document.getElementById('reviewDiplomaTypeField').classList.remove('hidden');
            } else {
                document.getElementById('reviewDiplomaTypeField').classList.add('hidden');
            }
        }
    } else {
        document.getElementById('reviewDiplomaFields').classList.add('hidden');
    }
}

jQuery(document).ready(function($) {
	// for bacYear input validation
	$('#bacYear').on('input', function() {
		const currentYear = new Date().getFullYear();
		const minYear = currentYear - 20;
		
		let value = parseInt(this.value, 10);
		
		if (value < minYear) {
			this.value = minYear;
		} else if (value > currentYear) {
			this.value = currentYear;
		}
	});
    // Gestion de l'affichage conditionnel
    $('#entryYear').on('change', function() {
        const value = parseInt(this.value);
        const pathField = $('#pathField');
        const diplomaFields = $('#diplomaFields');
        const diplomaTypeField = $('#diplomaTypeField');
        
        // Afficher le champ parcours pour 2ème année ou plus
        if (value >= 2) {
            pathField.show();
            $('#postBacPath').prop('required', true);
        } else {
            pathField.hide();
            $('#postBacPath').prop('required', false);
        }
        
        // Afficher les champs diplôme pour 3ème ou 4ème année
        if (value >= 3) {
            diplomaFields.show();
            diplomaTypeField.hide();
            $('#diplomaType').prop('required', false);
            $('input[name="hasDiploma"]').prop('required', true);
        } else {
            diplomaFields.hide();
            $('input[name="hasDiploma"]').prop('required', false);
        }
    });

    // Gestion du champ type de diplôme
    $('input[name="hasDiploma"]').on('change', function() {
        if ($('#hasDiplomaYes').is(':checked')) {
            $('#diplomaTypeField').show();
            $('#diplomaType').prop('required', true);
        } else {
            $('#diplomaTypeField').hide();
            $('#diplomaType').prop('required', false);
        }
    });

    // Gestion des boutons suivant/précédent
    $(document).on('click', '.next-step-btn', function() {
        const currentStep = parseInt($('.form-step.active').attr('id').replace('step', ''));
        nextStep(currentStep);
    });

    $(document).on('click', '.prev-step-btn', function() {
        const currentStep = parseInt($('.form-step.active').attr('id').replace('step', ''));
        prevStep(currentStep);
    });

    // Soumission du formulaire via AJAX
    $('#ing-candidature-form').on('submit', function(e) {
        e.preventDefault();
        
        // Validation de l'étape 3
        if (!this.checkValidity()) {
            alert('Veuillez remplir tous les champs obligatoires.');
            return;
        }
        
        // Préparer les données
        const formData = $(this).serialize();
        
        // Envoyer via AJAX
        $.ajax({
            url: ing_candidature_ajax.ajax_url,
            type: 'POST',
            data: formData + '&action=ing_candidature_submit&_wpnonce=' + ing_candidature_ajax.nonce,
            beforeSend: function() {
                // Afficher un indicateur de chargement
                $('#ing-candidature-form button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Envoi en cours...');
            },
            success: function(response) {
                if (response.success) {
                    alert(response.data.message);
                    $('#ing-candidature-form')[0].reset();
                    showStep(1);
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert('Une erreur est survenue lors de l\'envoi.');
            },
            complete: function() {
                $('#ing-candidature-form button[type="submit"]').html('<i class="fas fa-paper-plane mr-1"></i> Envoyer ma candidature');
            }
        });
    });

    // Gestion de l'activation/désactivation du bouton d'envoi
    $('#consent').on('change', function() {
        const submitBtn = $('#submit-candidature');
        if (this.checked) {
            submitBtn.prop('disabled', false)
                   .removeClass('bg-gray-400 cursor-not-allowed')
                   .addClass('bg-blue-600 hover:bg-blue-700');
        } else {
            submitBtn.prop('disabled', true)
                   .removeClass('bg-blue-600 hover:bg-blue-700')
                   .addClass('bg-gray-400 cursor-not-allowed');
        }
    });

    // Initialisation au chargement
    $('#consent').trigger('change');
});