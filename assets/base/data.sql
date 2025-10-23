-- Insertion des données pour l'année 2024, 2025 et 2026 basées sur le Tableau 1
INSERT INTO annee (annee, pib, taux_croissance, taux_inflation, taux_pression_fiscale) VALUES 
(2024, 78945.4, 4.4, 8.2, 10.6),
(2025, 88851.6, 5.0, 7.1, 11.2),
(2026, 99826.3, 5.2, 7.2, 11.8);

-- Insertions pour les secteurs principaux (primaire, secondaire, tertiaire)
INSERT INTO secteur (nom, type, parent_id, description) VALUES 
('Secteur primaire', 'primaire', NULL, 'Secteur incluant agriculture, élevage, etc.'),
('Secteur secondaire', 'secondaire', NULL, 'Secteur incluant industries extractives, manufacturing, etc.'),
('Secteur tertiaire', 'tertiaire', NULL, 'Secteur incluant services, commerce, etc.');

-- Récupération des IDs des secteurs principaux (假设 insertion order: 1=primaire, 2=secondaire, 3=tertiaire)
-- Insertions pour les sous-secteurs du primaire
INSERT INTO secteur (nom, type, parent_id, description) VALUES 
('Agriculture', NULL, 1, 'Production agricole'),
('Élevage et pêche', NULL, 1, 'Élevage et pêche'),
('Sylviculture', NULL, 1, 'Gestion des forêts');

-- Insertions pour les sous-secteurs du secondaire
INSERT INTO secteur (nom, type, parent_id, description) VALUES 
('Industrie extractive', NULL, 2, 'Extraction minière'),
('Alimentaire, boisson, tabac', NULL, 2, 'Industrie alimentaire'),
('Textile', NULL, 2, 'Industrie textile'),
('Bois, papiers, imprimerie', NULL, 2, 'Industrie du bois et papier'),
('Matériaux de construction', NULL, 2, 'Matériaux de construction'),
('Industrie métallique', NULL, 2, 'Industrie métallique'),
('Machine, matériels électriques', NULL, 2, 'Machines et équipements électriques'),
('Industries diverses', NULL, 2, 'Autres industries'),
('Électricité, eau, gaz', NULL, 2, 'Services publics d  énergie et eau');

-- Insertions pour les sous-secteurs du tertiaire
INSERT INTO secteur (nom, type, parent_id, description) VALUES 
('BTP', NULL, 3, 'Bâtiment et travaux publics'),
('Commerce, entretiens, réparations', NULL, 3, 'Commerce et réparations'),
('Hôtel, restaurant', NULL, 3, 'Hôtellerie et restauration'),
('Transport', NULL, 3, 'Transport'),
('Poste et télécommunication', NULL, 3, 'Poste et télécoms'),
('Banque, assurance', NULL, 3, 'Services financiers'),
('Services aux entreprises', NULL, 3, 'Services B2B'),
('Administration', NULL, 3, 'Administration publique'),
('Éducation', NULL, 3, 'Éducation'),
('Santé', NULL, 3, 'Santé'),
('Services rendus aux ménages', NULL, 3, 'Services domestiques');

-- Insertions pour les taux de croissance sectorielle (basé sur Tableau 2), IDs secteurs假设és (4=Agriculture, 5=Élevage, etc.)
INSERT INTO croissance_secteur (id_annee, id_secteur, taux) VALUES 
-- 2024
(1, 1, 5.3),  -- Primaire
(1, 4, 6.0),  -- Agriculture
(1, 5, 3.9),  -- Élevage et pêche
(1, 6, 1.0),  -- Sylviculture
(1, 2, -3.3), -- Secondaire
(1, 7, -20.8),-- Industrie extractive
(1, 8, 0.9),  -- Alimentaire
(1, 9, 31.6), -- Textile
(1, 10, 0.4), -- Bois
(1, 11, 7.9), -- Matériaux construction
(1, 12, 7.2), -- Métallique
(1, 13, 3.1), -- Machines
(1, 14, 0.5), -- Diverses
(1, 15, 3.9), -- Électricité
(1, 3, 5.0),  -- Tertiaire
(1, 16, 3.2), -- BTP
(1, 17, 4.2), -- Commerce
(1, 18, 14.7),-- Hôtel
(1, 19, 7.0), -- Transport
(1, 20, 13.4),-- Poste télécom
(1, 21, 5.3), -- Banque
(1, 22, 2.3), -- Services entreprises
(1, 23, 1.7), -- Administration
(1, 24, 1.7), -- Éducation
(1, 25, 1.8), -- Santé
(1, 26, 1.3), -- Services ménages
-- 2025
(2, 1, 7.8),
(2, 4, 9.5),
(2, 5, 4.0),
(2, 6, 1.1),
(2, 2, 3.4),
(2, 7, 4.0),
(2, 8, 2.4),
(2, 9, 4.0),
(2, 10, 0.7),
(2, 11, 8.0),
(2, 12, 7.3),
(2, 13, 3.2),
(2, 14, 0.6),
(2, 15, 4.0),
(2, 3, 5.4),
(2, 16, 3.6),
(2, 17, 4.3),
(2, 18, 14.9),
(2, 19, 7.2),
(2, 20, 13.7),
(2, 21, 6.1),
(2, 22, 2.4),
(2, 23, 1.9),
(2, 24, 1.8),
(2, 25, 1.9),
(2, 26, 1.4);

-- Catégories de recettes (basées sur tableaux 3,4,5,6)
INSERT INTO categorie_recette (nom, description) VALUES 
('Impôt sur les revenus', 'Impôts sur revenus généraux'),
('Impôt sur les revenus Salariaux et Assimilés', 'Impôts sur salaires'),
('Impôt sur les revenus des Capitaux Mobiliers', 'Impôts sur capitaux mobiliers'),
('Impôt sur les plus-values Immobilières', 'Impôts sur plus-values immobilières'),
('Impôt Synthétique', 'Impôt synthétique'),
('Droit d  Enregistrement', 'Droits d  enregistrement'),
('Taxe sur la Valeur Ajoutée', 'TVA y compris TTM'),
('Impôt sur les marchés Publics', 'Impôts sur marchés publics'),
('Droit d  Accise', 'Droits d  accise y compris taxe environnementale'),
('Taxes sur les Assurances', 'Taxes assurances'),
('Droit de Timbres', 'Droits de timbre'),
('Autres impôts', 'Autres impôts'),
('Droit de douane', 'Droits de douane'),
('TVA à l  importation', 'TVA sur importations'),
('Taxe sur les produits pétroliers', 'Taxe pétrole'),
('TVA sur les produits pétroliers', 'TVA pétrole'),
('Droit de navigation', 'Droits navigation'),
('Autres douanes', 'Autres douanes'),
('Dividendes', 'Dividendes'),
('Productions immobilières financières', 'Produits immobiliers'),
('Redevance de pêche', 'Redevances pêche'),
('Redevance minières', 'Redevances minières'),
('Autres redevance', 'Autres redevances'),
('Produits des activités et autres', 'Produits activités'),
('Autres non fiscales', 'Autres non fiscales'),
('Dons courants', 'Dons courants'),
('Dons capital', 'Dons en capital');

--假设 IDs catégories recettes: 1=Impôt revenus, 2=Salariaux, ..., 27=Dons courants, 28=Dons capital

-- Recettes pour 2024 et 2025 (id_annee 1=2024, 2=2025)
INSERT INTO recette (id_annee, id_categorie, type, montant) VALUES 
-- Fiscales intérieures 2024
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
(1, 12, 'fiscale', 1.5),
-- Fiscales intérieures 2025
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
(2, 12, 'fiscale', 2.7),
-- Douanières 2024
(1, 13, 'douanière', 847.5),
(1, 14, 'douanière', 1768.3),
(1, 15, 'douanière', 308.0),
(1, 16, 'douanière', 842.8),
(1, 17, 'douanière', 1.2),
(1, 18, 'douanière', 0.2),
-- Douanières 2025
(2, 13, 'douanière', 1010.7),
(2, 14, 'douanière', 2148.3),
(2, 15, 'douanière', 326.0),
(2, 16, 'douanière', 879.0),
(2, 17, 'douanière', 1.9),
(2, 18, 'douanière', 0.1),
-- Non fiscales 2024
(1, 19, 'non fiscale', 89.5),
(1, 20, 'non fiscale', 0.5),
(1, 21, 'non fiscale', 10.0),
(1, 22, 'non fiscale', 84.9),
(1, 23, 'non fiscale', 9.7),
(1, 24, 'non fiscale', 11.1),
(1, 25, 'non fiscale', 140.1),
-- Non fiscales 2025
(2, 19, 'non fiscale', 120.2),
(2, 20, 'non fiscale', 2.1),
(2, 21, 'non fiscale', 15.0),
(2, 22, 'non fiscale', 331.2),
(2, 23, 'non fiscale', 10.0),
(2, 24, 'non fiscale', 8.1),
(2, 25, 'non fiscale', 5.2),
-- Dons 2024
(1, 26, 'don', 0.3),
(1, 27, 'don', 1086.0),
-- Dons 2025
(2, 26, 'don', 31.0),
(2, 27, 'don', 2445.6);

-- Catégories de dépenses (basées sur Tableau 10 et autres)
INSERT INTO categorie_depense (nom, description) VALUES 
('Présidence de la République', 'Budget présidence'),
('Sénat', 'Budget sénat'),
('Assemblée Nationale', 'Budget assemblée'),
('Haute Cour Constitutionnelle', 'Budget HCC'),
('Primature', 'Budget primature'),
('Conseil du Fampihavanana Malagasy', 'Budget conseil fampihavanana'),
('Commission Électorale Nationale Indépendante', 'Budget CENI'),
('Ministère de la Défense Nationale', 'Budget défense'),
('Ministère des Affaires Étrangères', 'Budget affaires étrangères'),
('Ministère de la Justice', 'Budget justice'),
('Ministère de l  Intérieur', 'Budget intérieur'),
('Ministère de l  Économie et des Finances', 'Budget économie finances'),
('Ministère de la Sécurité Publique', 'Budget sécurité publique'),
('Ministère de l  Industrialisation et du Commerce', 'Budget industrialisation commerce'),
('Ministère de la Décentralisation et de l  Aménagement du Territoire', 'Budget décentralisation'),
('Ministère du Travail, de l  Emploi et de la Fonction Publique', 'Budget travail'),
('Ministère du Tourisme et de l  Artisanat', 'Budget tourisme'),
('Ministère de l  Enseignement Supérieur et de la Recherche Scientifique', 'Budget enseignement supérieur'),
('Ministère de l  Environnement et du Développement Durable', 'Budget environnement'),
('Ministère de l  Éducation Nationale', 'Budget éducation nationale'),
('Ministère des Transports et de la Météorologie', 'Budget transports'),
('Ministère de la Santé Publique', 'Budget santé publique'),
('Ministère de la Communication et de la Culture', 'Budget communication culture'),
('Ministère des Travaux Publics', 'Budget travaux publics'),
('Ministère des Mines et des Ressources Strategiques', 'Budget mines'),
('Ministère de l  Énergie et des Hydrocarbures', 'Budget énergie'),
('Ministère de l  Eau, de l  Assainissement et de l  Hygiène', 'Budget eau'),
('Ministère de l  Agriculture et de l  Élevage', 'Budget agriculture élevage'),
('Ministère de la Pêche et de l  Économie Bleue', 'Budget pêche'),
('Ministère de l  Enseignement Technique et de la Formation Professionnelle', 'Budget enseignement technique'),
('Ministère du Développement Numérique, des Postes et des Télécommunications', 'Budget numérique'),
('Ministère de la Population et des Solidarités', 'Budget population'),
('Ministère de la Jeunesse et des Sports', 'Budget jeunesse sports'),
('Secretariat d  État en charge des Nouvelles Villes et de l  Habitat', 'Budget nouvelles villes'),
('Ministère délégué chargé de la Gendarmerie', 'Budget gendarmerie'),
('Secrétariat d  État en charge de la Souveraineté Alimentaire', 'Budget souveraineté alimentaire'),
('Haut Conseil pour la Défense de la Démocratie et de l  État de Droit (HCDDED)', 'Budget HCDDED'),
('Commission Nationale Indépendante des Droits de l  Homme (CNIDH)', 'Budget CNIDH'),
('Haute Cour de Justice', 'Budget HCJ'),
('Intérêts de la dette', 'Intérêts dette'),
('Dépenses de soldes', 'Soldes et pensions'),
('Dépenses de fonctionnement', 'Fonctionnement hors solde'),
('Dépenses d  investissement', 'Investissements');

--假设 IDs catégories depenses: 1=Présidence, ..., 40=Intérêts dette, 41=Soldes, 42=Fonctionnement, 43=Investissement

-- Dépenses pour 2024 et 2025 (id_annee 1=2024, 2=2025, type basé sur nature)
INSERT INTO depense (id_annee, id_categorie, type, montant) VALUES 
-- 2024 (LFR 2024)
(1, 1, 'administratif', 177.1),
(1, 2, 'administratif', 22.1),
(1, 3, 'administratif', 87.4),
(1, 4, 'administratif', 11.9),
(1, 5, 'administratif', 278.3),
(1, 6, 'administratif', 6.7),
(1, 7, 'administratif', 113.3),
(1, 8, 'administratif', 557.0),
(1, 9, 'administratif', 99.2),
(1, 10, 'administratif', 199.6),
(1, 11, 'administratif', 150.2),
(1, 12, 'administratif', 2848.0),
(1, 13, 'administratif', 228.3),
(1, 14, 'administratif', 113.2),
(1, 15, 'administratif', 356.8),
(1, 16, 'administratif', 31.8),
(1, 17, 'administratif', 19.2),
(1, 18, 'administratif', 284.2),
(1, 19, 'administratif', 94.4),
(1, 20, 'administratif', 1532.8),
(1, 21, 'administratif', 63.9),
(1, 22, 'administratif', 716.6),
(1, 23, 'administratif', 38.4),
(1, 24, 'administratif', 1217.3),
(1, 25, 'administratif', 18.3),
(1, 26, 'administratif', 407.9),
(1, 27, 'administratif', 306.1),
(1, 28, 'administratif', 469.8),
(1, 29, 'administratif', 29.9),
(1, 30, 'administratif', 103.7),
(1, 31, 'administratif', 8.4),
(1, 32, 'administratif', 99.1),
(1, 33, 'administratif', 40.5),
(1, 34, 'administratif', 247.1),
(1, 35, 'administratif', 414.8),
(1, 36, 'administratif', 0.0),  -- Pas en 2024
(1, 37, 'administratif', 2.1),
(1, 38, 'administratif', 2.1),
(1, 39, 'administratif', 3.7),
(1, 40, 'dette', 672.0),
(1, 41, 'fonctionnement', 3814.5),
(1, 42, 'fonctionnement', 3069.0),
(1, 43, 'investissement', 4836.8),
-- 2025 (LF 2025)
(2, 1, 'administratif', 224.7),
(2, 2, 'administratif', 21.3),
(2, 3, 'administratif', 85.9),
(2, 4, 'administratif', 9.3),
(2, 5, 'administratif', 339.9),
(2, 6, 'administratif', 6.3),
(2, 7, 'administratif', 16.4),
(2, 8, 'administratif', 543.2),
(2, 9, 'administratif', 104.7),
(2, 10, 'administratif', 219.8),
(2, 11, 'administratif', 134.7),
(2, 12, 'administratif', 2332.7),
(2, 13, 'administratif', 229.2),
(2, 14, 'administratif', 119.6),
(2, 15, 'administratif', 568.1),
(2, 16, 'administratif', 33.7),
(2, 17, 'administratif', 43.9),
(2, 18, 'administratif', 285.6),
(2, 19, 'administratif', 188.8),
(2, 20, 'administratif', 1562.0),
(2, 21, 'administratif', 216.3),
(2, 22, 'administratif', 921.0),
(2, 23, 'administratif', 32.1),
(2, 24, 'administratif', 2327.5),
(2, 25, 'administratif', 18.1),
(2, 26, 'administratif', 1332.0),
(2, 27, 'administratif', 600.2),
(2, 28, 'administratif', 795.5),
(2, 29, 'administratif', 28.8),
(2, 30, 'administratif', 94.8),
(2, 31, 'administratif', 8.8),
(2, 32, 'administratif', 193.4),
(2, 33, 'administratif', 58.1),
(2, 34, 'administratif', 138.8),
(2, 35, 'administratif', 446.4),
(2, 36, 'administratif', 127.3),
(2, 37, 'administratif', 2.0),
(2, 38, 'administratif', 2.0),
(2, 39, 'administratif', 3.5),
(2, 40, 'dette', 756.5),
(2, 41, 'fonctionnement', 3846.4),
(2, 42, 'fonctionnement', 2304.3),
(2, 43, 'investissement', 8537.2);

-- Déficit pour 2025 (principalement), et 2024 si disponible
INSERT INTO deficit (id_annee, montant_total, financement_exterieur, financement_interieur) VALUES 
(1, -4.3, NULL, NULL),  -- % PIB, mais montant non précis, adapter si besoin
(2, 3642.2, 3147.6, 494.6);

-- Dispositions fiscales (extraites de la section V)
INSERT INTO disposition_fiscale (id_annee, type, description) VALUES 
(2, 'Impôt sur les revenus (IR)', 'Minima de perception pour les compagnies pétrolières : 1 000 000 Ar, majoré de 7‰ du chiffre d  affaires annuel HT'),
(2, 'Impôt sur les revenus (IR)', 'Minima de perception pour les détaillants de carburant : 1‰ du chiffre d  affaires HT'),
(2, 'Impôt sur les marchés publics (IMP)', 'Exonération des revenus des marchés financés par des fonds RSE et des transactions avec la Banky Foiben  i Madagasikara'),
(2, 'Impôt synthétique (IS)', 'Modifications des délais de déclaration fiscale : Déclarations à soumettre au plus tard le 15 du mois suivant'),
(2, 'Impôt sur les revenus salariaux et assimilés (IRSA)', 'Limitation à 2% du salaire brut pour la déduction des cotisations de santé'),
(2, 'Impôt sur les revenus salariaux et assimilés (IRSA)', 'Réduction d  impôt de 2 000 Ar pour les personnes à charge'),
(2, 'Impôt sur les plus values immobilières (IPVI)', 'Imposition des plus-values immobilières : Libératoire de l  impôt sur les revenus et de l  impôt synthétique, avec 30% des recettes affectées au Fonds National Foncier'),
(2, 'Droit d  enregistrement', 'Base imposable pour les mutations immobilières : non inférieure à la valeur administrative'),
(2, 'Droit d  enregistrement', 'Minimum de perception pour les droits proportionnels : Fixé à 20 000 Ar'),
(2, 'Taxe sur les transactions mobiles', 'Opérations soumises à la taxe : transferts d  argent, paiements de biens et services, etc.'),
(2, 'Taxe sur les transactions mobiles', 'Responsabilité de paiement de la taxe : à la charge de la personne effectuant l  envoi'),
(2, 'Taxe sur les transactions mobiles', 'Exonérations : transferts inférieurs à Ar 150 000, etc.'),
(2, 'Taxe sur les transactions mobiles', 'Taux de la taxe fixé à 0,5%'),
(2, 'Taxe sur la valeur ajoutée', 'Assujettissement à la TVA : Pour les entreprises avec un chiffre d  affaires annuel ≥ 400 000 000 Ar'),
(2, 'Taxe sur la valeur ajoutée', 'TVA à 10 % : Applicable aux importations et ventes de gaz butane'),
(2, 'Impôt foncier sur le terrain et impôt sur les propriétés bâtis', 'Date limite pour le dépôt des déclarations fiscales d  impôt foncier sur les terrains : fixée au 15 octobre'),
(2, 'Impôt foncier sur le terrain et impôt sur les propriétés bâtis', 'Établissement de l  impôt foncier : effectué par le Centre fiscal compétent'),
(2, 'Impôt foncier sur le terrain et impôt sur les propriétés bâtis', 'Base d  imposition pour l  IFT, l  IFPB, et la TAFPB : valeur vénale ou locative, avec un taux de 1 %'),
(2, 'Impôt sur les revenus des capitaux mobiliers', 'Assujettissement à l  IRCM pour les revenus des capitaux mobiliers'),
(2, 'Impôt sur les revenus des capitaux mobiliers', 'Dividendes et autres distributions sont soumis à un taux de 10% d  IRCM'),
(2, 'Droits d  enregistrement et de timbre', 'Taux réduits pour les actes de formation, prorogation de société et augmentation de capital'),
(2, 'Droits d  enregistrement et de timbre', 'Droits d  enregistrement pour cession de bail ou concession : Fixés à 4% du montant'),
(2, 'Taxe sur les chiffres d  affaires', 'Exonération de TVA sur les paiements d  intérêts, frais et commissions liées aux emprunts'),
(2, 'Taxe sur les chiffres d  affaires', 'Taux zéro de TVA pour les exportations et cessions de produits miniers'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Création possible de zones franches, ports francs, zones économiques spéciales'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Application des régimes douaniers des zones franches'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Mise en place, fonctionnement et conditions d  exploitation des zones franches'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Mesures tarifaires ou non tarifaires des autres entités publiques soumises à l  étude'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Vérification par l  Administration des Douanes du respect des critères d  origine'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Déclaration des éléments sur la valeur considérée comme un acte authentique'),
(2, 'Nouvelles dispositions dans les codes de douanes', 'Imposition d  un droit d  accise sur certains produits consommés dans le territoire douanier');

-- Projets d'investissement (basés sur section III.1.d)
INSERT INTO projet_investissement (id_annee, nom, description, id_categorie_depense, montant, source_financement, secteur) VALUES 
(2, 'Mandraka III', 'Projets hydroélectriques pour augmenter la production d  électricité', 26, NULL, 'externe', 'Énergie'),
(2, 'Volobe', 'Projets hydroélectriques pour augmenter la production d  électricité', 26, NULL, 'externe', 'Énergie'),
(2, 'Centrales solaires', 'Installation de 150 MW de centrales solaires', 26, NULL, 'mixte', 'Énergie'),
(2, 'Hazavana ho anao', 'Extension des kits solaires 3 kW', 26, NULL, 'interne', 'Énergie'),
(2, 'Smart Clean Cooking', 'Promotion des technologies de cuisson écologiques', 26, NULL, 'externe', 'Énergie'),
(2, 'Transformation des déchets en énergie', 'Développement de projets générant 20-30 MW à partir des déchets', 26, NULL, 'mixte', 'Énergie'),
(2, 'Production de riz', 'Distribution de semences hybrides, mécanisation', 28, NULL, 'interne', 'Agriculture'),
(2, 'Titre vert et Ankohonana miarina', 'Soutien aux projets pour augmenter la production de paddy', 28, NULL, 'mixte', 'Agriculture'),
(2, 'Réhabilitation des infrastructures agricoles', 'Modernisation des systèmes d  irrigation, barrages', 28, NULL, 'externe', 'Agriculture'),
(2, 'PFUMVUDZA', 'Mise en œuvre de techniques agricoles modernes', 28, NULL, 'interne', 'Agriculture'),
(2, 'Usines d  engrais', 'Construction d  usines locales pour soutenir la productivité', 28, NULL, 'mixte', 'Agriculture'),
(2, 'Développement de l  aquaculture', 'Contribution au développement de l  aquaculture', 29, NULL, 'externe', 'Agriculture'),
(2, 'Infrastructures agricoles', 'Construction de canaux d  irrigation, digues', 28, NULL, 'mixte', 'Agriculture'),
(2, 'RN13', 'Poursuite des travaux sur la RN13', 24, NULL, 'externe', 'Infrastructures'),
(2, 'Autoroute Antananarivo-Toamasina', 'Autoroute reliant Antananarivo à Toamasina', 24, NULL, 'mixte', 'Infrastructures'),
(2, 'Flyover Anosizato', 'Aménagement du flyover Anosizato', 24, NULL, 'interne', 'Infrastructures'),
(2, 'Kianja Maki Andohatapenaka', 'Aménagement du Kianja Maki Andohatapenaka', 24, NULL, 'interne', 'Infrastructures'),
(2, 'Corridors économiques', 'Aménagement des corridors économiques', 24, NULL, 'mixte', 'Infrastructures'),
(2, 'Pipeline Efaho', 'Construction du pipeline Efaho pour l  approvisionnement en eau', 27, NULL, 'externe', 'Infrastructures'),
(2, 'Train urbain', 'Développement du train urbain', 21, NULL, 'mixte', 'Infrastructures'),
(2, 'Transport par câble', 'Développement des lignes de transport par câble', 21, NULL, 'externe', 'Infrastructures'),
(2, 'Lake Village d  Ivato', 'Mise en œuvre du projet Lake Village d  Ivato', 34, NULL, 'mixte', 'Infrastructures'),
(2, 'Centres de santé', 'Opérationnalisation de centres de santé', 22, NULL, 'interne', 'Santé'),
(2, 'Distribution de vaccins', 'Distribution de vaccins et programmes de santé publique', 22, NULL, 'externe', 'Santé'),
(2, 'Construction d  écoles', 'Construction d  écoles', 20, NULL, 'mixte', 'Éducation'),
(2, 'Distribution de kits scolaires', 'Distribution de kits scolaires et cantines scolaires', 20, NULL, 'interne', 'Éducation'),
(2, 'Centres de formation de masse', 'Mise en place des centres de formation professionnelle', 30, NULL, 'mixte', 'Éducation'),
(2, 'Karinem-pokontany', 'Distribution des karinem-pokontany avec QR code', 31, NULL, 'interne', 'Numérique');

-- Dette
INSERT INTO dette (id_annee, type, interets, principal, taux_moyen) VALUES 
(1, 'exterieure', 287.6, NULL, NULL),
(2, 'exterieure', 314.2, 880.9, NULL),
(1, 'interieure', 384.4, NULL, NULL),
(2, 'interieure', 442.2, NULL, 13.0);

-- Postes budgétaires 2025
INSERT INTO poste_budgetaire (id_annee, id_categorie_depense, nombre, description) VALUES 
(2, 8, 1000, 'Ministère des Forces Armées'),
(2, 22, 300, 'Ministère de la Santé Publique'),
(2, 13, 1000, 'Ministère de la Sécurité Publique'),
(2, 20, 3000, 'Ministère de l  Éducation Nationale'),
(2, 30, 250, 'Ministère de l  Enseignement Technique et de la Formation Professionnelle'),
(2, 18, 100, 'Ministère de l  Enseignement Supérieur et de la Recherche Scientifique'),
(2, 35, 1000, 'Ministère délégué en charge de la Gendarmerie Nationale');

-- Indicateurs macro (basés sur Tableau 1 et autres)
INSERT INTO indicateur_macro (id_annee, nom, valeur, unite) VALUES 
(1, 'Ratio de dépenses publiques', 16.2, '% PIB'),
(2, 'Ratio de dépenses publiques', 18.4, '% PIB'),
(3, 'Ratio de dépenses publiques', 17.8, '% PIB'),
(1, 'Solde global (base caisse)', -4.3, '% PIB'),
(2, 'Solde global (base caisse)', -4.1, '% PIB'),
(3, 'Solde global (base caisse)', -4.1, '% PIB'),
(1, 'Solde primaire (base caisse)', 214.2, 'milliards Ariary'),
(2, 'Solde primaire (base caisse)', 1097.6, 'milliards Ariary'),
(3, 'Solde primaire (base caisse)', 866.0, 'milliards Ariary'),
(1, 'Taux de change Dollars/Ariary', 4508.6, 'Ariary'),
(2, 'Taux de change Dollars/Ariary', 4688.8, 'Ariary'),
(3, 'Taux de change Dollars/Ariary', 4853.2, 'Ariary'),
(1, 'Taux de change Euro/Ariary', 4905.5, 'Ariary'),
(2, 'Taux de change Euro/Ariary', 5275.2, 'Ariary'),
(3, 'Taux de change Euro/Ariary', 5532.7, 'Ariary'),
(1, 'Taux d  investissement public', 6.1, '% PIB'),
(2, 'Taux d  investissement public', 9.6, '% PIB'),
(3, 'Taux d  investissement public', 8.3, '% PIB'),
(1, 'Taux d  investissement privé', 14.6, '% PIB'),
(2, 'Taux d  investissement privé', 12.0, '% PIB'),
(3, 'Taux d  investissement privé', 13.7, '% PIB');

-- Glossaire (acronymes et termes)
INSERT INTO glossaire (terme, definition, type) VALUES 
('BAD', 'Banque Africaine pour le Développement', 'acronyme'),
('CRCM', 'Caisse de Retraite Civile et Militaire', 'acronyme'),
('HT', 'Hors Taxe', 'acronyme'),
('IFPB', 'Impôt Foncier sur la Propriété Bâtie', 'acronyme'),
('IFT', 'Impôt Foncier sur le Terrain', 'acronyme'),
('IMP', 'Impôt sur les Marchés Publics', 'acronyme'),
('IR', 'Impôt sur les Revenus', 'acronyme'),
('IRCM', 'Impôt Sur les Revenus des Capitaux Mobiliers', 'acronyme'),
('KW', 'Kilowatt', 'acronyme'),
('LF', 'Loi de Finances', 'acronyme'),
('LFI', 'Loi de Finances Initiale', 'acronyme'),
('LFR', 'Loi de Finances Rectificative', 'acronyme'),
('MW', 'Mégawatt', 'acronyme'),
('OGT', 'Opérations Globales du Trésor', 'acronyme'),
('PIB', 'Produit Intérieur Brut', 'acronyme'),
('PIP', 'Programme d  Investissements Publics', 'acronyme'),
('PLF', 'Projet de Loi de Finances', 'acronyme'),
('PTF', 'Partenaires Technique et Financier', 'acronyme'),
('SADC', 'Southern African Development Community', 'acronyme'),
('TAFPB', 'Taxe Annexe à l  IFPB', 'acronyme'),
('TTM', 'Taxe sur les Transactions Mobiles', 'acronyme'),
('TVA', 'Taxe sur la Valeur Ajoutée', 'acronyme'),
('Souveraineté alimentaire', 'Capacité d  un pays à produire suffisamment de nourriture pour répondre aux besoins de sa population, en réduisant sa dépendance vis-à-vis des importations.', 'terme'),
('Transition énergétique', 'Processus de passage des énergies polluantes vers des énergies renouvelables, afin de réduire l  impact sur l  environnement.', 'terme'),
('Zones Économiques Spéciales (ZES)', 'Zones où des conditions spéciales sont mises en place pour attirer les investisseurs.', 'terme'),
('Transition écologique', 'Ensemble des actions visant à protéger l  environnement.', 'terme'),
('Soutenabilité des finances publiques', 'Capacité d  un pays à gérer ses finances de manière responsable.', 'terme'),
('Fonds de Contre-Valeur (FCV)', 'Fonds issus de dons ou d  aides financières, utilisés pour financer des projets spécifiques.', 'terme'),
('Indicateurs macroéconomiques', 'Données chiffrées pour évaluer la santé économique d  un pays.', 'terme'),
('Taxe sur les Transactions Mobiles (TTM)', 'Impôt appliqué aux transferts d  argent via des téléphones mobiles.', 'terme'),
('Taux de Pression Fiscale', 'Pourcentage des impôts collectés par rapport à la richesse totale produite dans le pays (PIB).', 'terme');