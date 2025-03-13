-- Contient les données de la table user et plus bas celle de la table user_pokemon
-- Déchargement des données de la table `user` --
--/!\ Mot de passe des deux comptes : esicad2025 --
INSERT INTO `user` (
        `IdUser`,
        `Nom`,
        `Prenom`,
        `Login`,
        `PasswordHash`
    )
VALUES (
        1,
        'Cesar',
        'Ramirez',
        'cesar',
        '$2y$10$AXKQWZtTF7gQj58bMKt5QutaLfzV.UwvQE69FoXYTFcm3J4IgON8y'
    ),
    (
        2,
        'test',
        'mctest',
        'testy',
        '$2y$10$fFmrw.AkM1rHeYPHNUrgLeiUWja90jgNjBI4ctgw/1GrSxwrcjeEm'
    );
COMMIT;
--
-- Déchargement des données de la table `user_pokemon`
--
INSERT INTO `user_pokemon` (
        `IdCapture`,
        `IdUser`,
        `IdPokemon`,
        `DateCapture`
    )
VALUES (33, 1, 10, '2025-03-13 10:45:11'),
    (32, 1, 5, '2025-03-13 10:45:10'),
    (31, 1, 1, '2025-03-13 10:45:08'),
    (34, 1, 16, '2025-03-13 10:45:13'),
    (35, 1, 19, '2025-03-13 10:45:15'),
    (36, 1, 69, '2025-03-13 10:45:19');
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;