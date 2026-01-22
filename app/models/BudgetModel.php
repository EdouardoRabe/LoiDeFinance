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


    // ==========================
    // Extensions pour schéma étendu (secteurs, croissance, projets, dette, postes, indicateurs, glossaire)
    // ==========================

    public function getSecteurs(?int $parentId = null): array
    {
        if ($parentId === null) {
            $stmt = $this->pdo->query("SELECT id, nom, type, parent_id, description FROM secteur WHERE parent_id IS NULL ORDER BY id");
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        $stmt = $this->pdo->prepare("SELECT id, nom, type, parent_id, description FROM secteur WHERE parent_id = ? ORDER BY id");
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCroissanceSecteur(int $annee, ?int $secteurId = null): array
    {
        $params = [$annee];
        $sql = "SELECT s.id AS secteur_id, s.nom AS secteur, s.type, s.parent_id,
                       cs.taux
                FROM croissance_secteur cs
                JOIN annee a ON a.id = cs.id_annee
                JOIN secteur s ON s.id = cs.id_secteur
                WHERE a.annee = ?";
        if ($secteurId !== null) {
            $sql .= " AND s.id = ?";
            $params[] = $secteurId;
        }
        $sql .= " ORDER BY COALESCE(s.parent_id, s.id), s.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProjetsInvestissement(
        int $annee,
        ?string $secteur = null,
        ?string $source = null,
        ?int $categorieDepenseId = null,
        ?string $search = null,
        ?string $sort = null
    ): array {
        $params = [$annee];
        $filters = [];
        if ($secteur) { $filters[] = 'pi.secteur = ?'; $params[] = $secteur; }
        if ($source) { $filters[] = 'pi.source_financement = ?'; $params[] = $source; }
        if ($categorieDepenseId) { $filters[] = 'pi.id_categorie_depense = ?'; $params[] = $categorieDepenseId; }
        if ($search) { $filters[] = '(pi.nom LIKE ? OR pi.description LIKE ?)'; $params[] = "%$search%"; $params[] = "%$search%"; }

        $sql = "SELECT pi.id, pi.nom, pi.description, pi.id_categorie_depense, pi.montant, pi.source_financement, pi.secteur
                FROM projet_investissement pi
                JOIN annee a ON a.id = pi.id_annee
                WHERE a.annee = ?";
        if (!empty($filters)) { $sql .= ' AND ' . implode(' AND ', $filters); }

        $allowedSort = ['nom', 'montant', 'source_financement', 'secteur'];
        if ($sort && in_array($sort, $allowedSort, true)) { $sql .= " ORDER BY pi.$sort"; }
        else { $sql .= " ORDER BY pi.secteur, pi.nom"; }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getDette(int $annee, ?string $type = null): array
    {
        $params = [$annee];
        $sql = "SELECT d.type, d.interets, d.principal, d.taux_moyen
                FROM dette d
                JOIN annee a ON a.id = d.id_annee
                WHERE a.annee = ?";
        if ($type) { $sql .= ' AND d.type = ?'; $params[] = $type; }
        $sql .= ' ORDER BY d.type';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPostesBudgetaires(int $annee, ?int $categorieDepenseId = null): array
    {
        $params = [$annee];
        $sql = "SELECT pb.id, cd.id AS categorie_id, cd.nom AS categorie, pb.nombre, pb.description
                FROM poste_budgetaire pb
                JOIN categorie_depense cd ON cd.id = pb.id_categorie_depense
                JOIN annee a ON a.id = pb.id_annee
                WHERE a.annee = ?";
        if ($categorieDepenseId) { $sql .= ' AND cd.id = ?'; $params[] = $categorieDepenseId; }
        $sql .= ' ORDER BY cd.nom';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getIndicateursMacro(int $annee, ?string $nomLike = null): array
    {
        $params = [$annee];
        $sql = "SELECT im.id, im.nom, im.valeur, im.unite
                FROM indicateur_macro im
                JOIN annee a ON a.id = im.id_annee
                WHERE a.annee = ?";
        if ($nomLike) { $sql .= ' AND im.nom LIKE ?'; $params[] = "%$nomLike%"; }
        $sql .= ' ORDER BY im.nom';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getIndicateursSerie(array $noms, array $annees): array
    {
        if (empty($noms) || empty($annees)) { return []; }
        $namePlaceholders = implode(',', array_fill(0, count($noms), '?'));
        $yearPlaceholders = implode(',', array_fill(0, count($annees), '?'));
        $params = array_merge($annees, $noms); // annes first for a.annee IN, then noms for im.nom IN
        $sql = "SELECT a.annee, im.nom, im.valeur, im.unite
                FROM indicateur_macro im
                JOIN annee a ON a.id = im.id_annee
                WHERE a.annee IN ($yearPlaceholders)
                  AND im.nom IN ($namePlaceholders)
                ORDER BY im.nom, a.annee";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getGlossaire(?string $q = null, ?string $type = null): array
    {
        $params = [];
        $sql = "SELECT id, terme, definition, type FROM glossaire WHERE 1=1";
        if ($q) { $sql .= ' AND (terme LIKE ? OR definition LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($type) { $sql .= ' AND type = ?'; $params[] = $type; }
        $sql .= ' ORDER BY type, terme';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listCategoriesRecette(): array
    {
        $stmt = $this->pdo->query("SELECT id, nom, description FROM categorie_recette ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function listCategoriesDepense(): array
    {
        $stmt = $this->pdo->query("SELECT id, nom, description FROM categorie_depense ORDER BY id");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchRecettes(
        ?int $annee = null,
        ?string $type = null,
        ?int $categorieId = null,
        ?string $q = null,
        ?string $sort = null
    ): array {
        $params = [];
        $sql = "SELECT a.annee, r.type, cr.id AS categorie_id, cr.nom AS categorie, r.montant
                FROM recette r
                JOIN categorie_recette cr ON cr.id = r.id_categorie
                JOIN annee a ON a.id = r.id_annee
                WHERE 1=1";
        if ($annee !== null) { $sql .= ' AND a.annee = ?'; $params[] = $annee; }
        if ($type) { $sql .= ' AND r.type = ?'; $params[] = $type; }
        if ($categorieId) { $sql .= ' AND cr.id = ?'; $params[] = $categorieId; }
        if ($q) { $sql .= ' AND (cr.nom LIKE ? OR cr.description LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        $allowed = ['annee','type','categorie','montant'];
        if ($sort && in_array($sort, $allowed, true)) { $sql .= " ORDER BY $sort"; }
        else { $sql .= ' ORDER BY a.annee, r.type, cr.id'; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function searchDepenses(
        ?int $annee = null,
        ?string $type = null,
        ?int $categorieId = null,
        ?string $q = null,
        ?string $sort = null
    ): array {
        $params = [];
        $sql = "SELECT a.annee, d.type, cd.id AS categorie_id, cd.nom AS categorie, d.montant
                FROM depense d
                JOIN categorie_depense cd ON cd.id = d.id_categorie
                JOIN annee a ON a.id = d.id_annee
                WHERE 1=1";
        if ($annee !== null) { $sql .= ' AND a.annee = ?'; $params[] = $annee; }
        if ($type) { $sql .= ' AND d.type = ?'; $params[] = $type; }
        if ($categorieId) { $sql .= ' AND cd.id = ?'; $params[] = $categorieId; }
        if ($q) { $sql .= ' AND (cd.nom LIKE ? OR cd.description LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        $allowed = ['annee','type','categorie','montant'];
        if ($sort && in_array($sort, $allowed, true)) { $sql .= " ORDER BY $sort"; }
        else { $sql .= ' ORDER BY a.annee, d.type, cd.id'; }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function compareDette(int $annee1, int $annee2): array
    {
        $sql = "SELECT d.type,
                       SUM(CASE WHEN a.annee = ? THEN d.interets ELSE 0 END) AS interets1,
                       SUM(CASE WHEN a.annee = ? THEN d.interets ELSE 0 END) AS interets2,
                       SUM(CASE WHEN a.annee = ? THEN d.principal ELSE 0 END) AS principal1,
                       SUM(CASE WHEN a.annee = ? THEN d.principal ELSE 0 END) AS principal2
                FROM dette d
                JOIN annee a ON a.id = d.id_annee
                WHERE a.annee IN (?, ?)
                GROUP BY d.type
                ORDER BY d.type";
        $params = [$annee1, $annee2, $annee1, $annee2, $annee1, $annee2];
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function compareCroissanceSecteur(int $annee1, int $annee2, ?int $secteurId = null): array
    {
        $params = [$annee1, $annee2, $annee1, $annee2];
        $sql = "SELECT s.id AS secteur_id, s.nom AS secteur, s.parent_id,
                       SUM(CASE WHEN a.annee = ? THEN cs.taux ELSE 0 END) AS taux1,
                       SUM(CASE WHEN a.annee = ? THEN cs.taux ELSE 0 END) AS taux2
                FROM croissance_secteur cs
                JOIN annee a ON a.id = cs.id_annee
                JOIN secteur s ON s.id = cs.id_secteur
                WHERE a.annee IN (?, ?)";
        if ($secteurId !== null) { $sql .= ' AND s.id = ?'; $params[] = $secteurId; }
        $sql .= " GROUP BY s.id, s.nom, s.parent_id ORDER BY COALESCE(s.parent_id, s.id), s.id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAnnees(): array
    {
        // alias plus lisible de getYears()
        return $this->getYears();
    }

}
