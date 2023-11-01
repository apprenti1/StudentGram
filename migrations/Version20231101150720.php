<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231101150720 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant ADD ref_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etudiant ADD CONSTRAINT FK_717E22E3637A8045 FOREIGN KEY (ref_user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_717E22E3637A8045 ON etudiant (ref_user_id)');
        $this->addSql('ALTER TABLE user ADD etudiant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649DDEAB1A3 ON user (etudiant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etudiant DROP FOREIGN KEY FK_717E22E3637A8045');
        $this->addSql('DROP INDEX UNIQ_717E22E3637A8045 ON etudiant');
        $this->addSql('ALTER TABLE etudiant DROP ref_user_id');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649DDEAB1A3');
        $this->addSql('DROP INDEX IDX_8D93D649DDEAB1A3 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP etudiant_id');
    }
}
