<?php

require_once dirname(__DIR__) . '/core/Database.php';

function get_eleves_notes(PDO $pdo, int $anneeId, int $classeId, int $matiereId, int $periodeId): array
{
    $sql = "SELECT 
                i.id AS inscription_id,
                e.id AS eleve_id,
                e.nom,
                e.prenom,
                e.matricule,
                ev.id AS evaluation_id,
                COALESCE(ev.devoir1, 0) AS devoir1,
                COALESCE(ev.devoir2, 0) AS devoir2,
                COALESCE(ev.composition, 0) AS composition
            FROM eleves e
            JOIN inscriptions i ON i.eleve_id = e.id AND i.annee_id = :annee_id AND i.classe_id = :classe_id
            LEFT JOIN evaluations ev ON ev.inscription_id = i.id 
                AND ev.matiere_id = :matiere_id 
                AND ev.periode_id = :periode_id
            ORDER BY e.nom ASC, e.prenom ASC";

    $params = [
        'annee_id' => $anneeId,
        'classe_id' => $classeId,
        'matiere_id' => $matiereId,
        'periode_id' => $periodeId
    ];

    return executeQuery($pdo, $sql, $params);
}

function getMoyenneGeneral(
    PDO $pdo,
    int $anneeId,
    int $classeId,
    int $periodeId,
    ?int $matiereId = null,
    ?int $inscriptionId = null
): float {
    require_once __DIR__ . '/MatiereModel.php';

    if ($inscriptionId !== null) {
        if ($matiereId !== null) {
            $sql = "SELECT ROUND((COALESCE(devoir1, 0) + COALESCE(devoir2, 0) + 2 * COALESCE(composition, 0)) / 4.0, 2) AS moyenne
                    FROM evaluations
                    WHERE inscription_id = :inscription_id
                      AND periode_id = :periode_id
                      AND matiere_id = :matiere_id";
            $params = [
                'inscription_id' => $inscriptionId,
                'periode_id' => $periodeId,
                'matiere_id' => $matiereId
            ];
        } else {
            $matieres = get_matieres_by_classe($pdo, $classeId);
            if (empty($matieres)) {
                return 0.0;
            }
            $matiereIds = array_column($matieres, 'id');
            $inClause = implode(',', array_map('intval', $matiereIds));

            $sql = "SELECT ROUND(COALESCE(AVG(mm.moyenne_matiere), 0), 2) AS moyenne
                    FROM (
                        SELECT 
                            m.matiere_id,
                            ROUND((COALESCE(ev.devoir1, 0) + COALESCE(ev.devoir2, 0) + 2 * COALESCE(ev.composition, 0)) / 4.0, 2) AS moyenne_matiere
                        FROM (SELECT unnest(ARRAY[{$inClause}]) AS matiere_id) m
                        LEFT JOIN evaluations ev 
                               ON ev.matiere_id = m.matiere_id 
                              AND ev.inscription_id = :inscription_id
                              AND ev.periode_id = :periode_id
                    ) AS mm
                    WHERE mm.moyenne_matiere > 0";
            $params = [
                'inscription_id' => $inscriptionId,
                'periode_id' => $periodeId
            ];
        }
    } else {
        if ($matiereId !== null) {
            $sql = "SELECT ROUND(COALESCE(AVG(moyenne_eleve), 0), 2) AS moyenne
                    FROM (
                        SELECT 
                            i.id AS inscription_id,
                            ROUND((COALESCE(ev.devoir1, 0) + COALESCE(ev.devoir2, 0) + 2 * COALESCE(ev.composition, 0)) / 4.0, 2) AS moyenne_eleve
                        FROM inscriptions i
                        LEFT JOIN evaluations ev 
                               ON ev.inscription_id = i.id 
                              AND ev.matiere_id = :matiere_id 
                              AND ev.periode_id = :periode_id
                        WHERE i.annee_id = :annee_id
                          AND i.classe_id = :classe_id
                    ) AS moyennes_eleves
                    WHERE moyenne_eleve > 0";
            $params = [
                'annee_id' => $anneeId,
                'classe_id' => $classeId,
                'periode_id' => $periodeId,
                'matiere_id' => $matiereId
            ];
        } else {
            $matieres = get_matieres_by_classe($pdo, $classeId);
            if (empty($matieres)) {
                return 0.0;
            }
            $matiereIds = array_column($matieres, 'id');
            $inClause = implode(',', array_map('intval', $matiereIds));
            $nbMatieres = count($matiereIds);

            $sql = "SELECT ROUND(COALESCE(AVG(moyenne_eleve), 0), 2) AS moyenne
                    FROM (
                        SELECT 
                            i.id AS inscription_id,
                            ROUND(COALESCE(SUM(ROUND((COALESCE(ev.devoir1, 0) + COALESCE(ev.devoir2, 0) + 2 * COALESCE(ev.composition, 0)) / 4.0, 2)), 0) / {$nbMatieres}.0, 2) AS moyenne_eleve
                        FROM inscriptions i
                        CROSS JOIN (SELECT unnest(ARRAY[{$inClause}]) AS matiere_id) m
                        LEFT JOIN evaluations ev 
                               ON ev.inscription_id = i.id 
                              AND ev.matiere_id = m.matiere_id 
                              AND ev.periode_id = :periode_id
                        WHERE i.annee_id = :annee_id
                          AND i.classe_id = :classe_id
                        GROUP BY i.id
                    ) AS moyennes_eleves
                    WHERE moyenne_eleve > 0";
            $params = [
                'annee_id' => $anneeId,
                'classe_id' => $classeId,
                'periode_id' => $periodeId
            ];
        }
    }

    $res = executeQuery($pdo, $sql, $params, true);
    return (float) ($res['moyenne'] ?? 0);
}

function sauvegarder_notes(PDO $pdo, int $matiereId, int $periodeId, array $notes): bool
{
    try {
        $pdo->beginTransaction();

        $sqlCheck = "SELECT id FROM evaluations WHERE inscription_id = :inscription_id AND matiere_id = :matiere_id AND periode_id = :periode_id";
        $sqlInsert = "INSERT INTO evaluations (inscription_id, matiere_id, periode_id, devoir1, devoir2, composition) VALUES (:inscription_id, :matiere_id, :periode_id, :devoir1, :devoir2, :composition)";
        $sqlUpdate = "UPDATE evaluations SET devoir1 = :devoir1, devoir2 = :devoir2, composition = :composition WHERE id = :id";

        foreach ($notes as $note) {
            $inscriptionId = (int) $note['inscription_id'];
            $d1 = isset($note['devoir1']) && $note['devoir1'] !== '' && $note['devoir1'] !== null ? (float) $note['devoir1'] : 0.0;
            $d2 = isset($note['devoir2']) && $note['devoir2'] !== '' && $note['devoir2'] !== null ? (float) $note['devoir2'] : 0.0;
            $comp = isset($note['composition']) && $note['composition'] !== '' && $note['composition'] !== null ? (float) $note['composition'] : 0.0;

            $existing = executeQuery($pdo, $sqlCheck, [
                'inscription_id' => $inscriptionId,
                'matiere_id' => $matiereId,
                'periode_id' => $periodeId
            ], true);

            if (!empty($existing) && isset($existing['id'])) {
                executeUpdate($pdo, $sqlUpdate, [
                    'id' => $existing['id'],
                    'devoir1' => $d1,
                    'devoir2' => $d2,
                    'composition' => $comp
                ]);
            } else {
                executeUpdate($pdo, $sqlInsert, [
                    'inscription_id' => $inscriptionId,
                    'matiere_id' => $matiereId,
                    'periode_id' => $periodeId,
                    'devoir1' => $d1,
                    'devoir2' => $d2,
                    'composition' => $comp
                ]);
            }
        }

        $pdo->commit();
        return true;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        return false;
    }
}

function calculer_moyenne_eleve(mixed $d1, mixed $d2, mixed $comp): float {
    $v1 = ($d1 !== null && $d1 !== '') ? (float)$d1 : 0.0;
    $v2 = ($d2 !== null && $d2 !== '') ? (float)$d2 : 0.0;
    $vc = ($comp !== null && $comp !== '') ? (float)$comp : 0.0;

    return round(($v1 + $v2 + 2.0 * $vc) / 4.0, 2);
}

function get_appreciation_note(?float $moyenne): array {
    if ($moyenne === null) return ['label' => 'Insuffisant', 'cls' => 'low'];
    if ($moyenne >= 16) return ['label' => 'Très bien', 'cls' => ''];
    if ($moyenne >= 14) return ['label' => 'Bien', 'cls' => ''];
    if ($moyenne >= 12) return ['label' => 'Assez bien', 'cls' => ''];
    if ($moyenne >= 10) return ['label' => 'Passable', 'cls' => 'mid'];
    return ['label' => 'Insuffisant', 'cls' => 'low'];
}