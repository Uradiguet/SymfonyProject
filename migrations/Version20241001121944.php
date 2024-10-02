<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241001121944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, titulaire_id INT NOT NULL, nom VARCHAR(150) NOT NULL, solde DOUBLE PRECISION NOT NULL, INDEX IDX_CFF65260A10273AA (titulaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, libelle VARCHAR(50) NOT NULL, type_operation TINYINT(1) NOT NULL, INDEX IDX_2473F213FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, libelle VARCHAR(150) NOT NULL, montant DOUBLE PRECISION NOT NULL, type_operation TINYINT(1) NOT NULL, INDEX IDX_1981A66DF2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF65260A10273AA FOREIGN KEY (titulaire_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE famille ADD CONSTRAINT FK_2473F213FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF65260A10273AA');
        $this->addSql('ALTER TABLE famille DROP FOREIGN KEY FK_2473F213FB88E14F');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DF2C56620');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE operation');
    }
}
