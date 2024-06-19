<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528062641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion ADD tipo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipos_publicacion (id)');
        $this->addSql('CREATE INDEX IDX_62F2085FA9276E6C ON publicacion (tipo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085FA9276E6C');
        $this->addSql('DROP INDEX IDX_62F2085FA9276E6C ON publicacion');
        $this->addSql('ALTER TABLE publicacion DROP tipo_id');
    }
}
