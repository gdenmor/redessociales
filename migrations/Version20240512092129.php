<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512092129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interaccion DROP FOREIGN KEY FK_FA439281DB38439E');
        $this->addSql('DROP INDEX IDX_FA439281DB38439E ON interaccion');
        $this->addSql('ALTER TABLE interaccion DROP usuario_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interaccion ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE interaccion ADD CONSTRAINT FK_FA439281DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FA439281DB38439E ON interaccion (usuario_id)');
    }
}
