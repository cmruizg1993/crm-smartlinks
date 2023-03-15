<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314173857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secuencial ADD punto_emision_id INT NOT NULL');
        $this->addSql('ALTER TABLE secuencial ADD CONSTRAINT FK_207842C0742B70B6 FOREIGN KEY (punto_emision_id) REFERENCES punto_emision (id)');
        $this->addSql('CREATE INDEX IDX_207842C0742B70B6 ON secuencial (punto_emision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE secuencial DROP FOREIGN KEY FK_207842C0742B70B6');
        $this->addSql('DROP INDEX IDX_207842C0742B70B6 ON secuencial');
        $this->addSql('ALTER TABLE secuencial DROP punto_emision_id');
    }
}
