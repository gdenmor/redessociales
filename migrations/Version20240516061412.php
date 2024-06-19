<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516061412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_contacto (usuario_id INT NOT NULL, contacto_id INT NOT NULL, INDEX IDX_CF80BBA5DB38439E (usuario_id), INDEX IDX_CF80BBA56B505CA9 (contacto_id), PRIMARY KEY(usuario_id, contacto_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usuario_contacto ADD CONSTRAINT FK_CF80BBA5DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usuario_contacto ADD CONSTRAINT FK_CF80BBA56B505CA9 FOREIGN KEY (contacto_id) REFERENCES contacto (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_contacto DROP FOREIGN KEY FK_CF80BBA5DB38439E');
        $this->addSql('ALTER TABLE usuario_contacto DROP FOREIGN KEY FK_CF80BBA56B505CA9');
        $this->addSql('DROP TABLE usuario_contacto');
    }
}
