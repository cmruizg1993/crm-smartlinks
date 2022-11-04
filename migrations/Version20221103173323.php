<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103173323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato ADD actualizado_por_id INT DEFAULT NULL, ADD fecha_actualizacion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523F6167A1C FOREIGN KEY (actualizado_por_id) REFERENCES usuario (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66696523F55AE19E ON contrato (numero)');
        $this->addSql('CREATE INDEX IDX_66696523F6167A1C ON contrato (actualizado_por_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_66696523F6167A1C');
        $this->addSql('DROP INDEX UNIQ_66696523F55AE19E ON contrato');
        $this->addSql('DROP INDEX IDX_66696523F6167A1C ON contrato');
        $this->addSql('ALTER TABLE contrato DROP actualizado_por_id, DROP fecha_actualizacion');
    }
}
