<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011154223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden_seriado DROP FOREIGN KEY FK_F4EEEB544D04FBD8');
        $this->addSql('CREATE TABLE equipo_instalacion (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, contrato_id INT NOT NULL, serie VARCHAR(255) DEFAULT NULL, observacion VARCHAR(255) DEFAULT NULL, INDEX IDX_4E949FE623BFBED (equipo_id), INDEX IDX_4E949FE670AE7BF1 (contrato_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unidades (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(2) NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipo_instalacion ADD CONSTRAINT FK_4E949FE623BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE equipo_instalacion ADD CONSTRAINT FK_4E949FE670AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('DROP TABLE orden_seriado');
        $this->addSql('DROP TABLE seriado');
        $this->addSql('ALTER TABLE equipo CHANGE sku codigo VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orden_seriado (orden_id INT NOT NULL, seriado_id INT NOT NULL, INDEX IDX_F4EEEB544D04FBD8 (seriado_id), INDEX IDX_F4EEEB549750851F (orden_id), PRIMARY KEY(orden_id, seriado_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE seriado (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, serie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_4860F37A23BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE orden_seriado ADD CONSTRAINT FK_F4EEEB544D04FBD8 FOREIGN KEY (seriado_id) REFERENCES seriado (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orden_seriado ADD CONSTRAINT FK_F4EEEB549750851F FOREIGN KEY (orden_id) REFERENCES orden (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seriado ADD CONSTRAINT FK_4860F37A23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE equipo_instalacion');
        $this->addSql('DROP TABLE unidades');
        $this->addSql('ALTER TABLE equipo CHANGE codigo sku VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
