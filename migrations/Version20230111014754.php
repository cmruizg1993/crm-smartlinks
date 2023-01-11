<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111014754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cuota_cuenta (id INT AUTO_INCREMENT NOT NULL, cuenta_id INT NOT NULL, fecha_vencimiento DATE NOT NULL, valor NUMERIC(10, 2) NOT NULL, recargo NUMERIC(10, 2) DEFAULT NULL, observaciones LONGTEXT DEFAULT NULL, INDEX IDX_6F166C159AEFF118 (cuenta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cuota_cuenta ADD CONSTRAINT FK_6F166C159AEFF118 FOREIGN KEY (cuenta_id) REFERENCES cuenta_por_cobrar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cuota_cuenta');
    }
}
