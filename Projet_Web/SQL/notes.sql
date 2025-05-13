-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : lun. 12 mai 2025 à 02:40
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `notes`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `administrator`
--

INSERT INTO `administrator` (`id`, `username`, `password`, `name`) VALUES
(1, 'admin', '$2y$10$Uw162yZi6/bepDsZUhP3g.8VVAUHdPtxKCQLmTmJLhRcksNW3Ghny', 'admin'),
(4, 'med1', '$2y$10$PWw3C/Hf5t04cOPZVaFgYe2lNSxh.7KhoDXJXiUP8dt0LHJaCZLMK', 'Moha ');

-- --------------------------------------------------------

--
-- Structure de la table `archived_notes`
--

CREATE TABLE `archived_notes` (
  `id` int(11) NOT NULL,
  `original_id` int(11) DEFAULT NULL,
  `student_username` varchar(100) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `note` decimal(5,2) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `archived_notes`
--

INSERT INTO `archived_notes` (`id`, `original_id`, `student_username`, `module`, `note`, `deleted_at`) VALUES
(1, 2, 'mohammed', 'Web', 17.00, '2025-05-05 18:30:07');

-- --------------------------------------------------------

--
-- Structure de la table `deleted_students`
--

CREATE TABLE `deleted_students` (
  `id` int(11) NOT NULL,
  `original_id` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `module`
--

CREATE TABLE `module` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `student_username` varchar(100) NOT NULL,
  `module` varchar(100) NOT NULL,
  `note` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planning`
--

CREATE TABLE `planning` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `event_title` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `event_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `planning`
--

INSERT INTO `planning` (`id`, `student_id`, `event_title`, `event_date`, `event_time`, `event_type`, `event_description`) VALUES
(7, 13, 'Team Meeting', '2025-05-15', '14:00:00', 'meeting', 'Discuss project progress and next steps.'),
(8, 13, 'Math Exam', '2025-05-19', '10:00:00', 'exam', 'Study chapters 1 to 5 for the math exam.'),
(9, 13, 'Biology Assignment', '2025-05-24', '23:30:00', 'assignment', 'Complete the report on cell structure.'),
(10, 13, 'Personal Doctor Appointment', '2025-05-21', '09:30:00', 'personal', 'Routine health check-up.');

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `student_username` varchar(100) DEFAULT NULL,
  `module` varchar(100) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `quiz_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `id_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `student`
--

INSERT INTO `student` (`id`, `username`, `password`, `name`, `id_admin`) VALUES
(13, 'mohammed', '$2y$10$PGV/ZbvMdG5gyfWScY55QOeBm37mcVJJb.YlGR8DK0yLinkVVEFD.', 'Moahammed', 1),
(18, 'jad', '$2y$10$ReTtsQdkDM2qOOOj/pqUuOslEdFNMrBFwloAnqueVAP7t2imXAhXC', 'Jad Bo', 1),
(19, 'ahmed', '$2y$10$u8diYLDLuM4PYHzjSYXNH.9NgKRV32e9nDbotQ0gcNhFp2pJZaOHy', 'Ahmed', 1),
(20, 'riham', '$2y$10$4zTR/SqdirW9uliG4UfsveF7/id9gOLJcsnUrNPouNDM7kJGpCuA6', 'Riham', 1),
(22, 'hajar', '$2y$10$aPJO0ErFuN0WlroPZN83TO4UfQsH.//EjScvTNHW5ojEYpNKSObGi', 'Hajar', 1),
(24, 'aya', '$2y$10$7BkoyBxnjzxYF.YLiqGp6.zcEI5IIx8M2ahuZMXNU6jlQTrrW9nMG', 'Aya', 1),
(25, 'johndoe123', '$2y$10$EAY9oIcdWpTuscG9a4Ywg.rq7HNb6pOcEXZORfx6Sn8vpwKTqwihS', 'John Doe', 1);

-- --------------------------------------------------------

--
-- Structure de la table `student_notes`
--

CREATE TABLE `student_notes` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `student_notes`
--

INSERT INTO `student_notes` (`id`, `student_id`, `module`, `title`, `content`, `created_at`, `updated_at`) VALUES
(7, 13, 'Web Development', 'Introduction to HTML', 'HTML (HyperText Markup Language) is the standard markup language used to create web pages. It structures content using tags such as <html>, <head>, and <body>. Key elements include headings, paragraphs, images, and links.', '2025-05-09 18:13:41', '2025-05-09 18:13:41'),
(8, 13, 'Mathematics', 'Quadratic Formula', 'The quadratic formula helps to solve equations like ax squared plus bx plus c equals zero. To find the solutions, you use this formula: x equals negative b plus or minus the square root of b squared minus 4 times a times c, all divided by 2 times a.', '2025-05-11 18:59:11', '2025-05-11 18:59:11'),
(9, 13, 'Biology', 'Photosynthesis', 'Photosynthesis is the process by which green plants make their own food. They use sunlight, water, and carbon dioxide to produce oxygen and glucose, which is their source of energy.', '2025-05-11 18:59:55', '2025-05-11 18:59:55');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Index pour la table `archived_notes`
--
ALTER TABLE `archived_notes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `deleted_students`
--
ALTER TABLE `deleted_students`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `module`
--
ALTER TABLE `module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_username` (`student_username`);

--
-- Index pour la table `planning`
--
ALTER TABLE `planning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Index pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_username` (`student_username`);

--
-- Index pour la table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Index pour la table `student_notes`
--
ALTER TABLE `student_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `archived_notes`
--
ALTER TABLE `archived_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `deleted_students`
--
ALTER TABLE `deleted_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `module`
--
ALTER TABLE `module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `planning`
--
ALTER TABLE `planning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `student_notes`
--
ALTER TABLE `student_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`student_username`) REFERENCES `student` (`username`) ON DELETE CASCADE;

--
-- Contraintes pour la table `planning`
--
ALTER TABLE `planning`
  ADD CONSTRAINT `planning_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`);

--
-- Contraintes pour la table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`student_username`) REFERENCES `student` (`username`) ON DELETE CASCADE;

--
-- Contraintes pour la table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administrator` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `student_notes`
--
ALTER TABLE `student_notes`
  ADD CONSTRAINT `fk_student_note` FOREIGN KEY (`student_id`) REFERENCES `student` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
