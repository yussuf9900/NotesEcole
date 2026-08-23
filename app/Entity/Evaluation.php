<?php

require_once __DIR__ . '/Inscription.php';
require_once __DIR__ . '/Matiere.php';
require_once __DIR__ . '/Periode.php';

class Evaluation {
    private ?int $id = null;
    private ?int $inscriptionId = null;
    private ?int $matiereId = null;
    private ?int $periodeId = null;
    private ?float $devoir1 = null;
    private ?float $devoir2 = null;
    private ?float $composition = null;

    private ?Inscription $inscription = null;
    private ?Matiere $matiere = null;
    private ?Periode $periode = null;

    public function __construct(
        ?int $id = null,
        ?int $inscriptionId = null,
        ?int $matiereId = null,
        ?int $periodeId = null,
        ?float $devoir1 = null,
        ?float $devoir2 = null,
        ?float $composition = null
    ) {
        $this->id = $id;
        $this->inscriptionId = $inscriptionId;
        $this->matiereId = $matiereId;
        $this->periodeId = $periodeId;
        $this->devoir1 = $devoir1;
        $this->devoir2 = $devoir2;
        $this->composition = $composition;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getInscriptionId(): ?int {
        return $this->inscriptionId;
    }

    public function setInscriptionId(?int $inscriptionId): self {
        $this->inscriptionId = $inscriptionId;
        return $this;
    }

    public function getMatiereId(): ?int {
        return $this->matiereId;
    }

    public function setMatiereId(?int $matiereId): self {
        $this->matiereId = $matiereId;
        return $this;
    }

    public function getPeriodeId(): ?int {
        return $this->periodeId;
    }

    public function setPeriodeId(?int $periodeId): self {
        $this->periodeId = $periodeId;
        return $this;
    }

    public function getDevoir1(): ?float {
        return $this->devoir1;
    }

    public function setDevoir1(?float $devoir1): self {
        $this->devoir1 = $devoir1;
        return $this;
    }

    public function getDevoir2(): ?float {
        return $this->devoir2;
    }

    public function setDevoir2(?float $devoir2): self {
        $this->devoir2 = $devoir2;
        return $this;
    }

    public function getComposition(): ?float {
        return $this->composition;
    }

    public function setComposition(?float $composition): self {
        $this->composition = $composition;
        return $this;
    }

    public function getInscription(): ?Inscription {
        return $this->inscription;
    }

    public function setInscription(?Inscription $inscription): self {
        $this->inscription = $inscription;
        if ($inscription !== null) {
            $this->inscriptionId = $inscription->getId();
        }
        return $this;
    }

    public function getMatiere(): ?Matiere {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self {
        $this->matiere = $matiere;
        if ($matiere !== null) {
            $this->matiereId = $matiere->getId();
        }
        return $this;
    }

    public function getPeriode(): ?Periode {
        return $this->periode;
    }

    public function setPeriode(?Periode $periode): self {
        $this->periode = $periode;
        if ($periode !== null) {
            $this->periodeId = $periode->getId();
        }
        return $this;
    }

    public function getMoyenne(): float {
        $d1 = $this->devoir1 ?? 0.0;
        $d2 = $this->devoir2 ?? 0.0;
        $comp = $this->composition ?? 0.0;

        return round(($d1 + $d2 + 2.0 * $comp) / 4.0, 2);
    }

    public function getAppreciation(): array {
        $moyenne = $this->getMoyenne();
        if ($moyenne >= 16) return ['label' => 'Très bien', 'cls' => ''];
        if ($moyenne >= 14) return ['label' => 'Bien', 'cls' => ''];
        if ($moyenne >= 12) return ['label' => 'Assez bien', 'cls' => ''];
        if ($moyenne >= 10) return ['label' => 'Passable', 'cls' => 'mid'];
        return ['label' => 'Insuffisant', 'cls' => 'low'];
    }

    public static function fromArray(array $data): self {
        $inscriptionId = isset($data['inscription_id']) ? (int)$data['inscription_id'] : (isset($data['inscriptionId']) ? (int)$data['inscriptionId'] : null);
        $matiereId = isset($data['matiere_id']) ? (int)$data['matiere_id'] : (isset($data['matiereId']) ? (int)$data['matiereId'] : null);
        $periodeId = isset($data['periode_id']) ? (int)$data['periode_id'] : (isset($data['periodeId']) ? (int)$data['periodeId'] : null);

        $d1 = isset($data['devoir1']) && $data['devoir1'] !== '' && $data['devoir1'] !== null ? (float)$data['devoir1'] : 0.0;
        $d2 = isset($data['devoir2']) && $data['devoir2'] !== '' && $data['devoir2'] !== null ? (float)$data['devoir2'] : 0.0;
        $comp = isset($data['composition']) && $data['composition'] !== '' && $data['composition'] !== null ? (float)$data['composition'] : 0.0;

        $evaluation = new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['evaluation_id']) ? (int)$data['evaluation_id'] : null),
            $inscriptionId,
            $matiereId,
            $periodeId,
            $d1,
            $d2,
            $comp
        );

        if (isset($data['matricule']) || isset($data['nom']) || isset($data['prenom'])) {
            $evaluation->setInscription(Inscription::fromArray($data));
        }

        return $evaluation;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'inscription_id' => $this->inscriptionId,
            'matiere_id' => $this->matiereId,
            'periode_id' => $this->periodeId,
            'devoir1' => $this->devoir1,
            'devoir2' => $this->devoir2,
            'composition' => $this->composition
        ];
    }
}
