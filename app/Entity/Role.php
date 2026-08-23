<?php

class Role {
    private ?int $id = null;
    private string $nomRole = '';

    public function __construct(?int $id = null, string $nomRole = '') {
        $this->id = $id;
        $this->nomRole = $nomRole;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getNomRole(): string {
        return $this->nomRole;
    }

    public function setNomRole(string $nomRole): self {
        $this->nomRole = $nomRole;
        return $this;
    }

    public static function fromArray(array $data): self {
        $nomRole = $data['nomrole'] ?? $data['nomRole'] ?? $data['nom_role'] ?? '';
        return new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['role_id']) ? (int)$data['role_id'] : null),
            $nomRole
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nomRole' => $this->nomRole
        ];
    }
}
