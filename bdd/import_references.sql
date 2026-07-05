-- New APSI references and photo dump
-- Generated dynamically by process_references.py

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE `photo`;
TRUNCATE TABLE `reference`;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `reference` (`id`, `titre`, `commune`, `description`, `domaine`, `anneeD`, `anneeF`, `statut`, `moa`, `archi`, `eMoe`, `nbPhase`, `montant`, `nbE`, `duree_travaux_mois`, `nombre_lots`) VALUES
(1, 'Aménagement de la Place Jean Jaurès', 'APT (84)', 'Aménagement de la Place Jean Jaurès à Apt.', 5, 2024, 2025, 1, 'CITADIS (84)', NULL, NULL, NULL, 980000, 4, 12, 6),
(2, 'Centre Culturel Simone Signoret', 'CHATEAU-ARNOUX (04)', 'Réhabilitation du Centre Culturel Simone Signoret et de ses abords à Château-Arnoux-Saint-Auban.', 3, 2023, 2025, 1, 'PROVENCE ALPES AGGLOMERATION Digne-les-Bains (04)', NULL, NULL, NULL, 2875000, 13, 15, 18),
(3, 'L’hôtel des Monnaies-Niel', 'AVIGNON (84)', 'Reconversion de l''hôtel des Monnaies-Niel pour la création d''un hôtel de 40 chambres, Place du Palais des Papes à Avignon.', 12, 2024, 2027, 2, 'Groupe E-Hôtel (69)', NULL, NULL, NULL, 8000000, 18, 20, 25),
(4, 'Residence l''Aygues', 'ORANGE (84)', 'Réhabilitation de 146 logements collectifs - Résidence « L''Aygues » à Orange.', 10, 2024, 2026, 2, 'GRAND DELTA HABITAT (84)', NULL, NULL, NULL, 9250000, 9, 24, 12),
(5, 'Réfectoire de Coudoux', 'COUDOUX (13)', 'Création d''un réfectoire et extension du groupe scolaire à Coudoux.', 4, 2024, 2026, 1, 'Commune de COUDOUX (13)', NULL, NULL, NULL, 1300000, 14, 11, 16),
(6, 'Réhabilitation du collège Paul Cézanne', 'BRIGNOLES (83)', 'Réhabilitation du collège Paul Cézanne à Brignoles.', 4, 2024, 2027, 2, 'Conseil Départemental (83)', NULL, NULL, NULL, 5400000, 14, 19, 18),
(7, 'Résidence Les Angevines', 'LE THOR (84)', 'Construction de 30 logements collectifs – Résidence « Les Angevines » au Thor.', 10, 2024, 2026, 2, 'GRAND DELTA HABITAT (84)', NULL, NULL, NULL, 3280000, 17, 20, 22);

INSERT INTO `photo` (`id`, `titre`, `dir`, `idR`, `orderPic`) VALUES
(1, 'amenagement_de_la_place_jean_jaures_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/amenagement_de_la_place_jean_jaures_1.jpg', 1, 1),
(2, 'amenagement_de_la_place_jean_jaures_2.png', 'D:\\dev\\apsi\\APSI-WEB/pic/amenagement_de_la_place_jean_jaures_2.png', 1, 2),
(3, 'amenagement_de_la_place_jean_jaures_3.png', 'D:\\dev\\apsi\\APSI-WEB/pic/amenagement_de_la_place_jean_jaures_3.png', 1, 3),
(4, 'amenagement_de_la_place_jean_jaures_4.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/amenagement_de_la_place_jean_jaures_4.jpg', 1, 4),
(5, 'centre_culturel_simone_signoret_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_1.jpg', 2, 1),
(6, 'centre_culturel_simone_signoret_2.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_2.jpg', 2, 2),
(7, 'centre_culturel_simone_signoret_3.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_3.jpg', 2, 3),
(8, 'centre_culturel_simone_signoret_4.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_4.jpg', 2, 4),
(9, 'centre_culturel_simone_signoret_5.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_5.jpg', 2, 5),
(10, 'centre_culturel_simone_signoret_6.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_6.jpg', 2, 6),
(11, 'centre_culturel_simone_signoret_7.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/centre_culturel_simone_signoret_7.jpg', 2, 7),
(12, 'l_hotel_des_monnaies_niel_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_1.jpg', 3, 1),
(13, 'l_hotel_des_monnaies_niel_2.png', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_2.png', 3, 2),
(14, 'l_hotel_des_monnaies_niel_3.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_3.jpg', 3, 3),
(15, 'l_hotel_des_monnaies_niel_4.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_4.jpg', 3, 4),
(16, 'l_hotel_des_monnaies_niel_5.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_5.jpg', 3, 5),
(17, 'l_hotel_des_monnaies_niel_6.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_6.jpg', 3, 6),
(18, 'l_hotel_des_monnaies_niel_7.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_7.jpg', 3, 7),
(19, 'l_hotel_des_monnaies_niel_8.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_8.jpg', 3, 8),
(20, 'l_hotel_des_monnaies_niel_9.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/l_hotel_des_monnaies_niel_9.jpg', 3, 9),
(21, 'residence_l_aygues_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_l_aygues_1.jpg', 4, 1),
(22, 'refectoire_de_coudoux_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_1.jpg', 5, 1),
(23, 'refectoire_de_coudoux_2.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_2.jpg', 5, 2),
(24, 'refectoire_de_coudoux_3.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_3.jpg', 5, 3),
(25, 'refectoire_de_coudoux_4.avif', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_4.avif', 5, 4),
(26, 'refectoire_de_coudoux_5.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_5.jpg', 5, 5),
(27, 'refectoire_de_coudoux_6.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_6.jpg', 5, 6),
(28, 'refectoire_de_coudoux_7.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/refectoire_de_coudoux_7.jpg', 5, 7),
(29, 'rehabilitation_du_college_paul_cezanne_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_1.jpg', 6, 1),
(30, 'rehabilitation_du_college_paul_cezanne_2.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_2.jpg', 6, 2),
(31, 'rehabilitation_du_college_paul_cezanne_3.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_3.jpg', 6, 3),
(32, 'rehabilitation_du_college_paul_cezanne_4.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_4.jpg', 6, 4),
(33, 'rehabilitation_du_college_paul_cezanne_5.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_5.jpg', 6, 5),
(34, 'rehabilitation_du_college_paul_cezanne_6.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/rehabilitation_du_college_paul_cezanne_6.jpg', 6, 6),
(35, 'residence_les_angevines_1.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_1.jpg', 7, 1),
(36, 'residence_les_angevines_2.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_2.jpg', 7, 2),
(37, 'residence_les_angevines_3.jpeg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_3.jpeg', 7, 3),
(38, 'residence_les_angevines_4.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_4.jpg', 7, 4),
(39, 'residence_les_angevines_5.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_5.jpg', 7, 5),
(40, 'residence_les_angevines_6.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_6.jpg', 7, 6),
(41, 'residence_les_angevines_7.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_7.jpg', 7, 7),
(42, 'residence_les_angevines_8.jpg', 'D:\\dev\\apsi\\APSI-WEB/pic/residence_les_angevines_8.jpg', 7, 8);
