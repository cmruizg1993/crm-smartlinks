<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221105171915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, razon_social VARCHAR(255) DEFAULT NULL, nombre_comercial VARCHAR(255) DEFAULT NULL, ruc VARCHAR(13) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telefono VARCHAR(15) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, obligado_contabilidad VARCHAR(2) NOT NULL, p12_name VARCHAR(255) DEFAULT NULL, p12_password VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('ALTER TABLE usuario ADD empresa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05D521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('CREATE INDEX IDX_2265B05D521E1991 ON usuario (empresa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05D521E1991');
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, razon_social VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nombre_comercial VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ruc VARCHAR(13) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, telefono VARCHAR(15) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, direccion VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, obligado_contabilidad VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, p12_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, p12_password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP INDEX IDX_2265B05D521E1991 ON usuario');
        $this->addSql('ALTER TABLE usuario DROP empresa_id');
    }
}
