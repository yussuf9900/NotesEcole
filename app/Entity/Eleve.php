<?php

class Eleve {
    private ?int $id = null;
    private string $nom = '';
    private string $prenom = '';
    private string $matricule = '';

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $prenom = '',
        string $matricule = ''
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->matricule = $matricule;
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

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self {
        $this->prenom = $prenom;
        return $this;
    }

    public function getMatricule(): string {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self {
        $this->matricule = $matricule;
        return $this;
    }

    public function getNomComplet(): string {
        return trim($this->prenom . ' ' . $this->nom);
    }

    public function getInitiales(): string {
        $p = !empty($this->prenom) ? substr($this->prenom, 0, 1) : '';
        $n = !empty($this->nom) ? substr($this->nom, 0, 1) : '';
        return strtoupper($p . $n);
    }

    public static function fromArray(array $data): self {
        return new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['eleve_id']) ? (int)$data['eleve_id'] : null),
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['matricule'] ?? ''
        );
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'matricule' => $this->matricule
        ];
    }
}
