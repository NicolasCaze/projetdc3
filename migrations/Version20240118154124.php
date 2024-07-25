<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118154124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD thumbnail_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5ABED34FF2 FOREIGN KEY (thumbnail_id_id) REFERENCES attachments (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5ABED34FF2 ON products (thumbnail_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5ABED34FF2');
        $this->addSql('DROP INDEX IDX_B3BA5A5ABED34FF2 ON products');
        $this->addSql('ALTER TABLE products DROP thumbnail_id_id');
    }
}
