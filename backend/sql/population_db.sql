
-- Peuplement de la base CESIZen

-- 1. USERS
INSERT INTO users (username, email, password, created_at, updated_at, is_admin, terms_accepted, accepted_terms_at)
VALUES
('admin', 'admin@cesizen.fr', '$2a$12$DvcailYfGwlu48otR4PQP.9ECmuYtmdH6Vge6tqoJP76/nBzKUL2y', NOW(), NOW(), TRUE, TRUE, NOW()),
('Happie', 'happie@cesizen.fr', '$2a$12$DvcailYfGwlu48otR4PQP.9ECmuYtmdH6Vge6tqoJP76/nBzKUL2y', NOW(), NOW(), FALSE, TRUE, NOW()),
('Nuggets', 'nuggets@cesizen.fr', '$2a$12$DvcailYfGwlu48otR4PQP.9ECmuYtmdH6Vge6tqoJP76/nBzKUL2y', NOW(), NOW(), FALSE, TRUE, NOW());

-- 2. BREATH EXERCISE
INSERT INTO breath_exercise (exercise_label, breath_in, breath_out, holding_breath, media_breath_exercise)
VALUES
('Méthode 7-4-8', 7, 8, 4, NULL),
('Méthode 5-5', 5, 5, 0, NULL),
('Méthode 4-6', 4, 6, 0, NULL);

-- 3. BREATHING SESSION
INSERT INTO breathing_session (created_at, id_exercise, id_user)
VALUES
(NOW(), 1, 2),
(NOW(), 2, 2),
(NOW(), 3, 3);

-- 4. BASIC EMOTION
INSERT INTO basic_emotion (basic_emo_label)
VALUES
('Joie'), ('Colère'), ('Peur'), ('Tristesse'), ('Surprise'), ('Dégoût');

-- 5. EMOTION
INSERT INTO emotion (emotion_label, id_basic_emo)
VALUES
-- Joie
('Fierté', 1), ('Contentement', 1), ('Enchantement', 1), ('Excitation', 1), ('Émerveillement', 1), ('Gratitude', 1),
-- Colère
('Frustration', 2), ('Irritation', 2), ('Rage', 2), ('Ressentiment', 2), ('Agacement', 2), ('Hostilité', 2),
-- Peur
('Inquiétude', 3), ('Anxiété', 3), ('Terreur', 3), ('Appréhension', 3), ('Panique', 3), ('Crainte', 3),
-- Tristesse
('Chagrin', 4), ('Mélancolie', 4), ('Abattement', 4), ('Désespoir', 4), ('Solitude', 4), ('Dépression', 4),
-- Surprise
('Étonnement', 5), ('Stupéfaction', 5), ('Sidération', 5), ('Incrédule', 5), ('Emerveillement', 5), ('Confusion', 5),
-- Dégoût
('Répulsion', 6), ('Déplaisir', 6), ('Nausée', 6), ('Dédain', 6), ('Horreur', 6), ('Dégoût profond', 6);

-- 6. EMOTION TRACKER
INSERT INTO emotion_tracker (created_at, intensity, comment, id_emotion, id_user)
VALUES
(NOW(), 7, 'Journée productive', 1, 2),
(NOW(), 4, 'Un peu fatigué', 13, 3),
(NOW(), 9, 'Moment très joyeux', 5, 2);

-- 7. SCALE EVENT
INSERT INTO scale_event (event_label, event_score) VALUES
('Mort du conjoint', 100), ('Divorce', 73), ('Séparation des époux', 65),
('Mort d’un parent proche', 63), ('Période de prison', 63), ('Blessure corporelle ou maladie', 53),
('Mariage', 50), ('Licenciement', 47), ('Réconciliation entre époux', 45),
('Départ à la retraite', 45), ('Changement dans la santé d’un membre de la famille', 44),
('Grossesse', 40), ('Difficultés sexuelles', 39), ('Arrivée d’un nouveau membre dans la famille', 39),
('Changement dans l’univers du travail', 39), ('Changement au niveau financier', 38),
('Mort d’un ami proche', 37), ('Changement de fonction professionnelle', 36),
('Modification de la fréquence des scènes de ménage', 35), ('Hypothèque ou emprunt de plus de 3.000 €', 31),
('Saisie sur hypothèque ou sur prêt', 30), ('Changement de responsabilité dans le travail', 29),
('Départ du foyer d’une fille ou d’un fils', 29), ('Difficultés avec les beaux-parents', 29),
('Succès exceptionnel', 28), ('Conjoint commençant ou cessant de travailler', 26),
('Début ou fin des études', 26), ('Changement dans les conditions de vie', 25),
('Changement d’habitudes', 24), ('Difficultés avec son employeur/son manager', 23),
('Changement d’horaires ou de conditions de travail', 20), ('Changement de domicile', 20),
('Changement de lieu d’étude', 20), ('Changement dans les loisirs', 19),
('Changement dans les activités de la paroisse', 19), ('Changement dans les activités sociales', 19),
('Hypothèque ou emprunt de moins de 3.000€', 17), ('Changement dans les habitudes de sommeil', 16),
('Changement du nombre de réunions de famille', 15), ('Changement dans les habitudes alimentaires', 15),
('Vacances', 13), ('Noël', 12), ('Infractions mineures à la loi, contraventions', 11);

-- 8. HOLMES RAHE SCALE RESULT
INSERT INTO holmes_rahe_scale_result (result_label, description, min_score, max_score)
VALUES
('stress modéré, risque évalué à 30%', 'En dessous de 100, le risque se révèle peu important.', 0, 100),
('Stress élevé, risque évalué à 51%', 'Score compris entre 300 et 100, risques significatifs.', 100, 299),
('stress très élevé, risque évalué à 80 %', 'Score supérieur à 300 = risque très élevé.', 300, 600);

-- 9. STRESS DIAGNOSTIC
INSERT INTO stress_diagnostic (created_at, id_result, id_user)
VALUES
(NOW(), 2, 2),
(NOW(), 3, 3);

-- 10. STRESS RESPONSE
INSERT INTO stress_response (id_event, id_diagnostic)
VALUES
(2, 1), (3, 1), (8, 1), -- happie
(1, 2), (4, 2), (6, 2); -- nuggets

-- 11. RELAX ACTIVITY
INSERT INTO relax_activity (activity_label, content, category, is_active, type, media_activity)
VALUES
('Méditation guidée', 'Exercice audio', 'relaxation', TRUE, 'audio', 'meditation.mp3'),
('Lecture zen', 'Texte apaisant', 'lecture', TRUE, 'article', NULL);

-- 12. FAVORITE
INSERT INTO favorite (created_at, updated_at, still_fav, id_activity, id_user)
VALUES
(NOW(), NOW(), TRUE, 1, 2);

-- 13. CONNEXION LOG
INSERT INTO connexion_log (date_log, ip, id_user)
VALUES
(NOW(), '192.168.0.2', 2),
(NOW(), '192.168.0.3', 3);

-- 14. CONTENT PAGE
INSERT INTO content_page (content_label, body, media_content, visible, created_at, id_user)
VALUES
('À propos', 'Bienvenue sur CESIZen...', NULL, TRUE, NOW(), 1),
('Mentions légales', 'Conforme au RGPD...', NULL, TRUE, NOW(), 1);
