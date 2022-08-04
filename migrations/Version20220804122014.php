<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220804122014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formateur ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formateur ADD CONSTRAINT FK_ED767E4F8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('CREATE INDEX IDX_ED767E4F8BAC62AF ON formateur (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formateur DROP FOREIGN KEY FK_ED767E4F8BAC62AF');
        $this->addSql('DROP INDEX IDX_ED767E4F8BAC62AF ON formateur');
        $this->addSql('ALTER TABLE formateur DROP city_id');
    }
}
