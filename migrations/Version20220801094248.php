<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801094248 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD thumbnail_cover VARCHAR(255) DEFAULT NULL, ADD thumbnail_logo VARCHAR(255) DEFAULT NULL, DROP thumbail_cover, DROP thumbail_logo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD thumbail_cover VARCHAR(255) DEFAULT NULL, ADD thumbail_logo VARCHAR(255) DEFAULT NULL, DROP thumbnail_cover, DROP thumbnail_logo');
    }
}
