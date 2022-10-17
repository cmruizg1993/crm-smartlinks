<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003181248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punto_emision ADD establecimiento_id INT NOT NULL');
        $this->addSql('ALTER TABLE punto_emision ADD CONSTRAINT FK_7490553171B61351 FOREIGN KEY (establecimiento_id) REFERENCES establecimiento (id)');
        $this->addSql('CREATE INDEX IDX_7490553171B61351 ON punto_emision (establecimiento_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punto_emision DROP FOREIGN KEY FK_7490553171B61351');
        $this->addSql('DROP INDEX IDX_7490553171B61351 ON punto_emision');
        $this->addSql('ALTER TABLE punto_emision DROP establecimiento_id');
    }
}
