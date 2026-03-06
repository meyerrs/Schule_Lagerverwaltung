<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306084548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE Inventory ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Inventory ADD CONSTRAINT FK_33DCC8956BF700BD FOREIGN KEY (status_id) REFERENCES Status (id)');
        $this->addSql('CREATE INDEX IDX_33DCC8956BF700BD ON Inventory (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE Status');
        $this->addSql('ALTER TABLE Inventory DROP FOREIGN KEY FK_33DCC8956BF700BD');
        $this->addSql('DROP INDEX IDX_33DCC8956BF700BD ON Inventory');
        $this->addSql('ALTER TABLE Inventory DROP status_id');
    }
}
