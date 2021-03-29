<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210325144155 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B258361A8B8');
        $this->addSql('DROP INDEX IDX_F41C9B258361A8B8 ON cliente');
        $this->addSql('ALTER TABLE cliente DROP vendedor_id, DROP apellido_p, DROP apellido_m');
        $this->addSql('ALTER TABLE san ADD vendedor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A28361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE INDEX IDX_7F1A19A28361A8B8 ON san (vendedor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente ADD vendedor_id INT DEFAULT NULL, ADD apellido_p VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD apellido_m VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B258361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B258361A8B8 ON cliente (vendedor_id)');
        $this->addSql('ALTER TABLE san DROP FOREIGN KEY FK_7F1A19A28361A8B8');
        $this->addSql('DROP INDEX IDX_7F1A19A28361A8B8 ON san');
        $this->addSql('ALTER TABLE san DROP vendedor_id');
    }
}
