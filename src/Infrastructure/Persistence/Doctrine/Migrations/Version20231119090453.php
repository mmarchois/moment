<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119090453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendee (uuid UUID NOT NULL, event_uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_1150D567CEB41C0D ON attendee (event_uuid)');
        $this->addSql('CREATE TABLE event (uuid UUID NOT NULL, user_uuid UUID NOT NULL, title VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, expiration_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7ABFE1C6F ON event (user_uuid)');
        $this->addSql('CREATE TABLE tag (uuid UUID NOT NULL, event_uuid UUID NOT NULL, title VARCHAR(100) NOT NULL, start_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_389B783CEB41C0D ON tag (event_uuid)');
        $this->addSql('CREATE TABLE token (uuid UUID NOT NULL, user_uuid UUID NOT NULL, token VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, expiration_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE INDEX IDX_5F37A13BABFE1C6F ON token (user_uuid)');
        $this->addSql('CREATE INDEX IDX_5F37A13B5F37A13B ON token (token)');
        $this->addSql('CREATE TABLE "user" (uuid UUID NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(uuid))');
        $this->addSql('CREATE UNIQUE INDEX user_email ON "user" (email)');
        $this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_1150D567CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783CEB41C0D FOREIGN KEY (event_uuid) REFERENCES event (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "user" (uuid) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE attendee DROP CONSTRAINT FK_1150D567CEB41C0D');
        $this->addSql('ALTER TABLE event DROP CONSTRAINT FK_3BAE0AA7ABFE1C6F');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B783CEB41C0D');
        $this->addSql('ALTER TABLE token DROP CONSTRAINT FK_5F37A13BABFE1C6F');
        $this->addSql('DROP TABLE attendee');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE token');
        $this->addSql('DROP TABLE "user"');
    }
}
