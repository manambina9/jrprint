<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241107091510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD name VARCHAR(255) NOT NULL, ADD discount NUMERIC(10, 2) NOT NULL, DROP discount_percentage, DROP discounted_price, DROP promotion_start, DROP promotion_end');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promotion ADD discount_percentage NUMERIC(5, 2) DEFAULT NULL, ADD discounted_price NUMERIC(10, 2) DEFAULT NULL, ADD promotion_start DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD promotion_end DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP name, DROP discount');
    }
}
