<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027042920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura ADD punto_emision_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009742B70B6 FOREIGN KEY (punto_emision_id) REFERENCES punto_emision (id)');
        $this->addSql('CREATE INDEX IDX_F9EBA009742B70B6 ON factura (punto_emision_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA009742B70B6');
        $this->addSql('DROP INDEX IDX_F9EBA009742B70B6 ON factura');
        $this->addSql('ALTER TABLE factura DROP punto_emision_id');
    }
}
