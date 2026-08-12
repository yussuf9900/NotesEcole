<?php
require_once dirname(__DIR__) . '/models/AnneeModel.php';
require_once dirname(__DIR__) . '/models/ClasseModel.php';
require_once dirname(__DIR__) . '/models/MatiereModel.php';
require_once dirname(__DIR__) . '/models/PeriodeModel.php';
require_once dirname(__DIR__) . '/models/NoteModel.php';

function indexNote(): void {
    require_login();
    $pdo = connexionDB();

    $anneeActive = get_annee_active($pdo);
    $classes = get_all_classes($pdo);
    $periodes = get_all_periodes($pdo);

    if (isset($_GET['classe_id']) && $_GET['classe_id'] !== '') {
        $selectedClasseId = (int)$_GET['classe_id'];
    } else {
        $selectedClasseId = (int)get_session('selected_classe_id', $classes[0]['id'] ?? 1);
    }

    $validClasseIds = array_column($classes, 'id');
    if (!in_array($selectedClasseId, $validClasseIds, true) && !empty($validClasseIds)) {
        $selectedClasseId = $validClasseIds[0];
    }
    set_session('selected_classe_id', $selectedClasseId);

    $matieres = get_matieres_by_classe($pdo, $selectedClasseId);
    $validMatiereIds = array_column($matieres, 'id');

    if (isset($_GET['matiere_id']) && $_GET['matiere_id'] !== '') {
        $selectedMatiereId = (int)$_GET['matiere_id'];
    } else {
        $selectedMatiereId = (int)get_session('selected_matiere_id', 0);
    }

    if (!in_array($selectedMatiereId, $validMatiereIds, true) && !empty($validMatiereIds)) {
        $selectedMatiereId = $validMatiereIds[0] ?? 1;
    }
    set_session('selected_matiere_id', $selectedMatiereId);

    $validPeriodeIds = array_column($periodes, 'id');
    if (isset($_GET['periode_id']) && $_GET['periode_id'] !== '') {
        $selectedPeriodeId = (int)$_GET['periode_id'];
    } else {
        $selectedPeriodeId = (int)get_session('selected_periode_id', $periodes[0]['id'] ?? 1);
    }

    if (!in_array($selectedPeriodeId, $validPeriodeIds, true) && !empty($validPeriodeIds)) {
        $selectedPeriodeId = $validPeriodeIds[0];
    }
    set_session('selected_periode_id', $selectedPeriodeId);

    $anneeId = $anneeActive['id'] ?? 1;
    $elevesNotes = get_eleves_notes($pdo, $anneeId, $selectedClasseId, $selectedMatiereId, $selectedPeriodeId);

    $moyenneClasseMatiere = getMoyenneGeneral($pdo, $anneeId, $selectedClasseId, $selectedPeriodeId, $selectedMatiereId);
    $moyenneGeneraleClasse = getMoyenneGeneral($pdo, $anneeId, $selectedClasseId, $selectedPeriodeId);

    $currentUser = get_session('user');
    $flashMessage = get_flash();

    require_once dirname(__DIR__) . '/views/PageGestionNote.html.php';
}

function enregistrerNote(): void {
    require_login();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $classeId = isset($_POST['classe_id']) ? (int)$_POST['classe_id'] : (int)get_session('selected_classe_id', 1);
        $matiereId = isset($_POST['matiere_id']) ? (int)$_POST['matiere_id'] : (int)get_session('selected_matiere_id', 1);
        $periodeId = isset($_POST['periode_id']) ? (int)$_POST['periode_id'] : (int)get_session('selected_periode_id', 1);

        set_session('selected_classe_id', $classeId);
        set_session('selected_matiere_id', $matiereId);
        set_session('selected_periode_id', $periodeId);

        $rawNotes = $_POST['notes'] ?? [];
        $notesToSave = [];

        foreach ($rawNotes as $inscriptionId => $n) {
            $d1 = (isset($n['devoir1']) && trim((string)$n['devoir1']) !== '') ? (float)$n['devoir1'] : 0.0;
            $d2 = (isset($n['devoir2']) && trim((string)$n['devoir2']) !== '') ? (float)$n['devoir2'] : 0.0;
            $comp = (isset($n['composition']) && trim((string)$n['composition']) !== '') ? (float)$n['composition'] : 0.0;

            $notesToSave[] = [
                'inscription_id' => (int)$inscriptionId,
                'devoir1' => $d1,
                'devoir2' => $d2,
                'composition' => $comp
            ];
        }

        $pdo = connexionDB();
        $success = sauvegarder_notes($pdo, $matiereId, $periodeId, $notesToSave);

        if ($success) {
            set_flash('success', 'Les notes ont été enregistrées avec succès en base de données.');
        } else {
            set_flash('error', 'Une erreur est survenue lors de l\'enregistrement des notes.');
        }

        header("Location: /gestion?classe_id={$classeId}&matiere_id={$matiereId}&periode_id={$periodeId}");
        exit;
    }

    header('Location: /gestion');
    exit;
}




