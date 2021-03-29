<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325152007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente ADD fecha_nacimiento DATE DEFAULT NULL, ADD fingerprint VARCHAR(255) DEFAULT NULL, ADD genero VARCHAR(1) DEFAULT NULL, ADD nacionalidad VARCHAR(255) DEFAULT NULL, ADD residencia VARCHAR(255) DEFAULT NULL, ADD direccion VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente DROP fecha_nacimiento, DROP fingerprint, DROP genero, DROP nacionalidad, DROP residencia, DROP direccion');
    }
}
