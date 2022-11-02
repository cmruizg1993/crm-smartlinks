<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026232235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estado_contrato ADD contrato_id INT NOT NULL');
        $this->addSql('ALTER TABLE estado_contrato ADD CONSTRAINT FK_979FD6CE70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('CREATE INDEX IDX_979FD6CE70AE7BF1 ON estado_contrato (contrato_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE estado_contrato DROP FOREIGN KEY FK_979FD6CE70AE7BF1');
        $this->addSql('DROP INDEX IDX_979FD6CE70AE7BF1 ON estado_contrato');
        $this->addSql('ALTER TABLE estado_contrato DROP contrato_id');
    }
}
