<?php
$en = include __DIR__ . '/../resources/lang/en/text.php';
$fr = include __DIR__ . '/../resources/lang/fr/text.php';
$missing = array_diff_key($en, $fr);

// Build direct phrase map from already translated keys
$phrase_map = [];
foreach ($fr as $key => $fr_value) {
    if (isset($en[$key])) {
        $phrase_map[$en[$key]] = $fr_value;
    }
}

// Add custom phrase translations for English phrases that may not exist in FR yet
$phrase_map += [
    'forgot password' => 'mot de passe oublié',
    'forgot username' => 'nom d’utilisateur oublié',
    'create new expense' => 'créer une nouvelle dépense',
    'add new campus' => 'ajouter un nouveau campus',
    'add new role' => 'ajouter un rôle',
    'add new material' => 'ajouter du nouveau matériel',
    'application bypass' => 'contournement de l’application',
    'bypass application fee' => 'contourner les frais de dossier',
    'platform bypass' => 'contournement de la plateforme',
    'bypass report' => 'rapport de contournement',
    'download csv' => 'télécharger csv',
    'search by name, email or phone number' => 'rechercher par nom, email ou numéro de téléphone',
    'no statistics found for the selected academic year.' => 'Aucune statistique trouvée pour l’année scolaire sélectionnée.',
    'please select user' => 'veuillez sélectionner l’utilisateur',
    'please select user' => 'veuillez sélectionner l’utilisateur',
    'course registration is closed for this semester' => 'les inscriptions aux cours sont fermées pour ce semestre',
    'course registration date line' => 'date limite d’inscription aux cours',
    'personal details / information personnel' => 'détails personnels / informations personnelles',
    'course envisaged / filiere (option)' => 'cours envisagé / filière (option)',
    'first choice / premier choix' => 'premier choix / premier choix',
    'second choice / deuxieme choix' => 'deuxième choix / deuxième choix',
    'print receipt' => 'imprimer le reçu',
    'deposit payment' => 'versement',
    'cash receipt' => 'reçu en espèces',
    'transaction initialized' => 'transaction initialisée',
    'pending transaction phrase' => 'transaction en attente de confirmation. Composez votre code Orange Money ou MOMO pour confirmer le paiement.',
    'no results are available yet for this student' => 'Aucun résultat n’est encore disponible pour cet étudiant',
    'download statistics' => 'télécharger les statistiques',
    'platform charges' => 'frais de plateforme',
    'fee daily report for' => 'rapport journalier des frais pour',
    'who have paid atleast' => 'qui ont payé au moins',
    'student listing' => 'liste des étudiants',
    'per level' => 'par niveau',
    'per program' => 'par programme',
    'select recipients' => 'sélectionner les destinataires',
    'collect extra fee' => 'collecter des frais supplémentaires',
    'add extra fee' => 'ajouter des frais supplémentaires',
    'platform payments template text' => 'Faites le paiement via Orange Money ou MOMO. Le montant s’ajustera automatiquement.',
    'duplicated charges attempt' => 'tentative de double facturation',
    'no campus found' => 'aucun campus trouvé',
    'cannot delete last batch' => 'impossible de supprimer le dernier lot',
    'file must be of type :type' => 'Le fichier doit être de type :type',
    'upload letter head' => 'télécharger l’en-tête de lettre',
    'operation failed.' => 'l’opération a échoué.',
    'student with matricule :matric not found.' => 'étudiant avec matricule :matric introuvable.',
    'course with code :code not found.' => 'cours avec code :code introuvable.',
    'no class registered for student :student Year :year' => 'aucune classe enregistrée pour l’étudiant :student Année :year',
    'exam total not set for :program' => 'TOTAL D’EXAMEN non défini pour :program',
    'exam mark for :course exceeds ca totals for student :student' => 'La note d’EXAMEN pour :course dépasse le TOTAL de CC pour l’étudiant :student',
];

// Lowercase key helper
function normalize($value) {
    return trim(preg_replace('/\s+/', ' ', $value));
}

// Simple token translation map from existing data and manual terms
$word_map = [
    'account' => 'compte',
    'accounts' => 'comptes',
    'and' => 'et',
    'admin' => 'administrateur',
    'administrator' => 'administrateur',
    'application' => 'application',
    'applicants' => 'candidats',
    'applicant' => 'candidat',
    'apply' => 'appliquer',
    'archive' => 'archiver',
    'arrange' => 'organiser',
    'assessment' => 'évaluation',
    'assistance' => 'assistance',
    'attention' => 'attention',
    'audit' => 'audit',
    'authenticate' => 'authentifier',
    'authentication' => 'authentification',
    'authorize' => 'autoriser',
    'auto' => 'automatique',
    'available' => 'disponible',
    'average' => 'moyenne',
    'award' => 'récompense',
    'awarded' => 'attribué',
    'axis' => 'axe',
    'approve' => 'approuver',
    'as' => 'comme',
    'at' => 'à',
    'background' => 'arrière-plan',
    'balance' => 'solde',
    'batch' => 'lot',
    'be' => 'être',
    'by' => 'par',
    'cancel' => 'annuler',
    'capacity' => 'capacité',
    'center' => 'centre',
    'certificate' => 'certificat',
    'certificate_programs' => 'programmes de certificat',
    'change' => 'changement',
    'charge' => 'frais',
    'charges' => 'frais',
    'class' => 'classe',
    'code' => 'code',
    'collect' => 'collecter',
    'collection' => 'collecte',
    'collections' => 'collectes',
    'completed' => 'terminé',
    'completion' => 'achèvement',
    'confirm' => 'confirmer',
    'continue' => 'continuer',
    'contact' => 'contact',
    'content' => 'contenu',
    'country' => 'pays',
    'course' => 'cours',
    'courses' => 'cours',
    'create' => 'créer',
    'current' => 'actuel',
    'custom' => 'personnalisé',
    'dashboard' => 'tableau de bord',
    'date' => 'date',
    'day' => 'jour',
    'declaration' => 'déclaration',
    'degree' => 'diplôme',
    'department' => 'département',
    'deputy' => 'adjoint',
    'diploma' => 'diplôme',
    'direct' => 'direct',
    'director' => 'directeur',
    'discount' => 'réduction',
    'documents' => 'documents',
    'download' => 'télécharger',
    'duration' => 'durée',
    'edit' => 'modifier',
    'email' => 'email',
    'emergency' => 'urgence',
    'enter' => 'entrer',
    'entry' => 'entrée',
    'estimated' => 'estimé',
    'exam' => 'examen',
    'examined' => 'examiné',
    'exams' => 'examens',
    'expense' => 'dépense',
    'expenses' => 'dépenses',
    'export' => 'exporter',
    'external' => 'externe',
    'faculty' => 'faculté',
    'fail' => 'échec',
    'failed' => 'échoué',
    'fees' => 'frais',
    'fee' => 'frais',
    'female' => 'femelle',
    'fill' => 'remplir',
    'final' => 'final',
    'finished' => 'terminé',
    'first' => 'premier',
    'full' => 'plein',
    'gender' => 'genre',
    'general' => 'général',
    'grade' => 'note',
    'grading' => 'classement',
    'grand' => 'grand',
    'gross' => 'brut',
    'group' => 'groupe',
    'guest' => 'invité',
    'guide' => 'guide',
    'help' => 'aide',
    'history' => 'histoire',
    'home' => 'accueil',
    'hourly' => 'horaire',
    'hours' => 'heures',
    'import' => 'importer',
    'income' => 'revenu',
    'incomes' => 'revenus',
    'information' => 'information',
    'inquiry' => 'demande',
    'institution' => 'institution',
    'insurance' => 'assurance',
    'international' => 'international',
    'intranet' => 'intranet',
    'invoice' => 'facture',
    'item' => 'article',
    'items' => 'articles',
    'journal' => 'journal',
    'language' => 'langue',
    'last' => 'dernier',
    'latest' => 'dernier',
    'level' => 'niveau',
    'levels' => 'niveaux',
    'list' => 'liste',
    'lists' => 'listes',
    'load' => 'charger',
    'login' => 'connexion',
    'logout' => 'déconnexion',
    'manage' => 'gérer',
    'management' => 'gestion',
    'manual' => 'manuel',
    'map' => 'carte',
    'mark' => 'note',
    'marks' => 'notes',
    'matricule' => 'matricule',
    'message' => 'message',
    'messages' => 'messages',
    'month' => 'mois',
    'months' => 'mois',
    'name' => 'nom',
    'nationality' => 'nationalité',
    'new' => 'nouveau',
    'next' => 'suivant',
    'no' => 'non',
    'not' => 'pas',
    'notification' => 'notification',
    'notifications' => 'notifications',
    'number' => 'numéro',
    'numbers' => 'nombres',
    'online' => 'en ligne',
    'optional' => 'optionnel',
    'or' => 'ou',
    'other' => 'autre',
    'out' => 'dehors',
    'page' => 'page',
    'paid' => 'payé',
    'payment' => 'paiement',
    'payments' => 'paiements',
    'parent' => 'parent',
    'parents' => 'parents',
    'payment' =>'paiement',
    'pay' => 'payer',
    'pending' => 'en attente',
    'personal' => 'personnel',
    'period' => 'période',
    'print' => 'imprimer',
    'printed' => 'imprimé',
    'program' => 'programme',
    'programs' => 'programmes',
    'proof' => 'preuve',
    'publish' => 'publier',
    'published' => 'publié',
    'receipt' => 'reçu',
    'record' => 'enregistrement',
    'reference' => 'référence',
    'registered' => 'enregistré',
    'registration' => 'inscription',
    'release' => 'publication',
    'report' => 'rapport',
    'reports' => 'rapports',
    'required' => 'requis',
    'reset' => 'réinitialiser',
    'result' => 'résultat',
    'results' => 'résultats',
    'role' => 'rôle',
    'roles' => 'rôles',
    'save' => 'sauvegarder',
    'school' => 'école',
    'schooling' => 'scolarité',
    'semester' => 'semestre',
    'semesters' => 'semestres',
    'send' => 'envoyer',
    'send' => 'envoyer',
    'service' => 'service',
    'settings' => 'paramètres',
    'sign' => 'signer',
    'single' => 'célibataire',
    'soft' => 'douce',
    'solid' => 'solide',
    'sort' => 'trier',
    'special' => 'spécial',
    'student' => 'étudiant',
    'students' => 'étudiants',
    'submit' => 'soumettre',
    'summary' => 'résumé',
    'switch' => 'changer',
    'table' => 'table',
    'teacher' => 'enseignant',
    'teachers' => 'enseignants',
    'telephone' => 'téléphone',
    'tel' => 'tél',
    'term' => 'terme',
    'terms' => 'termes',
    'text' => 'texte',
    'time' => 'temps',
    'title' => 'titre',
    'to' => 'à',
    'total' => 'total',
    'totals' => 'totaux',
    'transfer' => 'transfert',
    'transcript' => 'relevé de notes',
    'transcripts' => 'relevés de notes',
    'transit' => 'transit',
    'university' => 'université',
    'update' => 'mise à jour',
    'upload' => 'télécharger',
    'user' => 'utilisateur',
    'users' => 'utilisateurs',
    'view' => 'voir',
    'visible' => 'visible',
    'year' => 'année',
    'years' => 'années',
    'yes' => 'oui',
    'you' => 'vous',
    'your' => 'votre',
    'yourself' => 'vous-même',
];

// Also extract additional one-word translations from existing phrase pairs
foreach ($phrase_map as $eng => $frt) {
    $engTokens = preg_split('/[\s\/|,&]+/', strtolower($eng));
    $frTokens = preg_split('/[\s\/|,&]+/', strtolower($frt));
    foreach ($engTokens as $i => $token) {
        if (!isset($word_map[$token]) && isset($frTokens[$i])) {
            $word_map[$token] = $frTokens[$i];
        }
    }
}

function translateText($text, $phrase_map, $word_map) {
    $text = trim($text);
    if (isset($phrase_map[$text])) {
        return $phrase_map[$text];
    }
    $original = $text;
    // preserve placeholders and html tags
    $parts = preg_split('/(:[a-zA-Z0-9_]+|<[^>]*>|\s+|[^a-zA-Z0-9_:\s<>]+)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
    $result = [];
    foreach ($parts as $part) {
        if (preg_match('/^:[a-zA-Z0-9_]+$/', $part) || preg_match('/^<[^>]*>$/', $part) || trim($part) === '') {
            $result[] = $part;
            continue;
        }
        $lower = strtolower($part);
        if (isset($phrase_map[$lower])) {
            $result[] = $phrase_map[$lower];
            continue;
        }
        if (isset($word_map[$lower])) {
            $translated = $word_map[$lower];
            // keep original case for starting words
            if (ctype_upper(substr($part, 0, 1))) {
                $translated = ucfirst($translated);
            }
            $result[] = $translated;
            continue;
        }
        // preserve uppercase acronyms like CA, DVC, etc.
        if (preg_match('/^[A-Z0-9_]+$/', $part)) {
            $result[] = $part;
            continue;
        }
        // fallback: keep part but convert a few common English words
        $fallback = $part;
        $fallback = str_replace(['application'], ['application'], $fallback);
        $result[] = $fallback;
    }
    $translated = implode('', $result);
    // cosmetic fixes
    $translated = preg_replace('/\s+([\.,;:\?\!])/', '$1', $translated);
    return $translated;
}

// Manual overrides for tricky sentences
$manual = [
    'CA mark for :course exceeds CA TOTALS for student :student' => 'La note CC pour :course dépasse le TOTAL de CC pour l’étudiant :student',
    'CA TOTAL not set for :program' => 'TOTAL de CC non défini pour :program',
    'DATE LINE NOT SET' => 'DATE LIMITE NON DÉFINIE',
    'DEPARTMENT OF' => 'DÉPARTEMENT DE',
    'DVC academic affairs & research' => 'DVC affaires académiques et recherche',
    'Director of administration & finance' => 'Directeur de l’administration et des finances',
    'DVC cooperation & innovation' => 'DVC coopération et innovation',
    'PLATFORM CHARGES' => 'FRAIS DE PLATEFORME',
    'SCHOOL OF' => 'ÉCOLE DE',
    'SEMESTER RESULT CHARGES' => 'FRAIS DE RÉSULTAT SEMESTRIEL',
    'TRANSCRIPT APPLICATION CHARGES' => 'FRAIS DE DEMANDE DE RELEVÉ',
    'forgot password' => 'mot de passe oublié',
    'accounts & users' => 'comptes et utilisateurs',
    'add additional fee for :item' => 'ajouter un frais supplémentaire pour :item',
    'add sub-topic' => 'ajouter un sous-thème',
    'add fee listing for :item' => 'ajouter une liste de frais pour :item',
    'add new campus' => 'ajouter un nouveau campus',
    'add wages' => 'ajouter des salaires',
    'additional personal details' => 'détails personnels supplémentaires',
    'admin user manual' => 'manuel administrateur',
    'admission date' => 'date d’admission',
    'admission information' => 'informations d’admission',
    'admission letter' => 'lettre d’admission',
    'Please note that if it is discovered at any time that you do not possess any of the qualifications you claim to have obtained, the admission will be withdrawn immediately.' => 'Veuillez noter que si, à tout moment, il est découvert que vous ne possédez pas l’une des qualifications que vous prétendez avoir obtenues, l’admission sera immédiatement annulée.',
    'Dear Mr/Mrs/Miss <b><i>:name</i></b>' => 'Cher M./Mme/Mlle <b><i>:name</i></b>',
    'If you cannot at the time of registration produce the original copies of your certificates and other credentials this offer of admission may be revoked.' => 'Si vous ne pouvez pas fournir au moment de l’inscription les copies originales de vos certificats et autres pièces justificatives, cette offre d’admission peut être révoquée.',
    'The first instalment (<b><i>at least 50%</i></b>) of the tuition fee must be paid on or before  the<b><i> :date1,</i></b> while the second and the last instalment must be paid on or before the <b><i>:date2 </i></b>.' => 'Le premier versement (<b><i>au moins 50%</i></b>) des frais de scolarité doit être payé au plus tard le <b><i>:date1,</i></b> tandis que le deuxième et dernier versement doit être payé au plus tard le <b><i>:date2</i></b>.',
    'It is mandatory for you to attend the orientation program for new students as will be scheduled.' => 'Il est obligatoire d’assister au programme d’orientation des nouveaux étudiants tel que prévu.',
    'We congratulate you for your admission and welcome you into Biaka University Institute .In case there is the need for more information or clarification, please contact email us at <b><i>:email</i></b>' => 'Nous vous félicitons pour votre admission et vous souhaitons la bienvenue à l’Institut universitaire Biaka. Si vous avez besoin de plus d’informations ou de précisions, veuillez nous contacter par email à <b><i>:email</i></b>.',
    'Login information; <i>Username <b>:matric</b>, password <b>12345678</b></i>.' => 'Informations de connexion ; <i>Nom d’utilisateur <b>:matric</b>, mot de passe <b>12345678</b></i>.',
    'We welcome you to Biaka University Institute of Buea and hopt that you will help us make your stay at the University fruitful and pleasant.' => 'Nous vous souhaitons la bienvenue à l’Institut universitaire Biaka de Buea et espérons que vous contribuerez à rendre votre séjour à l’Université fructueux et agréable.',
    'With Reference to your application for admission into Biaka University Institute  of Buea, we are pleased to inform you that you have been offered admission to pursue an academic and a professional program leading to the award of a/an <b>:degree</b> in the department of <b>:department</b> for the <b>:year</b> session. The program is full time and on campus.<br>        The offer of admission is conditional upon payment of a <b>tution fee (should be paid at the bank specified below)</b> of <b> :tution_fee </b> (first installment <b> :first_installment CFA</b>) and a <b>Registration fee (should be paid on campus)</b> of <b>:reg_fee</b> CFA making a total of <b>:fee_total CFA for Nationals.</b> International students will pay a tution fee of 1055USD' => 'En référence à votre demande d’admission à l’Institut universitaire Biaka de Buea, nous avons le plaisir de vous informer que vous avez été admis à poursuivre un programme académique et professionnel conduisant à l’obtention d’un(e) <b>:degree</b> dans le département de <b>:department</b> pour la session <b>:year</b>. Le programme est à temps plein et sur le campus.<br>        L’offre d’admission est conditionnée au paiement de <b>frais de scolarité (devant être payés à la banque indiquée ci-dessous)</b> de <b> :tution_fee </b> (premier acompte <b> :first_installment CFA</b>) et de <b>frais d’inscription (devant être payés sur le campus)</b> de <b>:reg_fee</b> CFA, pour un total de <b>:fee_total CFA pour les nationaux.</b> Les étudiants internationaux devront payer des frais de scolarité de 1055 USD.',
    'admission letters' => 'lettres d’admission',
    'admission statistics' => 'statistiques d’admission',
    'admit foreigners' => 'admettre des étrangers',
    'admit locals' => 'admettre des locaux',
    'admit student' => 'admettre l’étudiant',
    'admitted students' => 'étudiants admis',
    'advanced level results' => 'résultats du niveau avancé',
    'all our programs' => 'tous nos programmes',
    'all result releases' => 'toutes les publications de résultats',
    'all students for :unit' => 'tous les étudiants pour :unit',
    'already gave out item to :item' => 'a déjà remis l’article à :item',
    ':student already has a CA mark for :course this academic year and will not be added. Clear or delete record and re-import to make sure all data is correct</br>' => ':student a déjà une note CC pour :course cette année académique et elle ne sera pas ajoutée. Effacez ou supprimez l’enregistrement et réimportez pour vous assurer que toutes les données sont correctes</br>',
    ':student already has a EXAM mark for :course this academic year and will not be added. Clear or delete record and re-import to make sure all data is correct</br>' => ':student a déjà une note EXAM pour :course cette année académique et elle ne sera pas ajoutée. Effacez ou supprimez l’enregistrement et réimportez pour vous assurer que toutes les données sont correctes</br>',
    'any disabilities? / avez-vous un handicap?' => 'any disabilities? / avez-vous un handicap?',
    'any known health allergy? / avez-vous un problem de allergies?' => 'any known health allergy? / avez-vous un problème d’allergies?',
    'any known health problem? / avez-vous un problem de sante?' => 'any known health problem? / avez-vous un problème de santé?',
    'API root' => 'racine API',
    'appliable programs' => 'programmes applicables',
    'applicants center' => 'centre des candidats',
    'application bypass' => 'contournement d’application',
    'Applying for :degree_type in at :campus campus' => 'Candidature pour :degree_type sur le campus de :campus',
    'APPLYING FOR A(AN) :degree_type PROGRAM' => 'CANDIDATURE À UN PROGRAMME DE :degree_type',
    'You are about to make a payment of :amount CFA for application fee' => 'Vous êtes sur le point d’effectuer un paiement de :amount CFA pour les frais de dossier',
    'By clicking this button, you are confirming that every information supplied is correct.' => 'En cliquant sur ce bouton, vous confirmez que toutes les informations fournies sont correctes.',
    'Momo Transaction ID' => 'ID de transaction Momo',
    'In need of help ? Contact us at <b><i>:contacts</i></b>' => 'Besoin d’aide ? Contactez-nous à <b><i>:contacts</i></b>',
    'PLEASE REMEMBER TO SUBMIT YOUR FORM AT THE END OF THIS PROCESS.' => 'VEUILLEZ VOUS SOUVENIR DE SOUMETTRE VOTRE FORMULAIRE À LA FIN DE CE PROCESSUS.',
    'No statistics found for the selected academic year.' => 'Aucune statistique trouvée pour l’année scolaire sélectionnée.',
    'Search by name, email or phone number' => 'Rechercher par nom, email ou numéro de téléphone',
    'no campus found' => 'aucun campus trouvé',
    'FRAIS DE DEMANDE DE RELEVÉ' => 'FRAIS DE DEMANDE DE RELEVÉ',
    'FRAIS DE RÉSULTAT SEMESTRIEL' => 'FRAIS DE RÉSULTAT SEMESTRIEL',
];

// trim down word map duplicates
$word_map = array_change_key_case($word_map, CASE_LOWER);

foreach ($missing as $key => $value) {
    if (isset($manual[$value])) {
        $translation = $manual[$value];
    } else {
        $translation = translateText($value, $phrase_map + $manual, $word_map);
    }
    $translation = str_replace("'", "\'", $translation);
    echo "    '" . $key . "' => '" . $translation . "',\n";
}
?>
