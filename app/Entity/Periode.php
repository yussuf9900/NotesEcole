<?php

class Periode {
    private ?int $id = null;
    private string $nomPeriode = '';

    public function __construct(?int $id = null, string $nomPeriode = '') {
        $this->id = $id;
        $this->nomPeriode = $nomPeriode;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNomPeriode(): string {
        return $this->nomPeriode;
    }

    public function setNomPeriode(string $nomPeriode): self {
        $this->nomPeriode = $nomPeriode;
        return $this;
    }

    public static function fromArray(array $data): self {
        $nomPeriode = $data['nomperiode'] ?? $data['nomPeriode'] ?? $data['nom_periode'] ?? '';
        return new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['periode_id']) ? (int)$data['periode_id'] : null),
            $nomPeriode
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nomPeriode' => $this->nomPeriode
        ];
    }
}
