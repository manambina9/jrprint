<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115221650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_prestation ADD user_id INT NOT NULL, ADD adresse LONGTEXT NOT NULL, ADD code_postal VARCHAR(10) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD prestation VARCHAR(255) NOT NULL, ADD entreprise VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_prestation ADD CONSTRAINT FK_A704850CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A704850CA76ED395 ON demande_prestation (user_id)');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) DEFAULT NULL, ADD telephone VARCHAR(20) DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD code_postal VARCHAR(10) DEFAULT NULL, ADD ville VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nom, DROP telephone, DROP adresse, DROP code_postal, DROP ville');
        $this->addSql('ALTER TABLE demande_prestation DROP FOREIGN KEY FK_A704850CA76ED395');
        $this->addSql('DROP INDEX UNIQ_A704850CA76ED395 ON demande_prestation');
        $this->addSql('ALTER TABLE demande_prestation DROP user_id, DROP adresse, DROP code_postal, DROP ville, DROP prestation, DROP entreprise');
    }
}
