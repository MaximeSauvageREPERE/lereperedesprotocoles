<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128110624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocole ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE protocole ADD CONSTRAINT FK_9078B75D59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE rubrique_domaine ADD CONSTRAINT FK_D3BB5E063BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubrique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rubrique_domaine ADD CONSTRAINT FK_D3BB5E064272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme ADD CONSTRAINT FK_9775E7083BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubrique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE protocole DROP FOREIGN KEY FK_9078B75D59027487');
        $this->addSql('ALTER TABLE protocole DROP updated_at');
        $this->addSql('ALTER TABLE rubrique_domaine DROP FOREIGN KEY FK_D3BB5E063BD38833');
        $this->addSql('ALTER TABLE rubrique_domaine DROP FOREIGN KEY FK_D3BB5E064272FC9F');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E7083BD38833');
    }
}
