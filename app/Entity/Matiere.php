<?php

class Matiere {
    private ?int $id = null;
    private string $nomMatiere = '';

    public function __construct(?int $id = null, string $nomMatiere = '') {
        $this->id = $id;
        $this->nomMatiere = $nomMatiere;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNomMatiere(): string {
        return $this->nomMatiere;
    }

    public function setNomMatiere(string $nomMatiere): self {
        $this->nomMatiere = $nomMatiere;
        return $this;
    }

    public static function fromArray(array $data): self {
        $nomMatiere = $data['nommatiere'] ?? $data['nomMatiere'] ?? $data['nom_matiere'] ?? '';
        return new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['matiere_id']) ? (int)$data['matiere_id'] : null),
            $nomMatiere
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nomMatiere' => $this->nomMatiere
        ];
    }
}
