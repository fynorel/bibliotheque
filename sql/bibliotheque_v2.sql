-- ============================================================
--  MPD — Modèle Physique de Données
--  Système Bibliothèque — PHP / Symfony · MySQL / MariaDB
--  Généré le : 2026-03-08
-- ============================================================

-- ── Création de la base ──────────────────────────────────────
CREATE DATABASE IF NOT EXISTS bibliotheque
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bibliotheque;

-- ── Suppression dans l'ordre inverse des dépendances ────────
DROP TABLE IF EXISTS emprunt;
DROP TABLE IF EXISTS session;
DROP TABLE IF EXISTS copain;
DROP TABLE IF EXISTS livre;
DROP TABLE IF EXISTS administrateur;

-- ============================================================
--  TABLE : livre
--  Entité principale du système.
--  est_prete est géré applicativement par Symfony (PretController).
-- ============================================================
CREATE TABLE livre (
    id_livre      INT            NOT NULL AUTO_INCREMENT,
    titre         VARCHAR(255)   NOT NULL,
    genre         VARCHAR(100)   NOT NULL,
    auteur        VARCHAR(255)   NOT NULL,
    editeur       VARCHAR(255)   NOT NULL,
    annee_edition YEAR           NOT NULL,
    est_prete     TINYINT(1)     NOT NULL DEFAULT 0
                  COMMENT '0 = disponible, 1 = prêté',

    CONSTRAINT pk_livre PRIMARY KEY (id_livre)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Catalogue des livres de la bibliothèque personnelle';


-- ============================================================
--  TABLE : copain
--  Représente les emprunteurs (amis du propriétaire).
-- ============================================================
CREATE TABLE copain (
    id_copain INT          NOT NULL AUTO_INCREMENT,
    nom       VARCHAR(100) NOT NULL,
    prenom    VARCHAR(100) NOT NULL,

    CONSTRAINT pk_copain PRIMARY KEY (id_copain)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Personnes à qui des livres peuvent être prêtés';


-- ============================================================
--  TABLE : emprunt
--  Issue de l'association EMPRUNTER (0,N — 0,N) du MCD.
--  Chaque ligne = un prêt d'un livre à un copain.
-- ============================================================
CREATE TABLE emprunt (
    id_emprunt   INT  NOT NULL AUTO_INCREMENT,
    id_livre     INT  NOT NULL
                 COMMENT 'FK → livre(id_livre)',
    id_copain    INT  NOT NULL
                 COMMENT 'FK → copain(id_copain)',
    date_emprunt DATE NOT NULL,
    date_retour  DATE NOT NULL,

    CONSTRAINT pk_emprunt    PRIMARY KEY (id_emprunt),
    CONSTRAINT fk_emprunt_livre  FOREIGN KEY (id_livre)
        REFERENCES livre(id_livre)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_emprunt_copain FOREIGN KEY (id_copain)
        REFERENCES copain(id_copain)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Historique de tous les prêts de livres';


-- ============================================================
--  TABLE : administrateur
--  Compte unique de l'administrateur de la bibliothèque.
--  Le mot de passe est stocké hashé (bcrypt via Symfony Security).
-- ============================================================
CREATE TABLE administrateur (
    id_admin     INT          NOT NULL AUTO_INCREMENT,
    login        VARCHAR(100) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
                 COMMENT 'Hash bcrypt généré par Symfony',
    roles        JSON         NOT NULL
                 COMMENT 'Ex: ["ROLE_ADMIN"]',

    CONSTRAINT pk_admin   PRIMARY KEY (id_admin),
    CONSTRAINT uq_login   UNIQUE (login)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Compte administrateur unique (1 seul enregistrement attendu)';


-- ============================================================
--  TABLE : session
--  Issue de l'association OUVRE SESSION (1,1 — 0,1) du MCD.
--  Gérée par Symfony Session / Security component.
-- ============================================================
CREATE TABLE session (
    id_session INT          NOT NULL AUTO_INCREMENT,
    id_admin   INT          NOT NULL
               COMMENT 'FK → administrateur(id_admin)',
    token      VARCHAR(255) NOT NULL,
    expiration DATETIME     NOT NULL,
    donnees    JSON         NULL
               COMMENT 'Données de session sérialisées',

    CONSTRAINT pk_session      PRIMARY KEY (id_session),
    CONSTRAINT uq_token        UNIQUE (token),
    CONSTRAINT fk_session_admin FOREIGN KEY (id_admin)
        REFERENCES administrateur(id_admin)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
  COMMENT='Sessions actives de l administrateur';


-- ============================================================
--  INDEX supplémentaires (performances)
-- ============================================================

-- Recherche de livres par titre, auteur
CREATE INDEX idx_livre_titre  ON livre(titre);
CREATE INDEX idx_livre_auteur ON livre(auteur);
CREATE INDEX idx_livre_prete  ON livre(est_prete);

-- Recherche d'emprunts par copain ou livre
CREATE INDEX idx_emprunt_livre  ON emprunt(id_livre);
CREATE INDEX idx_emprunt_copain ON emprunt(id_copain);

-- Recherche de copains par nom
CREATE INDEX idx_copain_nom ON copain(nom);

-- Nettoyage des sessions expirées
CREATE INDEX idx_session_expiration ON session(expiration);


-- ============================================================
--  DONNÉES INITIALES — Compte administrateur par défaut
--  IMPORTANT : remplacer le hash par le vrai hash bcrypt
--  généré via : php bin/console security:hash-password
-- ============================================================
INSERT INTO administrateur (login, mot_de_passe, roles)
VALUES (
    'bruno',
    '$2y$13$PLACEHOLDER_REMPLACER_PAR_VRAI_HASH_BCRYPT',
    '["ROLE_ADMIN"]'
);


-- ============================================================
--  VÉRIFICATION — Affiche la structure des tables créées
-- ============================================================
SHOW TABLES;


-- ============================================================
--  MISE À JOUR v2 — US7 Supprimer un livre
--  Modifier la FK pour autoriser la suppression en cascade
-- ============================================================
-- À exécuter si la base est déjà créée avec la v1 :
ALTER TABLE emprunt DROP FOREIGN KEY fk_emprunt_livre;

ALTER TABLE emprunt ADD CONSTRAINT fk_emprunt_livre
    FOREIGN KEY (id_livre)
    REFERENCES livre(id_livre)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
