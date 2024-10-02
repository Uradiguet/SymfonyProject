<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001124754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partage (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, compte_id INT NOT NULL, ecriture TINYINT(1) NOT NULL, INDEX IDX_8B929E6EFB88E14F (utilisateur_id), INDEX IDX_8B929E6EF2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6EFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE partage ADD CONSTRAINT FK_8B929E6EF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE operation ADD comptes_id INT NOT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DDCED588B FOREIGN KEY (comptes_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_1981A66DDCED588B ON operation (comptes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6EFB88E14F');
        $this->addSql('ALTER TABLE partage DROP FOREIGN KEY FK_8B929E6EF2C56620');
        $this->addSql('DROP TABLE partage');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DDCED588B');
        $this->addSql('DROP INDEX IDX_1981A66DDCED588B ON operation');
        $this->addSql('ALTER TABLE operation DROP comptes_id');
    }
}
