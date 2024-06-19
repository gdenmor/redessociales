<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240512091123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interaccion ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE interaccion ADD CONSTRAINT FK_FA439281DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FA439281DB38439E ON interaccion (usuario_id)');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EA5989C7A FOREIGN KEY (usuario_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EBC7DCCF5 FOREIGN KEY (usuario_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_78A4129EA5989C7A ON user_seguidos (usuario_source)');
        $this->addSql('CREATE INDEX IDX_78A4129EBC7DCCF5 ON user_seguidos (usuario_target)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE interaccion DROP FOREIGN KEY FK_FA439281DB38439E');
        $this->addSql('DROP INDEX IDX_FA439281DB38439E ON interaccion');
        $this->addSql('ALTER TABLE interaccion DROP usuario_id');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EA5989C7A');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EBC7DCCF5');
        $this->addSql('DROP INDEX IDX_78A4129EA5989C7A ON user_seguidos');
        $this->addSql('DROP INDEX IDX_78A4129EBC7DCCF5 ON user_seguidos');
    }
}
