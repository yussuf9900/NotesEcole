<?php

class AnneeScolaire {
    private ?int $id = null;
    private string $nom = '';
    private ?string $date = null;
    private int $actif = 0;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        ?string $date = null,
        int $actif = 0
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->date = $date;
        $this->actif = $actif;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): self {
        $this->nom = $nom;
        return $this;
    }

    public function getDate(): ?string {
        return $this->date;
    }

    public function setDate(?string $date): self {
        $this->date = $date;
        return $this;
    }

    public function getActif(): int {
        return $this->actif;
    }

    public function setActif(int $actif): self {
        $this->actif = $actif;
        return $this;
    }

    public function isActif(): bool {
        return $this->actif === 1;
    }

    public static function fromArray(array $data): self {
        return new self(
            isset($data['id']) ? (int)$data['id'] : null,
            $data['nom'] ?? '',
            $data['date'] ?? null,
            isset($data['actif']) ? (int)$data['actif'] : 0
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'date' => $this->date,
            'actif' => $this->actif
        ];
    }
}
