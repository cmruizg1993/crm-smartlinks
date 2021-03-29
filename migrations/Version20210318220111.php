<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318220111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planes_claro ADD servicio_id INT NOT NULL');
        $this->addSql('ALTER TABLE planes_claro ADD CONSTRAINT FK_31F52BDB71CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio_claro (id)');
        $this->addSql('CREATE INDEX IDX_31F52BDB71CAA3E7 ON planes_claro (servicio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planes_claro DROP FOREIGN KEY FK_31F52BDB71CAA3E7');
        $this->addSql('DROP INDEX IDX_31F52BDB71CAA3E7 ON planes_claro');
        $this->addSql('ALTER TABLE planes_claro DROP servicio_id');
    }
}
