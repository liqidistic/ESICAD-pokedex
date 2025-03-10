-- Contient les données de la table user et plus bas celle de la table user_pokemon
-- Déchargement des données de la table `user` --
--/!\ Mot de passe : esicad2025 --
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
VALUES (21, 1, 1, '2025-03-10 12:32:52'),
    (22, 1, 25, '2025-03-10 12:32:55'),
    (23, 1, 1, '2025-03-10 12:51:01');
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;