<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231001101053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, INDEX IDX_6EEAA67DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, montre_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C53D045F1F3E1099 (montre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE montre (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, image_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B61A93A4BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE montre_commande (id INT AUTO_INCREMENT NOT NULL, montre_id INT NOT NULL, commande_id INT DEFAULT NULL, user_id INT NOT NULL, quantite INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut TINYINT(1) NOT NULL, INDEX IDX_5750B0071F3E1099 (montre_id), INDEX IDX_5750B00782EA2E54 (commande_id), INDEX IDX_5750B007A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F1F3E1099 FOREIGN KEY (montre_id) REFERENCES montre (id)');
        $this->addSql('ALTER TABLE montre ADD CONSTRAINT FK_B61A93A4BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B0071F3E1099 FOREIGN KEY (montre_id) REFERENCES montre (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B00782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE montre_commande ADD CONSTRAINT FK_5750B007A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DA76ED395');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F1F3E1099');
        $this->addSql('ALTER TABLE montre DROP FOREIGN KEY FK_B61A93A4BCF5E72D');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B0071F3E1099');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B00782EA2E54');
        $this->addSql('ALTER TABLE montre_commande DROP FOREIGN KEY FK_5750B007A76ED395');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE montre');
        $this->addSql('DROP TABLE montre_commande');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
