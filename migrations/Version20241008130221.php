<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008130221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D97A77B84');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66DDCED588B');
        $this->addSql('DROP INDEX IDX_1981A66DDCED588B ON operation');
        $this->addSql('DROP INDEX IDX_1981A66D97A77B84 ON operation');
        $this->addSql('ALTER TABLE operation DROP famille_id, DROP comptes_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation ADD famille_id INT NOT NULL, ADD comptes_id INT NOT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66DDCED588B FOREIGN KEY (comptes_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_1981A66DDCED588B ON operation (comptes_id)');
        $this->addSql('CREATE INDEX IDX_1981A66D97A77B84 ON operation (famille_id)');
    }
}
