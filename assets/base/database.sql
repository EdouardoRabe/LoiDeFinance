-- ===========================
-- BASE DE DONNÉES : LOI DE FINANCES ACCESSIBLE
-- ===========================

CREATE DATABASE IF NOT EXISTS loi_de_finance;
USE loi_de_finance;

-- ===========================
-- 1. TABLE ANNEE
-- ===========================
CREATE TABLE annee (
  id INT AUTO_INCREMENT PRIMARY KEY,
  annee YEAR NOT NULL,
  pib DECIMAL(15,2),
  taux_croissance DECIMAL(5,2),
  taux_inflation DECIMAL(5,2),
  taux_pression_fiscale DECIMAL(5,2)
);

-- ===========================
-- 2. TABLE CATEGORIE_RECETTE
-- ===========================
CREATE TABLE categorie_recette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT NULL
);

-- Exemples :
-- Impôt sur le revenu, TVA, Droits de douane, Dividendes, Dons projets...

-- ===========================
-- 3. TABLE CATEGORIE_DEPENSE
-- ===========================
CREATE TABLE categorie_depense (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(150) NOT NULL,
  description TEXT NULL
);

-- Exemples :
-- Présidence, Sénat, Santé Publique, Travaux Publics, Éducation, etc.

-- ===========================
-- 4. TABLE RECETTE
-- ===========================
CREATE TABLE recette (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_categorie INT NOT NULL,
  type ENUM('fiscale','douanière','non fiscale','don') NOT NULL,
  montant DECIMAL(15,2) NOT NULL,
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie) REFERENCES categorie_recette(id)
);

-- ===========================
-- 5. TABLE DEPENSE
-- ===========================
CREATE TABLE depense (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  id_categorie INT NOT NULL,
  type ENUM('fonctionnement','investissement','dette','administratif') NOT NULL,
  montant DECIMAL(15,2) NOT NULL,
  FOREIGN KEY (id_annee) REFERENCES annee(id),
  FOREIGN KEY (id_categorie) REFERENCES categorie_depense(id)
);

-- ===========================
-- 6. TABLE DEFICIT
-- ===========================
CREATE TABLE deficit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  montant_total DECIMAL(15,2) NOT NULL,
  financement_exterieur DECIMAL(15,2),
  financement_interieur DECIMAL(15,2),
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);

-- ===========================
-- 7. TABLE DISPOSITION_FISCALE
-- ===========================
CREATE TABLE disposition_fiscale (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_annee INT NOT NULL,
  type VARCHAR(100),
  description TEXT,
  FOREIGN KEY (id_annee) REFERENCES annee(id)
);
