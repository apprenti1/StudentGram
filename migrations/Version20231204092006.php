<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204092006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, ref_user_id INT DEFAULT NULL, offres_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, nom_entreprise VARCHAR(255) NOT NULL, fonction_employe VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, cp VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D19FA60637A8045 (ref_user_id), INDEX IDX_D19FA606C83CD9F (offres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, ref_user_id INT DEFAULT NULL, domaine_etude VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_717E22E3637A8045 (ref_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, salle_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, duree TIME NOT NULL, valide TINYINT(1) NOT NULL, INDEX IDX_B26681EDC304035 (salle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, ref_entreprise_id INT NOT NULL, ref_type_contrat_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image LONGTEXT DEFAULT NULL, valid TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_AF86866F80FEF88A (ref_entreprise_id), INDEX IDX_AF86866F95D25B9A (ref_type_contrat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, statut TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_offre (id INT AUTO_INCREMENT NOT NULL, ref_user_id INT NOT NULL, ref_offre_id INT NOT NULL, cv LONGTEXT NOT NULL, motivation LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_406FFD0C637A8045 (ref_user_id), UNIQUE INDEX UNIQ_406FFD0CCADF96DD (ref_offre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, nombre_de_place INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statut (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, etudiant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, note INT DEFAULT NULL, photo_profil LONGTEXT DEFAULT NULL, ref_statut INT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649A4AEAFEA (entreprise_id), INDEX IDX_8D93D649DDEAB1A3 (etudiant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60637A8045 FOREIGN KEY (ref_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA606C83CD9F FOREIGN KEY (offres_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3637A8045 FOREIGN KEY (ref_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F80FEF88A FOREIGN KEY (ref_entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F95D25B9A FOREIGN KEY (ref_type_contrat_id) REFERENCES type_contrat (id)');
        $this->addSql('ALTER TABLE reponse_offre ADD CONSTRAINT FK_406FFD0C637A8045 FOREIGN KEY (ref_user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE reponse_offre ADD CONSTRAINT FK_406FFD0CCADF96DD FOREIGN KEY (ref_offre_id) REFERENCES offre (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60637A8045');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA606C83CD9F');
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3637A8045');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EDC304035');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F80FEF88A');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F95D25B9A');
        $this->addSql('ALTER TABLE reponse_offre DROP FOREIGN KEY FK_406FFD0C637A8045');
        $this->addSql('ALTER TABLE reponse_offre DROP FOREIGN KEY FK_406FFD0CCADF96DD');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649A4AEAFEA');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DDEAB1A3');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE reponse_offre');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP TABLE statut');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
