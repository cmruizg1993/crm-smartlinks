<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407202752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orden_seriado (orden_id INT NOT NULL, seriado_id INT NOT NULL, INDEX IDX_F4EEEB549750851F (orden_id), INDEX IDX_F4EEEB544D04FBD8 (seriado_id), PRIMARY KEY(orden_id, seriado_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orden_seriado ADD CONSTRAINT FK_F4EEEB549750851F FOREIGN KEY (orden_id) REFERENCES orden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE orden_seriado ADD CONSTRAINT FK_F4EEEB544D04FBD8 FOREIGN KEY (seriado_id) REFERENCES seriado (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE orden_seriado');
    }
}
