<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318225136 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estado_orden (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, codigo VARCHAR(1) NOT NULL, UNIQUE INDEX UNIQ_EBD3492520332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orden ADD tipo_id INT NOT NULL, ADD san_id INT NOT NULL, ADD estado_id INT NOT NULL, ADD fecha DATE NOT NULL');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD7A9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_orden (id)');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD7DADF1C23 FOREIGN KEY (san_id) REFERENCES san (id)');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD79F5A440B FOREIGN KEY (estado_id) REFERENCES estado_orden (id)');
        $this->addSql('CREATE INDEX IDX_E128CFD7A9276E6C ON orden (tipo_id)');
        $this->addSql('CREATE INDEX IDX_E128CFD7DADF1C23 ON orden (san_id)');
        $this->addSql('CREATE INDEX IDX_E128CFD79F5A440B ON orden (estado_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD79F5A440B');
        $this->addSql('DROP TABLE estado_orden');
        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD7A9276E6C');
        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD7DADF1C23');
        $this->addSql('DROP INDEX IDX_E128CFD7A9276E6C ON orden');
        $this->addSql('DROP INDEX IDX_E128CFD7DADF1C23 ON orden');
        $this->addSql('DROP INDEX IDX_E128CFD79F5A440B ON orden');
        $this->addSql('ALTER TABLE orden DROP tipo_id, DROP san_id, DROP estado_id, DROP fecha');
    }
}
