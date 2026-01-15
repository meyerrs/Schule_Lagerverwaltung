<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260113105156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Inventory (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abteilung VARCHAR(255) NOT NULL, gruppe VARCHAR(255) NOT NULL, fach VARCHAR(255) NOT NULL, ort VARCHAR(255) NOT NULL, verantwortlicher_id INT DEFAULT NULL, INDEX IDX_33DCC895A1473085 (verantwortlicher_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE Inventory ADD CONSTRAINT FK_33DCC895A1473085 FOREIGN KEY (verantwortlicher_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Inventory DROP FOREIGN KEY FK_33DCC895A1473085');
        $this->addSql('DROP TABLE Inventory');
        $this->addSql('DROP TABLE user');
    }
}
