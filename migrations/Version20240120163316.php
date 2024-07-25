<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120163316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menus (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menus_products (menus_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_D15B9B5014041B84 (menus_id), INDEX IDX_D15B9B506C8A81A9 (products_id), PRIMARY KEY(menus_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menus_attachments (menus_id INT NOT NULL, attachments_id INT NOT NULL, INDEX IDX_4FBF4B8314041B84 (menus_id), INDEX IDX_4FBF4B839D1F836B (attachments_id), PRIMARY KEY(menus_id, attachments_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menus_orders (menus_id INT NOT NULL, orders_id INT NOT NULL, INDEX IDX_3A1E70CA14041B84 (menus_id), INDEX IDX_3A1E70CACFFE9AD6 (orders_id), PRIMARY KEY(menus_id, orders_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menus_products ADD CONSTRAINT FK_D15B9B5014041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_products ADD CONSTRAINT FK_D15B9B506C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_attachments ADD CONSTRAINT FK_4FBF4B8314041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_attachments ADD CONSTRAINT FK_4FBF4B839D1F836B FOREIGN KEY (attachments_id) REFERENCES attachments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_orders ADD CONSTRAINT FK_3A1E70CA14041B84 FOREIGN KEY (menus_id) REFERENCES menus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menus_orders ADD CONSTRAINT FK_3A1E70CACFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menus_products DROP FOREIGN KEY FK_D15B9B5014041B84');
        $this->addSql('ALTER TABLE menus_products DROP FOREIGN KEY FK_D15B9B506C8A81A9');
        $this->addSql('ALTER TABLE menus_attachments DROP FOREIGN KEY FK_4FBF4B8314041B84');
        $this->addSql('ALTER TABLE menus_attachments DROP FOREIGN KEY FK_4FBF4B839D1F836B');
        $this->addSql('ALTER TABLE menus_orders DROP FOREIGN KEY FK_3A1E70CA14041B84');
        $this->addSql('ALTER TABLE menus_orders DROP FOREIGN KEY FK_3A1E70CACFFE9AD6');
        $this->addSql('DROP TABLE menus');
        $this->addSql('DROP TABLE menus_products');
        $this->addSql('DROP TABLE menus_attachments');
        $this->addSql('DROP TABLE menus_orders');
    }
}
