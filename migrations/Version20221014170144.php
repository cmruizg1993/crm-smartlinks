<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014170144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE opcion_catalogo (id INT AUTO_INCREMENT NOT NULL, catalogo_id INT NOT NULL, codigo VARCHAR(20) NOT NULL, texto VARCHAR(255) NOT NULL, INDEX IDX_EAFBA634979D753 (catalogo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE opcion_catalogo ADD CONSTRAINT FK_EAFBA634979D753 FOREIGN KEY (catalogo_id) REFERENCES catalogo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE opcion_catalogo');
    }
}
