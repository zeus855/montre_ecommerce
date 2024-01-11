<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231208161808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE montre_commande (montre_id INT NOT NULL, commande_id INT NOT NULL, user_id INT NOT NULL, quantite INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut TINYINT(1) NOT NULL, INDEX IDX_5750B0071F3E1099 (montre_id), INDEX IDX_5750B00782EA2E54 (commande_id), INDEX IDX_5750B007A76ED395 (user_id), PRIMARY KEY(montre_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B0071F3E1099 FOREIGN KEY (montre_id) REFERENCES montre (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B00782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B007A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B0071F3E1099');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B00782EA2E54');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B007A76ED395');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE montre_commande');
    }
}
