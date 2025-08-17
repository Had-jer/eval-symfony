<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250720133844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE satellite (id INT AUTO_INCREMENT NOT NULL, planete_id INT NOT NULL, name VARCHAR(255) NOT NULL, diameter INT NOT NULL, INDEX IDX_6FC72A37A9CFCB36 (planete_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE satellite ADD CONSTRAINT FK_6FC72A37A9CFCB36 FOREIGN KEY (planete_id) REFERENCES planete (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE satellite DROP FOREIGN KEY FK_6FC72A37A9CFCB36');
        $this->addSql('DROP TABLE satellite');
    }
}
