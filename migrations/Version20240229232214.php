<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229232214 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(150) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command_line (id INT AUTO_INCREMENT NOT NULL, order_number_id INT NOT NULL, product_name_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_70BE1A7B8C26A5E8 (order_number_id), INDEX IDX_70BE1A7BA0CE8766 (product_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_adress (id INT AUTO_INCREMENT NOT NULL, name_id INT NOT NULL, first_name_id INT NOT NULL, phone_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, cp VARCHAR(15) NOT NULL, city VARCHAR(50) NOT NULL, country VARCHAR(50) NOT NULL, INDEX IDX_ED291BEF71179CD6 (name_id), INDEX IDX_ED291BEF629BF02A (first_name_id), INDEX IDX_ED291BEF3B7323CB (phone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, order_number INT NOT NULL, valid TINYINT(1) NOT NULL, date_time DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(25) NOT NULL, description VARCHAR(100) DEFAULT NULL, price_ht DOUBLE PRECISION NOT NULL, available TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(35) NOT NULL, first_name VARCHAR(35) NOT NULL, phone VARCHAR(25) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7B8C26A5E8 FOREIGN KEY (order_number_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE command_line ADD CONSTRAINT FK_70BE1A7BA0CE8766 FOREIGN KEY (product_name_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE customer_adress ADD CONSTRAINT FK_ED291BEF71179CD6 FOREIGN KEY (name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer_adress ADD CONSTRAINT FK_ED291BEF629BF02A FOREIGN KEY (first_name_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE customer_adress ADD CONSTRAINT FK_ED291BEF3B7323CB FOREIGN KEY (phone_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7B8C26A5E8');
        $this->addSql('ALTER TABLE command_line DROP FOREIGN KEY FK_70BE1A7BA0CE8766');
        $this->addSql('ALTER TABLE customer_adress DROP FOREIGN KEY FK_ED291BEF71179CD6');
        $this->addSql('ALTER TABLE customer_adress DROP FOREIGN KEY FK_ED291BEF629BF02A');
        $this->addSql('ALTER TABLE customer_adress DROP FOREIGN KEY FK_ED291BEF3B7323CB');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE command_line');
        $this->addSql('DROP TABLE customer_adress');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
