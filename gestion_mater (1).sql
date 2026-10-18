--

-- --------------------------------------------------------

--
-- Structure de la table `emprunt`
--

CREATE TABLE `emprunt` (
  `id` int(11) NOT NULL,
  `dateDeman` date NOT NULL,
  `dateEmprun` date NOT NULL,
  `nbJoursDemand` int(11) NOT NULL,
  `dateRetou` date NOT NULL,
  `nbJoursRetar` int(11) NOT NULL,
  `montantPenalit` float NOT NULL,
  `Etat` int(11) NOT NULL,
  `id_materiel` int(11) NOT NULL,
  `id_membr` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `emprunt`
--

INSERT INTO `emprunt` (`id`, `dateDeman`, `dateEmprun`, `nbJoursDemand`, `dateRetou`, `nbJoursRetar`, `montantPenalit`, `Etat`, `id_materiel`, `id_membr`) VALUES
(3, '2026-03-02', '2026-03-05', 5, '2026-03-16', 0, 0, 3, 10001, 10000),
(11, '2026-04-10', '2026-04-10', 5, '2026-04-15', 4, 12, 3, 10004, 10000),
(12, '2026-04-10', '2026-04-19', 6, '2026-04-16', 0, 0, 1, 10009, 10030),
(14, '2026-04-10', '2026-04-10', 9, '2026-04-19', 0, 0, 1, 10009, 10030),
(15, '2026-04-19', '0000-00-00', 6, '2026-04-25', 0, 0, 0, 10026, 10000),
(16, '2026-04-19', '0000-00-00', 2, '2026-04-21', 0, 0, 0, 10026, 15004);

-- --------------------------------------------------------

--
-- Structure de la table `materiel`
--

CREATE TABLE `materiel` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `materiel`
--

INSERT INTO `materiel` (`id`, `nom`, `description`, `image`, `etat`) VALUES
(10001, 'Projecteur Epson', 'Projecteur Epson HD', 'Epson.jpg', 1),
(10002, 'Scanner Epson', 'Scanner de documents A4', 'Scanner.jpg', 1),
(10003, 'Vidéoprojecteur Epson EB-X51', 'Vidéoprojecteur XGA 3800 lumens', 'epson_eb_x51.jpg', 0),
(10004, 'Appareil photo Canon EOS', 'Appareil photo  24MP', 'canon_eos.jpg', 0),
(10005, 'Caméra Sony 4K', 'Caméra de tournage 4K UHD', 'sony_4k.jpg', 1),
(10006, 'Drone DJI Mini 3', 'Drone caméra 4K stabilisé', 'dji_mini3.webp', 0),
(10007, 'Trépied Manfrotto', 'Trépied professionnel aluminium', 'trepied.jpg', 1),
(10008, 'Microphone Rode NT1', 'Microphone studio cardioïde', 'rode_nt1.jpg', 0),
(10009, 'Enceinte JBL Eon615', 'Enceinte sono 1000W', 'jbl_eon.jpg', 1),
(10010, 'Mixeur Yamaha MG10', 'Table de mixage 10 canaux', 'yamaha_mg10.jpg', 1),
(10015, 'Imprimante Canon PIXMA', 'Imprimante couleur A4 WiFi', 'canon_pixma.jpg', 0),
(10016, 'Perceuse', 'Perceuse Bosch 750W avec coffret d accessoires', 'perceuse.jpg', 0),
(10017, 'Machine à coudre', 'Singer Heavy Duty 4452 - 32 points de couture', 'machine-coudre.jpg', 1),
(10018, 'Guitare acoustique', 'Yamaha F310 - cordes acier', 'guitare.jpg', 1),
(10019, 'Tondeuse électrique', 'Tondeuse gazon Bosch Rotak 37V 1400W', 'tondeuse.jpg', 1),
(10020, 'Violon', 'Violon 4/4 idéal débutant avec archet et étui', 'violon.jpg', 1),
(10021, 'Perceuse visseuse', 'Makita 18V sans fil avec 2 batteries', 'perceuse_visseuse.jpg', 1),
(10022, 'Clavier piano', 'Yamaha PSR-E373 61 touches sensitives', 'clavier_piano.jpg', 1),
(10023, 'Tronçonneuse', 'Stihl MS 170 électrique 30cm guide', 'tronconneuse.jpg', 1),
(10025, 'Flûte traversière', 'Jupiter JFL700 flûte traversière argentée', 'flute.jpg', 1),
(10026, 'tondeuse  ', 'tondeuse  électrique,', '69e4cb56b81b3_tendeuse.jpg', 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `login`, `password`, `nom`, `email`, `tel`, `role`) VALUES
(10000, 'eyat24', '123456', 'EYA', 'trabelsieya562@gmail.com', '29696926', 0),
(10010, 'youssef246', '123987', 'Makniyousse', 'youssefM@GMAIL.com', '58384420', 0),
(10020, 'ahmed123', '123456', 'AHMED', 'ahmed123@gmail.com', '55123456', 0),
(10030, 'sarra456', '654321', 'SARRA', 'sarra456@gmail.com', '55234567', 0),
(10040, 'med789', '789456', 'MOHAMED', 'med789@gmail.com', '55345678', 0),
(10050, 'ines321', '321654', 'INES', 'ines321@gmail.com', '55456789', 0),
(11000, 'kenza', '2564985', 'KENZA', 'KENZA24@gmail.com', '25855082', 1),
(12000, 'houda', '265982', 'HODHOD', 'houdamakni@gmail.com', '25855561', 2),
(13000, 'admin2', '111222', 'ADMIN2', 'admin2@gmail.com', '55567890', 2),
(14000, 'gest1', '333444', 'GESTIONNAIRE1', 'gest1@gmail.com', '55678901', 1),
(15000, 'gest2', '555666', 'GESTIONNAIRE2', 'gest2@gmail.com', '55789012', 1),
(15001, 'kenza21', '$2y$10$khln6RhhC5865ndMZlXvhOi5JcaPxamls0c81J851mQ4c26370dMu', 'kenza Daoud', 'kd536@gmail.com', '25898759', 0),
(15002, 'souha25', '$2y$10$7LqqgOLBpT1yfcA0GS99AeIvrbGdT3sG52roSisgnBegARp/lEzK2', 'Souha yalmaz', 'souhafetah@gmail.com', '2598697', 0),
(15003, 'noursemaa58', '$2y$10$NhHAfAXsNxNy.iUlGVsXceVUb/L32VxHMw4.9EvW6Igfhbe2oNPo.', 'noursema yalmaz', 'nnnn@gmail.com', '2365897', 0),
(15004, 'rayen21', '$2y$10$QZb1KAWqNNo0XAF5YQpbTumjNLaTCNsbgEgzATnzmuIEcO5mN3p4u', 'rayen makni', 'rayenM25@gmail.com', '55741374', 0),
(15005, 'sawsan21', '$2y$10$8T66bVkxLZNFnPksTCQWsOwpmklenGD3XOYWcBSvLJRMoHUt03Fv2', 'sawsan zrafii', 'sawsan256@gmail.com', '25698725', 0),
(15006, 'mouna', '$2y$10$nCEW296yg7Yu9YUH891UquHPTJCyxRtSXxmcn1iqW1NvAY1bTuXQu', 'mouna makni', 'mouna25@gmail.com', '58976025', 0),
(15007, 'maher6', '$2y$10$fdAcXBUfrDABXBGj9ET9I.M0zsRkInSaGUoKtLe/R.PtRygXDe2hW', 'maher makni', 'megerP25@gmail.com', '58384420', 0),
(15008, 'sofien58', '$2y$10$UktNuhPsAfakNcpT2uPYVuA7wID1xbsyzYX9DC48G/ITMz/aLTUke', 'sofien makni', 'sofien@gmail.com', '52354420', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_membr` (`id_membr`),
  ADD KEY `id_materiel` (`id_materiel`);

--
-- Index pour la table `materiel`
--
ALTER TABLE `materiel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `emprunt`
--
ALTER TABLE `emprunt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `materiel`
--
ALTER TABLE `materiel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10027;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15009;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunt`
--
ALTER TABLE `emprunt`
  ADD CONSTRAINT `emprunt_ibfk_1` FOREIGN KEY (`id_membr`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emprunt_ibfk_2` FOREIGN KEY (`id_materiel`) REFERENCES `materiel` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
