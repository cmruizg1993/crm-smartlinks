<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311204716 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrato (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, fecha DATE NOT NULL, INDEX IDX_66696523DE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrato_plan (contrato_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_7E47450D70AE7BF1 (contrato_id), INDEX IDX_7E47450DE899029B (plan_id), PRIMARY KEY(contrato_id, plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solicitud (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, fecha DATE NOT NULL, INDEX IDX_96D27CC0DE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solicitud_plan (solicitud_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_E195105B1CB9D6E4 (solicitud_id), INDEX IDX_E195105BE899029B (plan_id), PRIMARY KEY(solicitud_id, plan_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE contrato_plan ADD CONSTRAINT FK_7E47450D70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrato_plan ADD CONSTRAINT FK_7E47450DE899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE solicitud_plan ADD CONSTRAINT FK_E195105B1CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE solicitud_plan ADD CONSTRAINT FK_E195105BE899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cliente ADD vendedor_id INT DEFAULT NULL, ADD estado VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B258361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B258361A8B8 ON cliente (vendedor_id)');
        $this->addSql('ALTER TABLE plan ADD servicio_id INT NOT NULL');
        $this->addSql('ALTER TABLE plan ADD CONSTRAINT FK_DD5A5B7D71CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id)');
        $this->addSql('CREATE INDEX IDX_DD5A5B7D71CAA3E7 ON plan (servicio_id)');
        $this->addSql('ALTER TABLE servicio ADD proveedor_id INT NOT NULL');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_CB86F22ACB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id)');
        $this->addSql('CREATE INDEX IDX_CB86F22ACB305D73 ON servicio (proveedor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato_plan DROP FOREIGN KEY FK_7E47450D70AE7BF1');
        $this->addSql('ALTER TABLE solicitud_plan DROP FOREIGN KEY FK_E195105B1CB9D6E4');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP TABLE contrato_plan');
        $this->addSql('DROP TABLE solicitud');
        $this->addSql('DROP TABLE solicitud_plan');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B258361A8B8');
        $this->addSql('DROP INDEX IDX_F41C9B258361A8B8 ON cliente');
        $this->addSql('ALTER TABLE cliente DROP vendedor_id, DROP estado');
        $this->addSql('ALTER TABLE plan DROP FOREIGN KEY FK_DD5A5B7D71CAA3E7');
        $this->addSql('DROP INDEX IDX_DD5A5B7D71CAA3E7 ON plan');
        $this->addSql('ALTER TABLE plan DROP servicio_id');
        $this->addSql('ALTER TABLE servicio DROP FOREIGN KEY FK_CB86F22ACB305D73');
        $this->addSql('DROP INDEX IDX_CB86F22ACB305D73 ON servicio');
        $this->addSql('ALTER TABLE servicio DROP proveedor_id');
    }
}
