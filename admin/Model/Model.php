<?php
namespace Admin\Model;

use Admin\Database\Db;

class Model extends Db

// Classe permettant de traiter les données d'une table
// Classe CRUD (CREATE READ UPDATE DELETE)

{
    // Table de la BDD
    protected ?string $table = null;

    // Instance de connexion de la BDD
    private ?Db $db = null;

    public function request(string $querySQL, array $attributes = [])
    {
        // On récupère l'instance de la DB
        $this->db = Db::getInstance();

        if (!empty($attributes)) {
            $query = $this->db->prepare($querySQL);
            $query->execute($attributes);

            return $query->fetch();
        }

        return $this->db->query($querySQL);
    }

    /**
     * Retrouver l'ensemble des données d'une table
     *
     * @return array
     */
    public function findAll(): array
    {
        $query = $this->request("SELECT * FROM {$this->table}");

        return $query->fetchAll();
    }

    public function find(int $id, string $keyID = "id")
    {
        return $this
            ->request("SELECT * FROM {$this->table} WHERE $keyID = $id")
            ->fetch();
    }

    public function findBy(array $parameters = [])
    {
        $columns = [];
        $values = [];

        foreach ($parameters as $column => $value) {
            $columns[] = "$column = ?";
            $values[] = $value;
        }

        $columnsList = implode(' AND ', $columns);

        // On exécute la requête
        return $this->request("SELECT * FROM {$this->table} WHERE $columnsList", $values);
    }

    public function create(Model $model)
    {
        $columns = [];
        $labelsValue = [];
        $values = [];

        foreach ($model as $column => $value) {
            if ($column !== "db" && $column !== "table") {
                $columns[] = $column;
                $labelsValue[] = "?";
                $values[] = $value;
            }
        }

        $columnsList = implode(', ', $columns);
        $labelsValueList = implode(', ', $labelsValue);

        return $this->request("INSERT INTO {$this->table} ({$columnsList}) VALUES ($labelsValueList)", $values);
    }

    public function update($id, Model $model): bool|\PDOStatement
    {
        $values = [$id];
        $conditions = [];

        foreach ($model as $column => $value) {
            if ($column !== "db" && $column !== "table") {
                $values[] = $value;
                $conditions[] = $column . " = ?";
            }
        }

        $conditionsList = implode(", ", $conditions);

        return $this->request("UPDATE {$this->table} SET {$conditionsList} WHERE id = ?", $values);
    }

    public function delete(int $id): bool|\PDOStatement
    {
        $values[] = $id;
        return $this->request("DELETE FROM {$this->table} WHERE id = ?", $values);
    }

    public function hydrate(array $data): self
    {
        foreach ($data as $key => $value) {
            // Récupérer le nom du setter qui correspond à l'attribut en question.
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }
}
