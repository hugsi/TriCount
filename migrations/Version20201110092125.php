<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201110092125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ardoise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(500) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `join` (id INT AUTO_INCREMENT NOT NULL, ardoise_id INT NOT NULL, participant_id INT NOT NULL, INDEX IDX_2861D8D72ED4A1B8 (ardoise_id), INDEX IDX_2861D8D79D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, association_id INT NOT NULL, valeur INT NOT NULL, description VARCHAR(500) DEFAULT NULL, INDEX IDX_723705D1EFB9C8A5 (association_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `join` ADD CONSTRAINT FK_2861D8D72ED4A1B8 FOREIGN KEY (ardoise_id) REFERENCES ardoise (id)');
        $this->addSql('ALTER TABLE `join` ADD CONSTRAINT FK_2861D8D79D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EFB9C8A5 FOREIGN KEY (association_id) REFERENCES `join` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `join` DROP FOREIGN KEY FK_2861D8D72ED4A1B8');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EFB9C8A5');
        $this->addSql('ALTER TABLE `join` DROP FOREIGN KEY FK_2861D8D79D1C3019');
        $this->addSql('DROP TABLE ardoise');
        $this->addSql('DROP TABLE `join`');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE transaction');
    }
}
