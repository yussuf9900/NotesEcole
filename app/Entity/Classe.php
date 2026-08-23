<?php

class Classe {
    private ?int $id = null;
    private string $nomClasse = '';

    public function __construct(?int $id = null, string $nomClasse = '') {
        $this->id = $id;
        $this->nomClasse = $nomClasse;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNomClasse(): string {
        return $this->nomClasse;
    }

    public function setNomClasse(string $nomClasse): self {
        $this->nomClasse = $nomClasse;
        return $this;
    }

    public static function fromArray(array $data): self {
        $nomClasse = $data['nomclasse'] ?? $data['nomClasse'] ?? $data['nom_classe'] ?? '';
        return new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['classe_id']) ? (int)$data['classe_id'] : null),
            $nomClasse
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nomClasse' => $this->nomClasse
        ];
    }
}
