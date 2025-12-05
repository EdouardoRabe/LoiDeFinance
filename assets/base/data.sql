USE loi_de_finance;

-- ===========================
-- 1️⃣ TABLE ANNEE
-- ===========================
INSERT INTO annee (annee, pib, taux_croissance, taux_inflation, taux_pression_fiscale)
VALUES 
(2024, 78945.4, 4.4, 8.2, 10.6),
(2025, 88851.6, 5.0, 7.1, 11.2);

-- ===========================
-- 2️⃣ TABLE CATEGORIE_RECETTE
-- ===========================
INSERT INTO categorie_recette (nom, description) VALUES
('Impôt sur les revenus', 'Impôt direct sur les bénéfices et revenus des personnes morales/physiques hors traitements et salaires.'),
('Impôt sur les revenus salariaux et assimilés', 'Retenues à la source sur salaires et assimilés (IRSA).'),
('Impôt sur les revenus des capitaux mobiliers', 'Prélèvements sur dividendes, intérêts et autres revenus mobiliers.'),
('Impôt sur les plus-values immobilières', 'Prélèvement sur les plus-values de cession d’immeubles.'),
('Impôt synthétique', 'Régime simplifié pour petits contribuables, forfaitaire/simplifié.'),
('Droit d’enregistrement', 'Droits perçus lors des mutations/actes soumis à enregistrement.'),
('Taxe sur la valeur ajoutée (TVA + TTM)', 'TVA sur biens et services et taxe sur les transactions mobiles (TTM) incluse.'),
('Impôt sur les marchés publics', 'Impôt appliqué aux contrats de marchés financés par l’État.'),
('Droit d’accise', 'Taxe spécifique sur certains produits (alcool, tabac, plastiques, etc.).'),
('Taxes sur les assurances', 'Prélèvements sur les primes et contrats d’assurance.'),
('Droit de timbre', 'Droits sur actes, documents et formalités administratives.'),
('Droit de douane', 'Droits perçus à l’importation sur les marchandises.'),
('TVA à l’importation', 'TVA perçue à l’entrée sur les biens importés.'),
('Taxe sur les produits pétroliers', 'Taxe spécifique sur les produits pétroliers.'),
('TVA sur produits pétroliers', 'TVA perçue sur les produits pétroliers.'),
('Dividendes', 'Revenus distribués par les entreprises publiques/participations de l’État.'),
('Redevances minières', 'Redevances liées à l’exploitation minière.'),
('Redevances de pêche', 'Redevances d’accès/quotas liés à la pêche.'),
('Produits des activités diverses', 'Autres produits et redevances diverses.'),
('Dons courants', 'Dons budgétaires et transferts courants non remboursables.'),
('Dons projets', 'Appuis extérieurs pour projets d’investissement publics.');

-- ===========================
-- 3️⃣ TABLE CATEGORIE_DEPENSE
-- ===========================
INSERT INTO categorie_depense (nom, description) VALUES
('Présidence de la République', 'Fonctionnement et programmes rattachés à la Présidence.'),
('Sénat', 'Dépenses de l’institution parlementaire (chambre haute).'),
('Assemblée Nationale', 'Dépenses de l’institution parlementaire (chambre basse).'),
('Primature', 'Fonctionnement et coordination gouvernementale du Premier Ministre.'),
('Ministère de la Défense Nationale', 'Dépenses de défense et sécurité nationale.'),
('Ministère de la Santé Publique', 'Fonctionnement et investissements santé, hôpitaux, programmes.'),
('Ministère de l’Éducation Nationale', 'Fonctionnement et investissements en éducation de base.'),
('Ministère des Travaux Publics', 'Investissements routiers et ouvrages publics.'),
('Ministère de l’Énergie et des Hydrocarbures', 'Projets de production, distribution et transition énergétique.'),
('Ministère de l’Agriculture et de l’Élevage', 'Programmes de production agricole et appui aux filières.'),
('Ministère de la Population et des Solidarités', 'Programmes sociaux et aides aux ménages.'),
('Ministère de l’Eau et de l’Assainissement', 'Infrastructures et services d’eau potable et assainissement.'),
('Ministère de la Jeunesse et des Sports', 'Programmes jeunesse, équipements et activités sportives.');

-- ===========================
-- 4️⃣ TABLE RECETTE (2024 et 2025, en milliards d’Ariary)
-- ===========================
INSERT INTO recette (id_annee, id_categorie, type, montant) VALUES
-- 2024 (LFR 2024)
(1, 1, 'fiscale', 1179.0),
(1, 2, 'fiscale', 848.2),
(1, 3, 'fiscale', 78.2),
(1, 4, 'fiscale', 14.0),
(1, 5, 'fiscale', 132.3),
(1, 6, 'fiscale', 49.0),
(1, 7, 'fiscale', 1400.2),
(1, 8, 'fiscale', 148.7),
(1, 9, 'fiscale', 754.1),
(1, 10, 'fiscale', 17.2),
(1, 11, 'fiscale', 14.1),

(1, 12, 'douanière', 847.5),
(1, 13, 'douanière', 1768.3),
(1, 14, 'douanière', 308.0),
(1, 15, 'douanière', 842.8),

(1, 16, 'non fiscale', 89.5),
(1, 17, 'non fiscale', 84.9),
(1, 18, 'non fiscale', 10.0),
(1, 19, 'non fiscale', 11.1),

(1, 20, 'don', 0.3),
(1, 21, 'don', 1086.0),

-- 2025 (LF 2025)
(2, 1, 'fiscale', 1411.4),
(2, 2, 'fiscale', 889.9),
(2, 3, 'fiscale', 93.7),
(2, 4, 'fiscale', 18.3),
(2, 5, 'fiscale', 164.7),
(2, 6, 'fiscale', 62.8),
(2, 7, 'fiscale', 1742.2),
(2, 8, 'fiscale', 250.0),
(2, 9, 'fiscale', 955.4),
(2, 10, 'fiscale', 20.6),
(2, 11, 'fiscale', 16.8),

(2, 12, 'douanière', 1010.7),
(2, 13, 'douanière', 2148.3),
(2, 14, 'douanière', 326.0),
(2, 15, 'douanière', 879.0),

(2, 16, 'non fiscale', 120.2),
(2, 17, 'non fiscale', 331.2),
(2, 18, 'non fiscale', 15.0),
(2, 19, 'non fiscale', 8.1),

(2, 20, 'don', 31.0),
(2, 21, 'don', 2445.6);

-- ===========================
-- 5️⃣ TABLE DEPENSE (2024 et 2025)
-- ===========================
INSERT INTO depense (id_annee, id_categorie, type, montant) VALUES
-- LFR 2024
(1, 1, 'administratif', 177.1),
(1, 2, 'administratif', 22.1),
(1, 3, 'administratif', 87.4),
(1, 4, 'administratif', 278.3),
(1, 5, 'administratif', 557.0),
(1, 6, 'fonctionnement', 716.6),
(1, 7, 'fonctionnement', 1532.8),
(1, 8, 'investissement', 1217.3),
(1, 9, 'investissement', 407.9),
(1, 10, 'investissement', 469.8),
(1, 11, 'fonctionnement', 99.1),
(1, 12, 'investissement', 306.1),
(1, 13, 'fonctionnement', 40.5),

-- LF 2025
(2, 1, 'administratif', 224.7),
(2, 2, 'administratif', 21.3),
(2, 3, 'administratif', 85.9),
(2, 4, 'administratif', 339.9),
(2, 5, 'administratif', 543.2),
(2, 6, 'fonctionnement', 921.0),
(2, 7, 'fonctionnement', 1562.0),
(2, 8, 'investissement', 2327.5),
(2, 9, 'investissement', 1332.0),
(2, 10, 'investissement', 795.5),
(2, 11, 'fonctionnement', 193.4),
(2, 12, 'investissement', 600.2),
(2, 13, 'fonctionnement', 58.1);

-- ===========================
-- 6️⃣ TABLE DEFICIT
-- ===========================
INSERT INTO deficit (id_annee, montant_total, financement_exterieur, financement_interieur)
VALUES
(1, 3642.2, 3147.6, 494.6),
(2, 3642.2, 3147.6, 494.6);

-- (Même déficit prévu pour LF 2025 selon la prévision budgétaire)

-- ===========================
-- 7️⃣ TABLE DISPOSITION_FISCALE
-- ===========================
INSERT INTO disposition_fiscale (id_annee, type, description) VALUES
-- LFR 2024
(1, 'TVA', 'TVA standard maintenue à 20 % sur la plupart des produits, quelques exonérations pour produits de base.'),
(1, 'ACCIS', 'Révision du droit d’accise sur l’alcool et le tabac.'),
(1, 'IR', 'Ajustement du barème de l’impôt sur les revenus.'),
(1, 'IFPB', 'Révision des taux de l’impôt foncier sur la propriété bâtie.'),
-- LF 2025
(2, 'TTM', 'Instauration de la taxe sur les transactions mobiles (TTM) à 0,5 % sur les transferts supérieurs à 150 000 Ar.'),
(2, 'TVA', 'TVA fixée à 10 % sur le gaz butane et ses contenants.'),
(2, 'ACCIS', 'Extension du droit d’accise aux produits plastiques et cigarettes électroniques.'),
(2, 'EXONERATIONS', 'Suppression des exonérations sur certains produits (chaussures de sport, robinetterie, etc.).'),
(2, 'IR', 'Retaxation des intérêts bancaires et des contrats d’assurance à la TVA.'),
(2, 'IFPB', 'Exonération de 5 ans pour les nouvelles constructions, taux de 1 % sur la valeur vénale ou locative.'),
(2, 'IRSA', 'Limitation à 2 % du salaire brut pour la déduction santé, réduction d’impôt de 2 000 Ar par personne à charge.');
