<?php

require_once dirname(__DIR__) . '/core/Session.php';
require_once dirname(__DIR__) . '/models/AnneeScolaireModel.php';
require_once dirname(__DIR__) . '/models/ClasseModel.php';
require_once dirname(__DIR__) . '/models/MatiereClasseModel.php';
require_once dirname(__DIR__) . '/models/PeriodeModel.php';
require_once dirname(__DIR__) . '/models/EvaluationModel.php';
require_once dirname(__DIR__) . '/Entity/Evaluation.php';

class NoteController {
    private AnneeScolaireModel $anneeModel;
    private ClasseModel $classeModel;
    private MatiereClasseModel $matiereClasseModel;
    private PeriodeModel $periodeModel;
    private EvaluationModel $evaluationModel;

    public function __construct() {
        $this->anneeModel = new AnneeScolaireModel();
        $this->classeModel = new ClasseModel();
        $this->matiereClasseModel = new MatiereClasseModel();
        $this->periodeModel = new PeriodeModel();
        $this->evaluationModel = new EvaluationModel();
    }

    public function index(): void {
        Session::requireLogin();

        $anneeActive = $this->anneeModel->getActive();
        $classes = $this->classeModel->findAll();
        $periodes = $this->periodeModel->findAll();

        $firstClasseId = !empty($classes) ? $classes[0]->getId() : 1;
        if (isset($_GET['classe_id']) && $_GET['classe_id'] !== '') {
            $selectedClasseId = (int)$_GET['classe_id'];
        } else {
            $selectedClasseId = (int)Session::get('selected_classe_id', $firstClasseId);
        }

        $validClasseIds = array_map(fn(Classe $c) => $c->getId(), $classes);
        if (!in_array($selectedClasseId, $validClasseIds, true) && !empty($validClasseIds)) {
            $selectedClasseId = $validClasseIds[0];
        }
        Session::set('selected_classe_id', $selectedClasseId);

        $matieres = $this->matiereClasseModel->getMatieresByClasseId($selectedClasseId);
        $validMatiereIds = array_map(fn(Matiere $m) => $m->getId(), $matieres);

        if (isset($_GET['matiere_id']) && $_GET['matiere_id'] !== '') {
            $selectedMatiereId = (int)$_GET['matiere_id'];
        } else {
            $selectedMatiereId = (int)Session::get('selected_matiere_id', $validMatiereIds[0] ?? 1);
        }

        if (!in_array($selectedMatiereId, $validMatiereIds, true) && !empty($validMatiereIds)) {
            $selectedMatiereId = $validMatiereIds[0] ?? 1;
        }
        Session::set('selected_matiere_id', $selectedMatiereId);

        $validPeriodeIds = array_map(fn(Periode $p) => $p->getId(), $periodes);
        if (isset($_GET['periode_id']) && $_GET['periode_id'] !== '') {
            $selectedPeriodeId = (int)$_GET['periode_id'];
        } else {
            $selectedPeriodeId = (int)Session::get('selected_periode_id', $validPeriodeIds[0] ?? 1);
        }

        if (!in_array($selectedPeriodeId, $validPeriodeIds, true) && !empty($validPeriodeIds)) {
            $selectedPeriodeId = $validPeriodeIds[0];
        }
        Session::set('selected_periode_id', $selectedPeriodeId);

        $anneeId = $anneeActive ? (int)$anneeActive->getId() : 1;
        $elevesNotes = $this->evaluationModel->getElevesNotes($anneeId, $selectedClasseId, $selectedMatiereId, $selectedPeriodeId);

        $moyenneClasseMatiere = $this->evaluationModel->getMoyenneGeneral($anneeId, $selectedClasseId, $selectedPeriodeId, $selectedMatiereId);
        $moyenneGeneraleClasse = $this->evaluationModel->getMoyenneGeneral($anneeId, $selectedClasseId, $selectedPeriodeId);

        $currentUser = Session::getUser() ?? Session::get('user');
        $flashMessage = Session::getFlash();

        $this->render('PageGestionNote.html.php', [
            'anneeActive' => $anneeActive,
            'classes' => $classes,
            'periodes' => $periodes,
            'matieres' => $matieres,
            'selectedClasseId' => $selectedClasseId,
            'selectedMatiereId' => $selectedMatiereId,
            'selectedPeriodeId' => $selectedPeriodeId,
            'elevesNotes' => $elevesNotes,
            'moyenneClasseMatiere' => $moyenneClasseMatiere,
            'moyenneGeneraleClasse' => $moyenneGeneraleClasse,
            'currentUser' => $currentUser,
            'flashMessage' => $flashMessage
        ]);
    }

    public function save(): void {
        Session::requireLogin();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $classeId = isset($_POST['classe_id']) ? (int)$_POST['classe_id'] : (int)Session::get('selected_classe_id', 1);
            $matiereId = isset($_POST['matiere_id']) ? (int)$_POST['matiere_id'] : (int)Session::get('selected_matiere_id', 1);
            $periodeId = isset($_POST['periode_id']) ? (int)$_POST['periode_id'] : (int)Session::get('selected_periode_id', 1);

            Session::set('selected_classe_id', $classeId);
            Session::set('selected_matiere_id', $matiereId);
            Session::set('selected_periode_id', $periodeId);

            $rawNotes = $_POST['notes'] ?? [];
            $notesToSave = [];

            foreach ($rawNotes as $inscriptionId => $n) {
                $d1 = (isset($n['devoir1']) && trim((string)$n['devoir1']) !== '') ? (float)$n['devoir1'] : 0.0;
                $d2 = (isset($n['devoir2']) && trim((string)$n['devoir2']) !== '') ? (float)$n['devoir2'] : 0.0;
                $comp = (isset($n['composition']) && trim((string)$n['composition']) !== '') ? (float)$n['composition'] : 0.0;

                $eval = new Evaluation();
                $eval->setInscriptionId((int)$inscriptionId);
                $eval->setMatiereId($matiereId);
                $eval->setPeriodeId($periodeId);
                $eval->setDevoir1($d1);
                $eval->setDevoir2($d2);
                $eval->setComposition($comp);

                $notesToSave[] = $eval;
            }

            $success = $this->evaluationModel->saveNotes($matiereId, $periodeId, $notesToSave);

            if ($success) {
                Session::setFlash('success', 'Les notes ont été enregistrées avec succès en base de données.');
            } else {
                Session::setFlash('error', 'Une erreur est survenue lors de l\'enregistrement des notes.');
            }

            $this->redirect("/gestion?classe_id={$classeId}&matiere_id={$matiereId}&periode_id={$periodeId}");
        }

        $this->redirect('/gestion');
    }

    private function render(string $view, array $data = []): void {
        extract($data);
        $viewPath = dirname(__DIR__) . '/views/' . $view;
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Erreur : La vue '{$view}' est introuvable.");
        }
    }

    private function redirect(string $url): void {
        header("Location: " . $url);
        exit;
    }
}
