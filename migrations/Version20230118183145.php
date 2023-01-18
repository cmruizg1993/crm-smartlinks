<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118183145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuenta_por_cobrar ADD fecha_crecion DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE orden DROP serial_modem, DROP serial_radio');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuenta_por_cobrar DROP fecha_crecion');
        $this->addSql('ALTER TABLE orden ADD serial_modem VARCHAR(255) DEFAULT NULL, ADD serial_radio VARCHAR(255) DEFAULT NULL');
    }
}
