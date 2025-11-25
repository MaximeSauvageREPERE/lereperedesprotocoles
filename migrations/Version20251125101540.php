<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251125101540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE protocole (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, fichier VARCHAR(255) DEFAULT NULL, theme_id INT NOT NULL, INDEX IDX_9078B75D59027487 (theme_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rubrique (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rubrique_domaine (rubrique_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_D3BB5E063BD38833 (rubrique_id), INDEX IDX_D3BB5E064272FC9F (domaine_id), PRIMARY KEY (rubrique_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, rubrique_id INT NOT NULL, INDEX IDX_9775E7083BD38833 (rubrique_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE protocole ADD CONSTRAINT FK_9078B75D59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE rubrique_domaine ADD CONSTRAINT FK_D3BB5E063BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubrique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rubrique_domaine ADD CONSTRAINT FK_D3BB5E064272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E7083BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubrique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocole DROP FOREIGN KEY FK_9078B75D59027487');
        $this->addSql('ALTER TABLE rubrique_domaine DROP FOREIGN KEY FK_D3BB5E063BD38833');
        $this->addSql('ALTER TABLE rubrique_domaine DROP FOREIGN KEY FK_D3BB5E064272FC9F');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E7083BD38833');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE protocole');
        $this->addSql('DROP TABLE rubrique');
        $this->addSql('DROP TABLE rubrique_domaine');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
