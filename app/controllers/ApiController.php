<?php

namespace app\controllers;

use Flight;

class ApiController
{
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
        Flight::json($data);
    }

    public static function recettes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null; // fiscale|douanière|non fiscale|don
        $rows = Flight::budgetModel()->getRecettes($annee, $type);
        Flight::json($rows);
    }

    public static function depenses()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null; // fonctionnement|investissement|dette|administratif
        $rows = Flight::budgetModel()->getDepenses($annee, $type);
        Flight::json($rows);
    }

    public static function dispositions()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getDispositions($annee);
        Flight::json($rows);
    }

    public static function recetteTypes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getRecetteTypes($annee);
        Flight::json($rows);
    }

    public static function depenseTypes()
    {
        $annee = (int)(Flight::request()->query['annee'] ?? 2025);
        $rows = Flight::budgetModel()->getDepenseTypes($annee);
        Flight::json($rows);
    }

    public static function recettesCompare()
    {
        $annee1 = (int)(Flight::request()->query['annee1'] ?? 2024);
        $annee2 = (int)(Flight::request()->query['annee2'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getRecettesCompare($annee1, $annee2, $type);
        Flight::json($rows);
    }

    public static function depensesCompare()
    {
        $annee1 = (int)(Flight::request()->query['annee1'] ?? 2024);
        $annee2 = (int)(Flight::request()->query['annee2'] ?? 2025);
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getDepensesCompare($annee1, $annee2, $type);
        Flight::json($rows);
    }

    public static function recettesCompareMulti()
    {
        $annees = Flight::request()->query['annee'] ?? [];
        if (!is_array($annees)) { $annees = [$annees]; }
        $annees = array_values(array_unique(array_map('intval', $annees)));
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getRecettesCompareMulti($annees, $type);
        Flight::json($rows);
    }

    public static function depensesCompareMulti()
    {
        $annees = Flight::request()->query['annee'] ?? [];
        if (!is_array($annees)) { $annees = [$annees]; }
        $annees = array_values(array_unique(array_map('intval', $annees)));
        $type = Flight::request()->query['type'] ?? null;
        $rows = Flight::budgetModel()->getDepensesCompareMulti($annees, $type);
        Flight::json($rows);
    }
}
