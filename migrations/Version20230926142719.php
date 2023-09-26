<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926142719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stagiaire_session (stagiaire_id INT NOT NULL, session_id INT NOT NULL, INDEX IDX_D32D02D4BBA93DD6 (stagiaire_id), INDEX IDX_D32D02D4613FECDF (session_id), PRIMARY KEY(stagiaire_id, session_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stagiaire_session ADD CONSTRAINT FK_D32D02D4BBA93DD6 FOREIGN KEY (stagiaire_id) REFERENCES stagiaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stagiaire_session ADD CONSTRAINT FK_D32D02D4613FECDF FOREIGN KEY (session_id) REFERENCES session (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stagiaire_session DROP FOREIGN KEY FK_D32D02D4BBA93DD6');
        $this->addSql('ALTER TABLE stagiaire_session DROP FOREIGN KEY FK_D32D02D4613FECDF');
        $this->addSql('DROP TABLE stagiaire_session');
    }
}
