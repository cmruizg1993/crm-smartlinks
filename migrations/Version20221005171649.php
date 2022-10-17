<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005171649 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato_planes_claro DROP FOREIGN KEY FK_228BA9DB70AE7BF1');
        $this->addSql('ALTER TABLE contrato_planes_claro DROP FOREIGN KEY FK_228BA9DB5C3AE42A');
        $this->addSql('ALTER TABLE planes_claro DROP FOREIGN KEY FK_31F52BDB71CAA3E7');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP TABLE contrato_planes_claro');
        $this->addSql('DROP TABLE planes_claro');
        $this->addSql('DROP TABLE servicio_claro');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrato (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, instalador_id INT DEFAULT NULL, fecha DATE NOT NULL, INDEX IDX_66696523DE734E51 (cliente_id), INDEX IDX_66696523EEBF8695 (instalador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE contrato_planes_claro (contrato_id INT NOT NULL, planes_claro_id INT NOT NULL, INDEX IDX_228BA9DB5C3AE42A (planes_claro_id), INDEX IDX_228BA9DB70AE7BF1 (contrato_id), PRIMARY KEY(contrato_id, planes_claro_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE planes_claro (id INT AUTO_INCREMENT NOT NULL, servicio_id INT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_31F52BDB71CAA3E7 (servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE servicio_claro (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523EEBF8695 FOREIGN KEY (instalador_id) REFERENCES colaborador (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE contrato_planes_claro ADD CONSTRAINT FK_228BA9DB5C3AE42A FOREIGN KEY (planes_claro_id) REFERENCES planes_claro (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrato_planes_claro ADD CONSTRAINT FK_228BA9DB70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE planes_claro ADD CONSTRAINT FK_31F52BDB71CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio_claro (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
