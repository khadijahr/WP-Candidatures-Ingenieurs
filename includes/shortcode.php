<?php
// Shortcode pour afficher le formulaire
function ing_candidature_form_shortcode() {
    ob_start();
    ?>
    <div class="min-h-screen font-sans">
        <div class="container mx-auto px-4 py-12 max-w-2xl">            
            <!-- Indicateur d'étapes -->
            <ul class="step-indicator flex justify-between mb-10 px-10 text-center text-sm text-gray-500">
                <li class="active">
                    <span class="text-xs md:text-sm tag_label">Informations<br>personnelles</span>
                </li>
                <li class="">
                    <span class="text-xs md:text-sm tag_label">Parcours<br>académique</span>
                </li>
                <li>
                    <span class="text-xs md:text-sm tag_label">Validation</span>
                </li>
            </ul>

            <!-- Formulaire -->
            <form id="ing-candidature-form" class="bg-white rounded-xl shadow-sm p-6 md:p-8" method="post">
                <!-- Étape 1 - Informations personnelles -->
                <div class="form-step active" id="step1">
                    <h2 class="title-step text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Informations personnelles</h2>
                    
                    <div class="space-y-6 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                <input type="text" id="lastName" name="lastName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            </div>
                            <div>
                                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                <input type="text" id="firstName" name="firstName" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="birthDate" class="block text-sm font-medium text-gray-700 mb-1">Date de naissance <span class="text-red-500">*</span></label>
                                <input type="date" id="birthDate" name="birthDate" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            </div>
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Ville de résidence <span class="text-red-500">*</span></label>
                                <input type="text" id="city" name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            </div>
                        </div>                       

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-500">*</span></label>
                                <input type="tel" id="telephone" name="telephone" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required
                                    pattern="[0-9]{10}" title="Numéro à 10 chiffres (ex: 0612345678)">
                            </div>
                            <div>
								<label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
								<input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="jean@gmail.com" required>
							</div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
								<label for="bacYear" class="block text-sm font-medium text-gray-700 mb-1">Année d'obtention du BAC <span class="text-red-500">*</span></label>
								<input type="number" id="bacYear" name="bacYear" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" min="<?php echo date('Y') - 20; ?>" max="<?php echo date('Y'); ?>" placeholder="AAAA" required>
							</div>
                            <div>
                                <label for="highSchool" class="block text-sm font-medium text-gray-700 mb-1">Lycée <span class="text-red-500">*</span></label>
                                <input type="text" id="highSchool" name="highSchool" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                            </div>
                        </div>

                        <div>
                            <label for="bacType" class="block text-sm font-medium text-gray-700 mb-1">Type de BAC <span class="text-red-500">*</span></label>
                            <select id="bacType" name="bacType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="" disabled selected>Sélectionnez</option>
                                <option value="Scientifique">Scientifique</option>
                                <option value="Technique">Technique</option>
                                <option value="Économique">Économique</option>
                                <option value="Littéraire">Littéraire</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        
                    </div>

                    <div class="flex justify-end">  
                        <button type="button" class="btn-postuler next-step-btn px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-md">
                            Suivant <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 2 - Parcours académique -->
                <div class="form-step" id="step2">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Parcours académique</h2>

                    <div class="space-y-6">
                        <div>
                            <label for="entryYear" class="block text-sm font-medium text-gray-700 mb-1">En quelle année souhaitez-vous intégrer le programme ? <span class="text-red-500">*</span></label>
                            <select id="entryYear" name="entryYear" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                                <option value="" disabled selected>Sélectionnez</option>
                                <option value="1">1ʳᵉ année</option>
                                <option value="2">2ᵉ année</option>
                                <option value="3">3ᵉ année</option>
                                <option value="4">4ᵉ année</option>
                            </select>
                        </div>

                        <!-- Champ conditionnel pour 2ème année ou plus -->
                        <div id="pathField" class="conditional-field">
                            <label for="postBacPath" class="block text-sm font-medium text-gray-700 mb-1">Quel parcours avez-vous suivi après le BAC ? <span class="text-red-500">*</span></label>
                            <input type="text" id="postBacPath" name="postBacPath" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <!-- Champs conditionnels pour 3ème ou 4ème année -->
                        <div id="diplomaFields" class="conditional-field space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Avez-vous un diplôme ou une attestation ? <span class="text-red-500">*</span></label>
                                <div class="flex items-center space-x-6 mt-2">
                                    <div class="flex items-center">
                                        <input id="hasDiplomaYes" name="hasDiploma" type="radio" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="hasDiplomaYes" class="ml-2 text-sm text-gray-700">Oui</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="hasDiplomaNo" name="hasDiploma" type="radio" value="0" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <label for="hasDiplomaNo" class="ml-2 text-sm text-gray-700">Non</label>
                                    </div>
                                </div>
                            </div>

                            <div id="diplomaTypeField" class="conditional-field">
                                <label for="diplomaType" class="block text-sm font-medium text-gray-700 mb-1">Si oui, lequel ? <span class="text-red-500">*</span></label>
                                <input type="text" id="diplomaType" name="diplomaType" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-10 pt-6 border-t border-gray-200">
                        <button type="button" onclick="prevStep(2)" class="btn-precedent px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition flex items-center gap-2 shadow-md">
                            <i class="fas fa-arrow-left mr-1"></i> Précédent
                        </button>
                        <button type="button" class="btn-postuler next-step-btn px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-md">
                            Suivant <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Étape 3 - Validation -->
                <div class="form-step" id="step3">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-3">Validation de votre candidature</h2>

                    <div class="bg-div rounded-lg p-6 mb-8">
                        <h3 class="text-notif font-medium mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> Votre candidature est presque complète
                        </h3>
                        <p class="text-notif-2 text-sm">Veuillez vérifier les informations ci-dessous avant de soumettre votre candidature.</p>
                    </div>

                    <div class="space-y-6 mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Nom</p>
                                <p id="reviewLastName" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Prénom</p>
                                <p id="reviewFirstName" class="font-medium">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Date de naissance</p>
                                <p id="reviewBirthDate" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ville de résidence</p>
                                <p id="reviewCity" class="font-medium">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Téléphone</p>
                                <p id="reviewTelephone" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p id="reviewEmail" class="font-medium">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Lycée</p>
                                <p id="reviewHighSchool" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Type de BAC</p>
                                <p id="reviewBacType" class="font-medium">-</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500">Année BAC</p>
                                <p id="reviewBacYear" class="font-medium">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Année d'intégration</p>
                                <p id="reviewEntryYear" class="font-medium">-</p>
                            </div>
                        </div> 

                        <div id="reviewPathField" class="hidden">
                            <p class="text-sm text-gray-500">Parcours post-BAC</p>
                            <p id="reviewPostBacPath" class="font-medium">-</p>
                        </div>

                        <div id="reviewDiplomaFields" class="hidden space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Diplôme ou attestation</p>
                                <p id="reviewHasDiploma" class="font-medium">-</p>
                            </div>
                            <div id="reviewDiplomaTypeField" class="hidden">
                                <p class="text-sm text-gray-500">Type de diplôme</p>
                                <p id="reviewDiplomaType" class="font-medium">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-6 border-t border-gray-200">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="consent" name="consent" type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" required>
                            </div>
                            <label for="consent" class="cc-notif ml-3 text-sm text-gray-700">
                                Je certifie que les informations fournies sont exactes et complètes.
                            </label>
                        </div>

                        <div class="flex divf-w sm:flex-nowrap justify-between mt-6">
                            <button type="button" onclick="prevStep(3)" class="btn-precedent px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition flex items-center gap-2 shadow-md">
                                <i class="fas fa-arrow-left mr-1"></i> Précédent
                            </button>
                            <button type="submit" name="submit_candidature" class="send-button px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition flex items-center gap-2 shadow-md">
                                <i class="fas fa-paper-plane mr-1"></i> Envoyer ma candidature
                            </button>
                        </div>
                    </div>
                </div>
            </form>           
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('ingenieurs_candidature', 'ing_candidature_form_shortcode');