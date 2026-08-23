<?php

require_once __DIR__ . '/AnneeScolaire.php';
require_once __DIR__ . '/Eleve.php';
require_once __DIR__ . '/Classe.php';

class Inscription {
    private ?int $id = null;
    private ?int $anneeId = null;
    private ?int $eleveId = null;
    private ?int $classeId = null;

    private ?AnneeScolaire $anneeScolaire = null;
    private ?Eleve $eleve = null;
    private ?Classe $classe = null;

    public function __construct(
        ?int $id = null,
        ?int $anneeId = null,
        ?int $eleveId = null,
        ?int $classeId = null
    ) {
        $this->id = $id;
        $this->anneeId = $anneeId;
        $this->eleveId = $eleveId;
        $this->classeId = $classeId;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getAnneeId(): ?int {
        return $this->anneeId;
    }

    public function setAnneeId(?int $anneeId): self {
        $this->anneeId = $anneeId;
        return $this;
    }

    public function getEleveId(): ?int {
        return $this->eleveId;
    }

    public function setEleveId(?int $eleveId): self {
        $this->eleveId = $eleveId;
        return $this;
    }

    public function getClasseId(): ?int {
        return $this->classeId;
    }

    public function setClasseId(?int $classeId): self {
        $this->classeId = $classeId;
        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire {
        return $this->anneeScolaire;
    }

    public function setAnneeScolaire(?AnneeScolaire $anneeScolaire): self {
        $this->anneeScolaire = $anneeScolaire;
        if ($anneeScolaire !== null) {
            $this->anneeId = $anneeScolaire->getId();
        }
        return $this;
    }

    public function getEleve(): ?Eleve {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self {
        $this->eleve = $eleve;
        if ($eleve !== null) {
            $this->eleveId = $eleve->getId();
        }
        return $this;
    }

    public function getClasse(): ?Classe {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self {
        $this->classe = $classe;
        if ($classe !== null) {
            $this->classeId = $classe->getId();
        }
        return $this;
    }

    public static function fromArray(array $data): self {
        $anneeId = isset($data['annee_id']) ? (int)$data['annee_id'] : (isset($data['anneeId']) ? (int)$data['anneeId'] : null);
        $eleveId = isset($data['eleve_id']) ? (int)$data['eleve_id'] : (isset($data['eleveId']) ? (int)$data['eleveId'] : null);
        $classeId = isset($data['classe_id']) ? (int)$data['classe_id'] : (isset($data['classeId']) ? (int)$data['classeId'] : null);

        $inscription = new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['inscription_id']) ? (int)$data['inscription_id'] : null),
            $anneeId,
            $eleveId,
            $classeId
        );

        if (isset($data['matricule']) || isset($data['nom']) || isset($data['prenom'])) {
            $inscription->setEleve(Eleve::fromArray($data));
        }

        if (isset($data['nomclasse']) || isset($data['nomClasse']) || isset($data['nom_classe'])) {
            $inscription->setClasse(Classe::fromArray($data));
        }

        return $inscription;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'annee_id' => $this->anneeId,
            'eleve_id' => $this->eleveId,
            'classe_id' => $this->classeId
        ];
    }
}
