<?php

require_once __DIR__ . '/Role.php';

class Utilisateur {
    private ?int $id = null;
    private string $nom = '';
    private string $prenom = '';
    private string $telephone = '';
    private string $email = '';
    private ?string $password = null;
    private ?int $roleId = null;

    private ?Role $role = null;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        string $prenom = '',
        string $telephone = '',
        string $email = '',
        ?string $password = null,
        ?int $roleId = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->email = $email;
        $this->password = $password;
        $this->roleId = $roleId;
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

    public function getTelephone(): string {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self {
        $this->telephone = $telephone;
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(?string $password): self {
        $this->password = $password;
        return $this;
    }

    public function getRoleId(): ?int {
        return $this->roleId;
    }

    public function setRoleId(?int $roleId): self {
        $this->roleId = $roleId;
        return $this;
    }

    public function getRole(): ?Role {
        return $this->role;
    }

    public function setRole(?Role $role): self {
        $this->role = $role;
        if ($role !== null) {
            $this->roleId = $role->getId();
        }
        return $this;
    }

    public function getNomRole(): string {
        return $this->role !== null ? $this->role->getNomRole() : '';
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
        $roleId = isset($data['role_id']) ? (int)$data['role_id'] : (isset($data['roleId']) ? (int)$data['roleId'] : null);
        
        $user = new self(
            isset($data['id']) ? (int)$data['id'] : (isset($data['utilisateur_id']) ? (int)$data['utilisateur_id'] : null),
            $data['nom'] ?? '',
            $data['prenom'] ?? '',
            $data['telephone'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? null,
            $roleId
        );

        if (isset($data['nomrole']) || isset($data['nomRole']) || isset($data['nom_role'])) {
            $user->setRole(Role::fromArray($data));
        }

        return $user;
    }

    public function toArray(bool $includePassword = false): array {
        $array = [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'role_id' => $this->roleId,
            'nomrole' => $this->getNomRole()
        ];

        if ($includePassword) {
            $array['password'] = $this->password;
        }

        return $array;
    }
}
