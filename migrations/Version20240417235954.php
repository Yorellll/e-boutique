<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417235954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP INDEX UNIQ_F52993981AD5CDBF, ADD INDEX IDX_F52993981AD5CDBF (cart_id)');
        $this->addSql('ALTER TABLE product RENAME INDEX fk_product_category TO IDX_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE user CHANGE code_postal code_postal VARCHAR(10) NOT NULL, CHANGE ville ville VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP INDEX IDX_F52993981AD5CDBF, ADD UNIQUE INDEX UNIQ_F52993981AD5CDBF (cart_id)');
        $this->addSql('ALTER TABLE product RENAME INDEX idx_d34a04ad12469de2 TO fk_product_category');
        $this->addSql('ALTER TABLE user CHANGE code_postal code_postal VARCHAR(10) DEFAULT NULL, CHANGE ville ville VARCHAR(255) DEFAULT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL');
    }
}
