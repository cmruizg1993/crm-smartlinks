<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031204328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente ADD nombre_comercial VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE dni DROP FOREIGN KEY FK_7F8F253BA9276E6C');
        $this->addSql('DROP INDEX IDX_7F8F253BA9276E6C ON dni');
        $this->addSql('ALTER TABLE dni ADD tipo VARCHAR(2) DEFAULT NULL, DROP tipo_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente DROP nombre_comercial');
        $this->addSql('ALTER TABLE dni ADD tipo_id INT NOT NULL, DROP tipo');
        $this->addSql('ALTER TABLE dni ADD CONSTRAINT FK_7F8F253BA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_dni (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7F8F253BA9276E6C ON dni (tipo_id)');
    }
}
