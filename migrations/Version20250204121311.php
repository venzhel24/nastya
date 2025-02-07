<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250204121311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalog_items (id SERIAL NOT NULL, country_id INT DEFAULT NULL, section_id INT DEFAULT NULL, description TEXT NOT NULL, type VARCHAR(255) NOT NULL, advantages JSON NOT NULL, published_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, photo_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_580D88F4F92F3E70 ON catalog_items (country_id)');
        $this->addSql('CREATE INDEX IDX_580D88F4D823E37A ON catalog_items (section_id)');
        $this->addSql('CREATE TABLE catalog_item_manufacturer (catalog_item_id INT NOT NULL, manufacturer_id INT NOT NULL, PRIMARY KEY(catalog_item_id, manufacturer_id))');
        $this->addSql('CREATE INDEX IDX_A2137DBD1DDDAF72 ON catalog_item_manufacturer (catalog_item_id)');
        $this->addSql('CREATE INDEX IDX_A2137DBDA23B42D ON catalog_item_manufacturer (manufacturer_id)');
        $this->addSql('CREATE TABLE catalog_section (id SERIAL NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_22C187C3727ACA70 ON catalog_section (parent_id)');
        $this->addSql('CREATE TABLE country (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE manufacturer (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE catalog_items ADD CONSTRAINT FK_580D88F4F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE catalog_items ADD CONSTRAINT FK_580D88F4D823E37A FOREIGN KEY (section_id) REFERENCES catalog_section (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE catalog_item_manufacturer ADD CONSTRAINT FK_A2137DBD1DDDAF72 FOREIGN KEY (catalog_item_id) REFERENCES catalog_items (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE catalog_item_manufacturer ADD CONSTRAINT FK_A2137DBDA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE catalog_section ADD CONSTRAINT FK_22C187C3727ACA70 FOREIGN KEY (parent_id) REFERENCES catalog_section (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_items DROP CONSTRAINT FK_580D88F4F92F3E70');
        $this->addSql('ALTER TABLE catalog_items DROP CONSTRAINT FK_580D88F4D823E37A');
        $this->addSql('ALTER TABLE catalog_item_manufacturer DROP CONSTRAINT FK_A2137DBD1DDDAF72');
        $this->addSql('ALTER TABLE catalog_item_manufacturer DROP CONSTRAINT FK_A2137DBDA23B42D');
        $this->addSql('ALTER TABLE catalog_section DROP CONSTRAINT FK_22C187C3727ACA70');
        $this->addSql('DROP TABLE catalog_items');
        $this->addSql('DROP TABLE catalog_item_manufacturer');
        $this->addSql('DROP TABLE catalog_section');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE manufacturer');
    }
}
