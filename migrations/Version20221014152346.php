<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014152346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_66696523E899029B');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0E899029B');
        $this->addSql('DROP TABLE servicio');
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_66696523E899029B');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE servicio ADD activo TINYINT(1) DEFAULT NULL, ADD costo DOUBLE PRECISION DEFAULT NULL, CHANGE nombre nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0E899029B');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE servicio (id INT AUTO_INCREMENT NOT NULL, servicio_id INT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, activo TINYINT(1) DEFAULT NULL, costo DOUBLE PRECISION DEFAULT NULL, INDEX IDX_DD5A5B7D71CAA3E7 (servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_DD5A5B7D71CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_66696523E899029B');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE servicio DROP activo, DROP costo, CHANGE nombre nombre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0E899029B');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
