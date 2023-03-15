<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314192511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador ADD punto_emision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE colaborador ADD CONSTRAINT FK_D2F80BB3742B70B6 FOREIGN KEY (punto_emision_id) REFERENCES punto_emision (id)');
        $this->addSql('CREATE INDEX IDX_D2F80BB3742B70B6 ON colaborador (punto_emision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3742B70B6');
        $this->addSql('DROP INDEX IDX_D2F80BB3742B70B6 ON colaborador');
        $this->addSql('ALTER TABLE colaborador DROP punto_emision_id');
    }
}
