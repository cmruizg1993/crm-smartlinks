<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221019043912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP INDEX UNIQ_B1354EA171CAA3E7, ADD INDEX IDX_B1354EA171CAA3E7 (servicio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP INDEX IDX_B1354EA171CAA3E7, ADD UNIQUE INDEX UNIQ_B1354EA171CAA3E7 (servicio_id)');
    }
}
