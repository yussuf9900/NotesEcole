<?php

require_once __DIR__ . '/Classe.php';
require_once __DIR__ . '/Matiere.php';

class MatiereClasse {
    private ?int $id = null;
    private ?int $classeId = null;
    private ?int $matiereId = null;

    private ?Classe $classe = null;
    private ?Matiere $matiere = null;

    public function __construct(
        ?int $id = null,
        ?int $classeId = null,
        ?int $matiereId = null
    ) {
        $this->id = $id;
        $this->classeId = $classeId;
        $this->matiereId = $matiereId;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getClasseId(): ?int {
        return $this->classeId;
    }

    public function setClasseId(?int $classeId): self {
        $this->classeId = $classeId;
        return $this;
    }

    public function getMatiereId(): ?int {
        return $this->matiereId;
    }

    public function setMatiereId(?int $matiereId): self {
        $this->matiereId = $matiereId;
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

    public static function fromArray(array $data): self {
        $classeId = isset($data['classe_id']) ? (int)$data['classe_id'] : (isset($data['classeId']) ? (int)$data['classeId'] : null);
        $matiereId = isset($data['matiere_id']) ? (int)$data['matiere_id'] : (isset($data['matiereId']) ? (int)$data['matiereId'] : null);

        $matiereClasse = new self(
            isset($data['id']) ? (int)$data['id'] : null,
            $classeId,
            $matiereId
        );

        if (isset($data['nomclasse']) || isset($data['nomClasse']) || isset($data['nom_classe'])) {
            $matiereClasse->setClasse(Classe::fromArray($data));
        }

        if (isset($data['nommatiere']) || isset($data['nomMatiere']) || isset($data['nom_matiere'])) {
            $matiereClasse->setMatiere(Matiere::fromArray($data));
        }

        return $matiereClasse;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'classe_id' => $this->classeId,
            'matiere_id' => $this->matiereId
        ];
    }
}
