<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260717114642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('DROP INDEX uq_login ON administrateur');
        $this->addSql('ALTER TABLE administrateur CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('DROP INDEX idx_copain_nom ON copain');
        $this->addSql('ALTER TABLE copain MODIFY id_copain INT NOT NULL');
        $this->addSql('ALTER TABLE copain CHANGE id_copain id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY `fk_emprunt_copain`');
        $this->addSql('ALTER TABLE emprunt DROP FOREIGN KEY `fk_emprunt_livre`');
        $this->addSql('DROP INDEX idx_emprunt_livre ON emprunt');
        $this->addSql('DROP INDEX idx_emprunt_copain ON emprunt');
        $this->addSql('ALTER TABLE emprunt MODIFY id_emprunt INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt CHANGE id_livre id_livre INT NOT NULL, CHANGE id_copain id_copain INT NOT NULL, CHANGE date_retour date_retour DATE DEFAULT NULL, CHANGE id_emprunt id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('DROP INDEX idx_livre_auteur ON livre');
        $this->addSql('DROP INDEX idx_livre_prete ON livre');
        $this->addSql('DROP INDEX idx_livre_titre ON livre');
        $this->addSql('ALTER TABLE livre MODIFY id_livre INT NOT NULL');
        $this->addSql('ALTER TABLE livre CHANGE annee_edition annee_edition INT NOT NULL, CHANGE est_prete est_prete TINYINT NOT NULL, CHANGE id_livre id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY `fk_session_admin`');
        $this->addSql('DROP INDEX idx_session_expiration ON session');
        $this->addSql('DROP INDEX uq_token ON session');
        $this->addSql('DROP INDEX fk_session_admin ON session');
        $this->addSql('ALTER TABLE session MODIFY id_session INT NOT NULL');
        $this->addSql('ALTER TABLE session CHANGE id_admin id_admin INT NOT NULL, CHANGE donnees donnees JSON DEFAULT NULL, CHANGE id_session id INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE administrateur CHANGE mot_de_passe mot_de_passe VARCHAR(255) NOT NULL COMMENT \'Hash bcrypt généré par Symfony\', CHANGE roles roles JSON NOT NULL COMMENT \'Ex: ["ROLE_ADMIN"]\'');
        $this->addSql('CREATE UNIQUE INDEX uq_login ON administrateur (login)');
        $this->addSql('ALTER TABLE copain MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE copain CHANGE id id_copain INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_copain)');
        $this->addSql('CREATE INDEX idx_copain_nom ON copain (nom)');
        $this->addSql('ALTER TABLE emprunt MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE emprunt CHANGE id_livre id_livre INT NOT NULL COMMENT \'FK → livre(id_livre)\', CHANGE id_copain id_copain INT NOT NULL COMMENT \'FK → copain(id_copain)\', CHANGE date_retour date_retour DATE NOT NULL, CHANGE id id_emprunt INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_emprunt)');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT `fk_emprunt_copain` FOREIGN KEY (id_copain) REFERENCES copain (id_copain) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE emprunt ADD CONSTRAINT `fk_emprunt_livre` FOREIGN KEY (id_livre) REFERENCES livre (id_livre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX idx_emprunt_livre ON emprunt (id_livre)');
        $this->addSql('CREATE INDEX idx_emprunt_copain ON emprunt (id_copain)');
        $this->addSql('ALTER TABLE livre MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE livre CHANGE annee_edition annee_edition DATE NOT NULL, CHANGE est_prete est_prete TINYINT DEFAULT 0 NOT NULL COMMENT \'0 = disponible, 1 = prêté\', CHANGE id id_livre INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_livre)');
        $this->addSql('CREATE INDEX idx_livre_auteur ON livre (auteur)');
        $this->addSql('CREATE INDEX idx_livre_prete ON livre (est_prete)');
        $this->addSql('CREATE INDEX idx_livre_titre ON livre (titre)');
        $this->addSql('ALTER TABLE session MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE session CHANGE id_admin id_admin INT NOT NULL COMMENT \'FK → administrateur(id_admin)\', CHANGE donnees donnees JSON DEFAULT NULL COMMENT \'Données de session sérialisées\', CHANGE id id_session INT AUTO_INCREMENT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id_session)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT `fk_session_admin` FOREIGN KEY (id_admin) REFERENCES administrateur (id_admin) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX idx_session_expiration ON session (expiration)');
        $this->addSql('CREATE UNIQUE INDEX uq_token ON session (token)');
        $this->addSql('CREATE INDEX fk_session_admin ON session (id_admin)');
    }
}
