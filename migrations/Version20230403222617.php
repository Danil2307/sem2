<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403222617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers ADD flag TINYINT(1) NOT NULL, ADD api_token VARCHAR(510) NOT NULL');
        $this->addSql('ALTER TABLE questions ADD flag TINYINT(1) NOT NULL, ADD api_token VARCHAR(510) NOT NULL');
        $this->addSql('ALTER TABLE user ADD api_token VARCHAR(510) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP flag, DROP api_token');
        $this->addSql('ALTER TABLE questions DROP flag, DROP api_token');
        $this->addSql('ALTER TABLE user DROP api_token');
    }
}
