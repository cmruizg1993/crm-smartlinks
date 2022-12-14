<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220928041056 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE comision');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comision (id INT AUTO_INCREMENT NOT NULL, orden_id INT NOT NULL, porcentaje DOUBLE PRECISION NOT NULL, limite_inferior INT NOT NULL, limite_superior INT NOT NULL, INDEX IDX_1013896F9750851F (orden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comision ADD CONSTRAINT FK_1013896F9750851F FOREIGN KEY (orden_id) REFERENCES tipo_orden (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
