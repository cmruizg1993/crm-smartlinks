<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210426012818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud ADD cuenta_bancaria_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0619BC045 FOREIGN KEY (cuenta_bancaria_id) REFERENCES cuenta_bancaria (id)');
        $this->addSql('CREATE INDEX IDX_96D27CC0619BC045 ON solicitud (cuenta_bancaria_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0619BC045');
        $this->addSql('DROP INDEX IDX_96D27CC0619BC045 ON solicitud');
        $this->addSql('ALTER TABLE solicitud DROP cuenta_bancaria_id');
    }
}
