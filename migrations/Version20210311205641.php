<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311205641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador ADD cargo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE colaborador ADD CONSTRAINT FK_D2F80BB3813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id)');
        $this->addSql('CREATE INDEX IDX_D2F80BB3813AC380 ON colaborador (cargo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3813AC380');
        $this->addSql('DROP INDEX IDX_D2F80BB3813AC380 ON colaborador');
        $this->addSql('ALTER TABLE colaborador DROP cargo_id');
    }
}
