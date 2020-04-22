<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422211653 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_4FBF094F7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, owner_id, name, email FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_4FBF094F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO company (id, owner_id, name, email) SELECT id, owner_id, name, email FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E3C61F9 ON company (owner_id)');
        $this->addSql('DROP INDEX IDX_29D6873E7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, owner_id, title FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_29D6873E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES company (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO offer (id, owner_id, title) SELECT id, owner_id, title FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873E7E3C61F9 ON offer (owner_id)');
        $this->addSql('DROP INDEX UNIQ_CAAD1019A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__applicant AS SELECT id, user_id, name, email FROM applicant');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('CREATE TABLE applicant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_CAAD1019A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO applicant (id, user_id, name, email) SELECT id, user_id, name, email FROM __temp__applicant');
        $this->addSql('DROP TABLE __temp__applicant');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAAD1019A76ED395 ON applicant (user_id)');
        $this->addSql('DROP INDEX IDX_C4EE7D3D97139001');
        $this->addSql('DROP INDEX IDX_C4EE7D3D53C674EE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__applicant_offer AS SELECT applicant_id, offer_id FROM applicant_offer');
        $this->addSql('DROP TABLE applicant_offer');
        $this->addSql('CREATE TABLE applicant_offer (applicant_id INTEGER NOT NULL, offer_id INTEGER NOT NULL, PRIMARY KEY(applicant_id, offer_id), CONSTRAINT FK_C4EE7D3D97139001 FOREIGN KEY (applicant_id) REFERENCES applicant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C4EE7D3D53C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO applicant_offer (applicant_id, offer_id) SELECT applicant_id, offer_id FROM __temp__applicant_offer');
        $this->addSql('DROP TABLE __temp__applicant_offer');
        $this->addSql('CREATE INDEX IDX_C4EE7D3D97139001 ON applicant_offer (applicant_id)');
        $this->addSql('CREATE INDEX IDX_C4EE7D3D53C674EE ON applicant_offer (offer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX UNIQ_CAAD1019A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__applicant AS SELECT id, user_id, name, email FROM applicant');
        $this->addSql('DROP TABLE applicant');
        $this->addSql('CREATE TABLE applicant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO applicant (id, user_id, name, email) SELECT id, user_id, name, email FROM __temp__applicant');
        $this->addSql('DROP TABLE __temp__applicant');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAAD1019A76ED395 ON applicant (user_id)');
        $this->addSql('DROP INDEX IDX_C4EE7D3D97139001');
        $this->addSql('DROP INDEX IDX_C4EE7D3D53C674EE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__applicant_offer AS SELECT applicant_id, offer_id FROM applicant_offer');
        $this->addSql('DROP TABLE applicant_offer');
        $this->addSql('CREATE TABLE applicant_offer (applicant_id INTEGER NOT NULL, offer_id INTEGER NOT NULL, PRIMARY KEY(applicant_id, offer_id))');
        $this->addSql('INSERT INTO applicant_offer (applicant_id, offer_id) SELECT applicant_id, offer_id FROM __temp__applicant_offer');
        $this->addSql('DROP TABLE __temp__applicant_offer');
        $this->addSql('CREATE INDEX IDX_C4EE7D3D97139001 ON applicant_offer (applicant_id)');
        $this->addSql('CREATE INDEX IDX_C4EE7D3D53C674EE ON applicant_offer (offer_id)');
        $this->addSql('DROP INDEX UNIQ_4FBF094F7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__company AS SELECT id, owner_id, name, email FROM company');
        $this->addSql('DROP TABLE company');
        $this->addSql('CREATE TABLE company (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO company (id, owner_id, name, email) SELECT id, owner_id, name, email FROM __temp__company');
        $this->addSql('DROP TABLE __temp__company');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4FBF094F7E3C61F9 ON company (owner_id)');
        $this->addSql('DROP INDEX IDX_29D6873E7E3C61F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__offer AS SELECT id, owner_id, title FROM offer');
        $this->addSql('DROP TABLE offer');
        $this->addSql('CREATE TABLE offer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO offer (id, owner_id, title) SELECT id, owner_id, title FROM __temp__offer');
        $this->addSql('DROP TABLE __temp__offer');
        $this->addSql('CREATE INDEX IDX_29D6873E7E3C61F9 ON offer (owner_id)');
    }
}
