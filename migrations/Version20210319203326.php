<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319203326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden ADD tecnico_id INT NOT NULL');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD7841DB1E7 FOREIGN KEY (tecnico_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE INDEX IDX_E128CFD7841DB1E7 ON orden (tecnico_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD7841DB1E7');
        $this->addSql('DROP INDEX IDX_E128CFD7841DB1E7 ON orden');
        $this->addSql('ALTER TABLE orden DROP tecnico_id');
    }
}
