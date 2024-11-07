<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241105172930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD is_admin TINYINT(1) NOT NULL, DROP name, DROP email, DROP subject, CHANGE message content LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE prestation ADD advantages JSON NOT NULL, ADD characteristics JSON NOT NULL, ADD images3d JSON NOT NULL, ADD locations JSON NOT NULL, DROP available, DROP image_url, DROP quantity_available, DROP created_at, DROP updated_at, CHANGE title name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prestation ADD available TINYINT(1) NOT NULL, ADD image_url VARCHAR(255) DEFAULT NULL, ADD quantity_available INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP advantages, DROP characteristics, DROP images3d, DROP locations, CHANGE name title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message ADD name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD subject VARCHAR(255) NOT NULL, DROP is_admin, CHANGE content message LONGTEXT NOT NULL');
    }
}
