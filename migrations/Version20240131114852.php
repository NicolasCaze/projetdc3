<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240131114852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menus CHANGE price price NUMERIC(10, 3) NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price price NUMERIC(10, 3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menus CHANGE price price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE products CHANGE price price NUMERIC(10, 2) NOT NULL');
    }
}
