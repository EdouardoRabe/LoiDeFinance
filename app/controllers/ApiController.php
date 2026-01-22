<?php

namespace app\controllers;

use Flight;

class ApiController
{
    private static function v($s)
    {
        if (!is_string($s)) return $s;
        if (function_exists('current_lang') && current_lang() === 'mg') {
            static $map = null;
            if ($map === null) {
                $file = __DIR__ . '/../lang/value_map/mg.php';
                $map = file_exists($file) ? (require $file) : [];
            }
            return $map[$s] ?? $s;
        }
        return $s;
    }
    public static function ping()
    {
        Flight::json(['ok' => true, 'time' => date('c')]);
    }

    public static function health()
    {
        try {
            $db = Flight::bdd();
            $years = (int)($db->query('SELECT COUNT(*) FROM annee')->fetchColumn() ?: 0);
            $recettes = (int)($db->query('SELECT COUNT(*) FROM recette')->fetchColumn() ?: 0);
            $depenses = (int)($db->query('SELECT COUNT(*) FROM depense')->fetchColumn() ?: 0);
            Flight::json(['ok' => true, 'years' => $years, 'recettes' => $recettes, 'depenses' => $depenses]);
        } catch (\Throwable $e) {
            Flight::json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public static function years()
    {
        $years = Flight::budgetModel()->getYears();
        Flight::json($years);
    }

    public static function kpis()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $data = Flight::budgetModel()->getKpis($annee);
        if (isset($data['recettes']) && is_array($data['recettes'])) {
            $out = [];
            foreach ($data['recettes'] as $k => $v) { $out[self::v($k)] = $v; }
            $data['recettes'] = $out;
        }
        Flight::json($data);
    }

    public static function recettes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null; // fiscale|douanière|non fiscale|don
        $rows = Flight::budgetModel()->getRecettes($annee, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function depenses()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null; // fonctionnement|investissement|dette|administratif
        $rows = Flight::budgetModel()->getDepenses($annee, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function dispositions()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getDispositions($annee);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function recetteTypes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getRecetteTypes($annee);
        $out = [];
        foreach ($rows as $val) { $out[] = ['value' => $val, 'label' => self::v($val)]; }
        Flight::json($out);
    }

    public static function depenseTypes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getDepenseTypes($annee);
        $out = [];
        foreach ($rows as $val) { $out[] = ['value' => $val, 'label' => self::v($val)]; }
        Flight::json($out);
    }

    public static function recettesCompare()
    {
        $annee1 = (int)(Flight::request()->query['annee1'] ?? 2024);
        $annee2 = (int)(Flight::request()->query['annee2'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getRecettesCompare($annee1, $annee2, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function depensesCompare()
    {
        $annee1 = (int)(Flight::request()->query['annee1'] ?? 2024);
        $annee2 = (int)(Flight::request()->query['annee2'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getDepensesCompare($annee1, $annee2, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function recettesCompareMulti()
    {
        $annees = Flight::request()->query['annee'] ?? [];
        if (!is_array($annees)) { $annees = [$annees]; }
        $annees = array_values(array_unique(array_map('intval', $annees)));
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getRecettesCompareMulti($annees, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function depensesCompareMulti()
    {
        $annees = Flight::request()->query['annee'] ?? [];
        if (!is_array($annees)) { $annees = [$annees]; }
        $annees = array_values(array_unique(array_map('intval', $annees)));
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getDepensesCompareMulti($annees, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    // ==========================
    // Extensions API (schéma étendu)
    // ==========================

    public static function secteurs()
    {
        $parentId = Flight::request()->query['parent_id'] ?? null;
        $parentId = $parentId !== null && $parentId !== '' ? (int)$parentId : null;
        $rows = Flight::budgetModel()->getSecteurs($parentId);
        foreach ($rows as &$r) { $r['nom'] = self::v($r['nom']); $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function croissanceSecteur()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $secteurId = Flight::request()->query['secteur_id'] ?? null;
        $secteurId = $secteurId !== null && $secteurId !== '' ? (int)$secteurId : null;
        $rows = Flight::budgetModel()->getCroissanceSecteur($annee, $secteurId);
        foreach ($rows as &$r) { $r['secteur'] = self::v($r['secteur']); $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function projets()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $secteur = Flight::request()->query['secteur'] ?? null;
        $source = Flight::request()->query['source'] ?? null; // interne|externe|mixte
        $categorie = Flight::request()->query['categorie'] ?? null;
        $categorie = $categorie ? (int)$categorie : null;
        $q = Flight::request()->query['q'] ?? null;
        $sort = Flight::request()->query['sort'] ?? null; // nom|montant|source_financement|secteur
        $rows = Flight::budgetModel()->getProjetsInvestissement($annee, $secteur, $source, $categorie, $q, $sort);
        foreach ($rows as &$r) { $r['secteur'] = self::v($r['secteur']); $r['source_financement'] = self::v($r['source_financement']); }
        Flight::json($rows);
    }

    public static function dette()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null; // interieure|exterieure
        $rows = Flight::budgetModel()->getDette($annee, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function compareDette()
    {
        $annee1 = (int)(Flight::request()->query['annee1'] ?? 2024);
        $annee2 = (int)(Flight::request()->query['annee2'] ?? 2025);
        $rows = Flight::budgetModel()->compareDette($annee1, $annee2);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function postes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $categorie = Flight::request()->query['categorie'] ?? null;
        $categorie = $categorie ? (int)$categorie : null;
        $rows = Flight::budgetModel()->getPostesBudgetaires($annee, $categorie);
        foreach ($rows as &$r) { $r['categorie'] = self::v($r['categorie']); }
        Flight::json($rows);
    }

    public static function indicateurs()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $q = Flight::request()->query['q'] ?? null;
        $rows = Flight::budgetModel()->getIndicateursMacro($annee, $q);
        foreach ($rows as &$r) { $r['nom'] = self::v($r['nom']); $r['unite'] = self::v($r['unite']); }
        Flight::json($rows);
    }

    public static function indicateursSerie()
    {
        $annees = Flight::request()->query['annee'] ?? [];
        if (!is_array($annees)) { $annees = [$annees]; }
        $annees = array_values(array_unique(array_map('intval', $annees)));
        $noms = Flight::request()->query['nom'] ?? [];
        if (!is_array($noms)) { $noms = [$noms]; }
        $rows = Flight::budgetModel()->getIndicateursSerie($noms, $annees);
        foreach ($rows as &$r) { $r['nom'] = self::v($r['nom']); $r['unite'] = self::v($r['unite']); }
        Flight::json($rows);
    }

    public static function glossaire()
    {
        $q = Flight::request()->query['q'] ?? null;
        $type = Flight::request()->query['type'] ?? null; // acronyme|terme
        $rows = Flight::budgetModel()->getGlossaire($q, $type);
        foreach ($rows as &$r) { $r['type'] = self::v($r['type']); }
        Flight::json($rows);
    }

    public static function searchRecettes()
    {
        $annee = Flight::request()->query['annee'] ?? null; $annee = $annee !== null && $annee !== '' ? (int)$annee : null;
        $type = Flight::request()->query['type'] ?? null;
        $categorie = Flight::request()->query['categorie'] ?? null; $categorie = $categorie ? (int)$categorie : null;
        $q = Flight::request()->query['q'] ?? null;
        $sort = Flight::request()->query['sort'] ?? null;
        $rows = Flight::budgetModel()->searchRecettes($annee, $type, $categorie, $q, $sort);
        Flight::json($rows);
    }

    public static function searchDepenses()
    {
        $annee = Flight::request()->query['annee'] ?? null; $annee = $annee !== null && $annee !== '' ? (int)$annee : null;
        $type = Flight::request()->query['type'] ?? null;
        $categorie = Flight::request()->query['categorie'] ?? null; $categorie = $categorie ? (int)$categorie : null;
        $q = Flight::request()->query['q'] ?? null;
        $sort = Flight::request()->query['sort'] ?? null;
        $rows = Flight::budgetModel()->searchDepenses($annee, $type, $categorie, $q, $sort);
        Flight::json($rows);
    }
}
