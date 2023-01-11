<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111012433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuenta_por_cobrar ADD usuario_id INT NOT NULL, ADD cliente_id INT NOT NULL, ADD total NUMERIC(9, 2) NOT NULL, ADD plazo INT NOT NULL');
        $this->addSql('ALTER TABLE cuenta_por_cobrar ADD CONSTRAINT FK_87623D18DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE cuenta_por_cobrar ADD CONSTRAINT FK_87623D18DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('CREATE INDEX IDX_87623D18DB38439E ON cuenta_por_cobrar (usuario_id)');
        $this->addSql('CREATE INDEX IDX_87623D18DE734E51 ON cuenta_por_cobrar (cliente_id)');
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA009BD207FB5');
        $this->addSql('DROP INDEX UNIQ_F9EBA009BD207FB5 ON factura');
        $this->addSql('ALTER TABLE factura DROP cuenta_por_cobrar_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuenta_por_cobrar DROP FOREIGN KEY FK_87623D18DB38439E');
        $this->addSql('ALTER TABLE cuenta_por_cobrar DROP FOREIGN KEY FK_87623D18DE734E51');
        $this->addSql('DROP INDEX IDX_87623D18DB38439E ON cuenta_por_cobrar');
        $this->addSql('DROP INDEX IDX_87623D18DE734E51 ON cuenta_por_cobrar');
        $this->addSql('ALTER TABLE cuenta_por_cobrar DROP usuario_id, DROP cliente_id, DROP total, DROP plazo');
        $this->addSql('ALTER TABLE factura ADD cuenta_por_cobrar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009BD207FB5 FOREIGN KEY (cuenta_por_cobrar_id) REFERENCES cuenta_por_cobrar (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9EBA009BD207FB5 ON factura (cuenta_por_cobrar_id)');
    }
}
