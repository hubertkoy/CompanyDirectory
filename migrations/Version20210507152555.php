<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507152555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        //$this->addSql('ALTER TABLE department ADD CONSTRAINT FK_8240E25AADB908A5 FOREIGN KEY (locationID) REFERENCES Location (id)');
        //$this->addSql('CREATE INDEX IDX_8240E25AADB908A5 ON department (locationID)');
        //$this->addSql('ALTER TABLE personnel CHANGE firstName firstName VARCHAR(50) NOT NULL');
        //$this->addSql('ALTER TABLE personnel ADD CONSTRAINT FK_244D717D7B20C08 FOREIGN KEY (departmentID) REFERENCES Department (id)');
        //$this->addSql('CREATE INDEX IDX_244D717D7B20C08 ON personnel (departmentID)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE User');
        $this->addSql('ALTER TABLE Department DROP FOREIGN KEY FK_8240E25AADB908A5');
        $this->addSql('DROP INDEX IDX_8240E25AADB908A5 ON Department');
        $this->addSql('ALTER TABLE Personnel DROP FOREIGN KEY FK_244D717D7B20C08');
        $this->addSql('DROP INDEX IDX_244D717D7B20C08 ON Personnel');
        $this->addSql('ALTER TABLE Personnel CHANGE firstName firstName VARCHAR(50) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
    }
}
