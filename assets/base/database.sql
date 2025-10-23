CREATE DATABASE IF NOT EXISTS loi_de_finance;
USE loi_de_finance;

-- Tables existantes (non modifiées, mais incluses pour contexte)
CREATE TABLE IF NOT EXISTS annee (
  id INT AUTO_INCREMENT PRIMARY KEY,
  annee YEAR NOT NULL,
  pib DECIMAL(15,2),
  taux_croissance DECIMAL(5,2),
  taux_inflation DECIMAL(5,2),
  taux_pression_fiscale DECIMAL(5,2)
);

CREATE TABLE IF NOT EXISTS categorie_recette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT NULL
);

CREATE TABLE IF NOT EXISTS categorie_depense (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT NULL
);

CREATE TABLE IF NOT EXISTS recette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_categorie INT NOT NULL,
  type ENUM('fiscale','douanière','non fiscale','don') NOT NULL,
  montant DECIMAL(15,2) NOT NULL,
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie) REFERENCES categorie_recette(id)
);

CREATE TABLE IF NOT EXISTS depense (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_categorie INT NOT NULL,
  type ENUM('fonctionnement','investissement','dette','administratif') NOT NULL,
  montant DECIMAL(15,2) NOT NULL,
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie) REFERENCES categorie_depense(id)
);

CREATE TABLE IF NOT EXISTS deficit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  montant_total DECIMAL(15,2) NOT NULL,
  financement_exterieur DECIMAL(15,2),
  financement_interieur DECIMAL(15,2),
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);

CREATE TABLE IF NOT EXISTS disposition_fiscale (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  type VARCHAR(100),
  description TEXT,
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);

-- Nouvelles tables ajoutées pour couvrir les données manquantes du document

-- ===========================
-- TABLE SECTEUR (pour gérer les secteurs et sous-secteurs économiques : primaire, secondaire, tertiaire, et leurs détails comme agriculture, industrie extractive, etc.)
-- ===========================
CREATE TABLE IF NOT EXISTS secteur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  type ENUM('primaire', 'secondaire', 'tertiaire') NULL, -- Pour classer les secteurs principaux
  parent_id INT NULL, -- Pour les sous-secteurs (référence à un secteur parent)
  description TEXT NULL,
  FOREIGN KEY (parent_id) REFERENCES secteur(id)
);

-- ===========================
-- TABLE CROISSANCE_SECTEUR (pour stocker les taux de croissance par secteur/sous-secteur et par année, y compris projections futures)
-- ===========================
CREATE TABLE IF NOT EXISTS croissance_secteur (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_secteur INT NOT NULL,
  taux DECIMAL(5,2) NOT NULL,
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_secteur) REFERENCES secteur(id)
);

-- ===========================
-- TABLE PROJET_INVESTISSEMENT (pour les détails des programmes d'investissements publics : PIP, projets spécifiques en énergie, agriculture, infrastructures, etc.)
-- ===========================
CREATE TABLE IF NOT EXISTS projet_investissement (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  nom VARCHAR(255) NOT NULL, -- Ex: "Mandraka III", "Pipeline Efaho"
  description TEXT NULL, -- Détails sur le projet
  id_categorie_depense INT NULL, -- Lien optionnel vers une catégorie de dépense (ex: ministère concerné)
  montant DECIMAL(15,2) NULL,
  source_financement ENUM('interne', 'externe', 'mixte') NOT NULL,
  secteur VARCHAR(150) NULL, -- Ex: "Énergie", "Agriculture", "Infrastructures"
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie_depense) REFERENCES categorie_depense(id)
);

-- ===========================
-- TABLE DETTE (pour les détails sur la dette : intérêts, principal, intérieure/extérieure)
-- ===========================
CREATE TABLE IF NOT EXISTS dette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  type ENUM('interieure', 'exterieure') NOT NULL,
  interets DECIMAL(15,2) NOT NULL,
  principal DECIMAL(15,2) NULL, -- Optionnel pour les projections
  taux_moyen DECIMAL(5,2) NULL, -- Ex: taux d'intérêt moyen pondéré
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);

-- ===========================
-- TABLE POSTE_BUDGETAIRE (pour les postes budgétaires autorisés : emplois créés par ministère/institution)
-- ===========================
CREATE TABLE IF NOT EXISTS poste_budgetaire (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_categorie_depense INT NOT NULL, -- Lien vers le ministère/institution
  nombre INT NOT NULL, -- Nombre de postes (ex: 3000 pour Éducation Nationale)
  description TEXT NULL, -- Ex: "À recruter par voie de concours"
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie_depense) REFERENCES categorie_depense(id)
);

-- ===========================
-- TABLE INDICATEUR_MACRO (pour les indicateurs macroéconomiques supplémentaires : solde global, taux de change, taux d'investissement public/privé, etc.)
-- ===========================
CREATE TABLE IF NOT EXISTS indicateur_macro (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  nom VARCHAR(150) NOT NULL, -- Ex: "Solde global (base caisse)", "Taux de change Dollars/Ariary"
  valeur DECIMAL(15,2) NOT NULL,
  unite VARCHAR(50) NULL, -- Ex: "% PIB", "Ariary"
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);

-- ===========================
-- TABLE GLOSSAIRE (pour acronymes et définitions : combine acronymes et glossaire du document)
-- ===========================
CREATE TABLE IF NOT EXISTS glossaire (
  id INT AUTO_INCREMENT PRIMARY KEY,
  terme VARCHAR(100) NOT NULL, -- Ex: "PIB", "Souveraineté alimentaire"
  definition TEXT NOT NULL,
  type ENUM('acronyme', 'terme') NOT NULL -- Pour distinguer acronymes et glossaire
);