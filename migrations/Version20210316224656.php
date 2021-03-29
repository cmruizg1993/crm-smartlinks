<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316224656 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje ADD siguiente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D0137E94FA1 FOREIGN KEY (siguiente_id) REFERENCES mensaje (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B631D0137E94FA1 ON mensaje (siguiente_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D0137E94FA1');
        $this->addSql('DROP INDEX UNIQ_9B631D0137E94FA1 ON mensaje');
        $this->addSql('ALTER TABLE mensaje DROP siguiente_id');
    }
}
