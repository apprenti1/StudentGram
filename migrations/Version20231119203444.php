<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119203444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE type_offre_emploi');
        $this->addSql('ALTER TABLE offre ADD ref_type_contrat_id INT NOT NULL, DROP type_de_contrat');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866F95D25B9A FOREIGN KEY (ref_type_contrat_id) REFERENCES type_contrat (id)');
        $this->addSql('CREATE INDEX IDX_AF86866F95D25B9A ON offre (ref_type_contrat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866F95D25B9A');
        $this->addSql('CREATE TABLE type_offre_emploi (id INT AUTO_INCREMENT NOT NULL, type_de_contrat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP INDEX IDX_AF86866F95D25B9A ON offre');
        $this->addSql('ALTER TABLE offre ADD type_de_contrat VARCHAR(255) NOT NULL, DROP ref_type_contrat_id');
    }
}
