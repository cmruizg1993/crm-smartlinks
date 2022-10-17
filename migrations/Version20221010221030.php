<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010221030 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador_proveedor DROP FOREIGN KEY FK_AC1A7AA7CB305D73');
        $this->addSql('ALTER TABLE servicio DROP FOREIGN KEY FK_CB86F22ACB305D73');
        $this->addSql('DROP TABLE colaborador_proveedor');
        $this->addSql('DROP TABLE proveedor');
        $this->addSql('DROP INDEX IDX_CB86F22ACB305D73 ON servicio');
        $this->addSql('ALTER TABLE servicio DROP proveedor_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colaborador_proveedor (colaborador_id INT NOT NULL, proveedor_id INT NOT NULL, INDEX IDX_AC1A7AA7CB305D73 (proveedor_id), INDEX IDX_AC1A7AA7F1CB264E (colaborador_id), PRIMARY KEY(colaborador_id, proveedor_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE proveedor (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nombre VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_16C068CE20332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE colaborador_proveedor ADD CONSTRAINT FK_AC1A7AA7CB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE colaborador_proveedor ADD CONSTRAINT FK_AC1A7AA7F1CB264E FOREIGN KEY (colaborador_id) REFERENCES colaborador (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE servicio ADD proveedor_id INT NOT NULL');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_CB86F22ACB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_CB86F22ACB305D73 ON servicio (proveedor_id)');
    }
}
