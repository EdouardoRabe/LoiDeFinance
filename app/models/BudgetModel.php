<?php

namespace app\models;

class BudgetModel
{
    private $pdo; // PDO or a PDO-like wrapper

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getYears(): array
    {
        $stmt = $this->pdo->query("SELECT id, annee FROM annee ORDER BY annee ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getKpis(int $annee): array
    {
        // Recettes par type (filtrées par année via jointure)
        $stmt = $this->pdo->prepare("SELECT r.type, SUM(r.montant) AS total
                                      FROM recette r
                                      JOIN annee a ON a.id = r.id_annee
                                      WHERE a.annee = ?
                                      GROUP BY r.type");
        $stmt->execute([$annee]);
        $recettes = [];
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $recettes[$row['type']] = (float)$row['total'];
        }

        // Dépenses totales
        $stmt = $this->pdo->prepare("SELECT SUM(d.montant) AS total
                                      FROM depense d
                                      JOIN annee a ON a.id = d.id_annee
                                      WHERE a.annee = ?");
        $stmt->execute([$annee]);
        $depensesTotal = (float)($stmt->fetchColumn() ?: 0);

        // Déficit
        $stmt = $this->pdo->prepare("SELECT df.montant_total, df.financement_exterieur, df.financement_interieur
                                      FROM deficit df
                                      JOIN annee a ON a.id = df.id_annee
                                      WHERE a.annee = ?
                                      LIMIT 1");
        $stmt->execute([$annee]);
        $deficit = $stmt->fetch(\PDO::FETCH_ASSOC) ?: [
            'montant_total' => null,
            'financement_exterieur' => null,
            'financement_interieur' => null,
        ];

        return [
            'recettes' => $recettes,
            'depenses' => $depensesTotal,
            'deficit' => $deficit,
        ];
    }

    public function getRecettes(int $annee, ?string $type = null): array
    {
        if ($type) {
            $sql = "SELECT cr.id, cr.nom, cr.description, r.montant, r.type
                    FROM recette r
                    JOIN categorie_recette cr ON cr.id = r.id_categorie
            JOIN annee a ON a.id = r.id_annee
            WHERE a.annee = ? AND r.type = ?
                    ORDER BY cr.id";
            $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annee, $type]);
        } else {
            $sql = "SELECT cr.id, cr.nom, cr.description, r.montant, r.type
                    FROM recette r
                    JOIN categorie_recette cr ON cr.id = r.id_categorie
            JOIN annee a ON a.id = r.id_annee
            WHERE a.annee = ?
                    ORDER BY r.type, cr.id";
            $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annee]);
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDepenses(int $annee, ?string $type = null): array
    {
        if ($type) {
            $sql = "SELECT cd.id, cd.nom, cd.description, d.montant, d.type
                    FROM depense d
                    JOIN categorie_depense cd ON cd.id = d.id_categorie
            JOIN annee a ON a.id = d.id_annee
            WHERE a.annee = ? AND d.type = ?
                    ORDER BY cd.id";
            $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annee, $type]);
        } else {
            $sql = "SELECT cd.id, cd.nom, cd.description, d.montant, d.type
                    FROM depense d
                    JOIN categorie_depense cd ON cd.id = d.id_categorie
            JOIN annee a ON a.id = d.id_annee
            WHERE a.annee = ?
                    ORDER BY d.type, cd.id";
            $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$annee]);
        }
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDispositions(int $annee): array
    {
        $stmt = $this->pdo->prepare("SELECT df.type, df.description
                                      FROM disposition_fiscale df
                                      JOIN annee a ON a.id = df.id_annee
                                      WHERE a.annee = ?
                                      ORDER BY df.type");
        $stmt->execute([$annee]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecetteTypes(int $annee): array
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT r.type
                                      FROM recette r
                                      JOIN annee a ON a.id = r.id_annee
                                      WHERE a.annee = ?
                                      ORDER BY r.type");
        $stmt->execute([$annee]);
        return array_map(function($row){ return $row['type']; }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getDepenseTypes(int $annee): array
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT d.type
                                      FROM depense d
                                      JOIN annee a ON a.id = d.id_annee
                                      WHERE a.annee = ?
                                      ORDER BY d.type");
        $stmt->execute([$annee]);
        return array_map(function($row){ return $row['type']; }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function getRecettesCompare(int $annee1, int $annee2, ?string $type = null): array
    {
        $params = [$annee1, $annee2];
        $typeFilter = '';
        if ($type) { $typeFilter = ' AND r.type = ?'; $params[] = $type; }
        $sql = "SELECT cr.id,
                       cr.nom,
                       cr.description,
                       r.type,
                       SUM(CASE WHEN a.annee = ? THEN r.montant ELSE 0 END) AS montant1,
                       SUM(CASE WHEN a.annee = ? THEN r.montant ELSE 0 END) AS montant2
                FROM recette r
                JOIN categorie_recette cr ON cr.id = r.id_categorie
                JOIN annee a ON a.id = r.id_annee
                WHERE a.annee IN (?, ?)" . $typeFilter . "
                GROUP BY cr.id, cr.nom, cr.description, r.type
                ORDER BY r.type, cr.id";
        // Note: parameters order must match placeholders: first for CASEs, then IN list, then optional type
        $execParams = [$annee1, $annee2, $annee1, $annee2];
        if ($type) { $execParams[] = $type; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($execParams);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDepensesCompare(int $annee1, int $annee2, ?string $type = null): array
    {
        $typeFilter = '';
        if ($type) { $typeFilter = ' AND d.type = ?'; }
        $sql = "SELECT cd.id,
                       cd.nom,
                       cd.description,
                       d.type,
                       SUM(CASE WHEN a.annee = ? THEN d.montant ELSE 0 END) AS montant1,
                       SUM(CASE WHEN a.annee = ? THEN d.montant ELSE 0 END) AS montant2
                FROM depense d
                JOIN categorie_depense cd ON cd.id = d.id_categorie
                JOIN annee a ON a.id = d.id_annee
                WHERE a.annee IN (?, ?)" . $typeFilter . "
                GROUP BY cd.id, cd.nom, cd.description, d.type
                ORDER BY d.type, cd.id";
        $execParams = [$annee1, $annee2, $annee1, $annee2];
        if ($type) { $execParams[] = $type; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($execParams);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecettesCompareMulti(array $annees, ?string $type = null): array
    {
        if (empty($annees)) { return []; }
        $placeholders = implode(',', array_fill(0, count($annees), '?'));
        $sql = "SELECT cr.id, cr.nom, cr.description, r.type, a.annee, SUM(r.montant) AS montant
                FROM recette r
                JOIN categorie_recette cr ON cr.id = r.id_categorie
                JOIN annee a ON a.id = r.id_annee
                WHERE a.annee IN ($placeholders)" . ($type ? " AND r.type = ?" : "") . "
                GROUP BY cr.id, cr.nom, cr.description, r.type, a.annee
                ORDER BY r.type, cr.id, a.annee";
        $params = array_values($annees);
        if ($type) { $params[] = $type; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDepensesCompareMulti(array $annees, ?string $type = null): array
    {
        if (empty($annees)) { return []; }
        $placeholders = implode(',', array_fill(0, count($annees), '?'));
        $sql = "SELECT cd.id, cd.nom, cd.description, d.type, a.annee, SUM(d.montant) AS montant
                FROM depense d
                JOIN categorie_depense cd ON cd.id = d.id_categorie
                JOIN annee a ON a.id = d.id_annee
                WHERE a.annee IN ($placeholders)" . ($type ? " AND d.type = ?" : "") . "
                GROUP BY cd.id, cd.nom, cd.description, d.type, a.annee
                ORDER BY d.type, cd.id, a.annee";
        $params = array_values($annees);
        if ($type) { $params[] = $type; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAnneeIdByYear(int $annee): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM annee WHERE annee = ? LIMIT 1");
        $stmt->execute([$annee]);
        $id = $stmt->fetchColumn();
        return $id ? (int)$id : null;
    }
}
