<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821131307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id INT AUTO_INCREMENT NOT NULL, videogame_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, poster VARCHAR(255) DEFAULT NULL, release_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_39986E4325EB9E4B (videogame_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music (id INT AUTO_INCREMENT NOT NULL, album_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, time TIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CD52224A1137ABCF (album_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_playlist (music_id INT NOT NULL, playlist_id INT NOT NULL, INDEX IDX_10914D0B399BBB13 (music_id), INDEX IDX_10914D0B6BBD148 (playlist_id), PRIMARY KEY(music_id, playlist_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE platform (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, poster VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D782112DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videogame (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, poster VARCHAR(255) DEFAULT NULL, resume LONGTEXT DEFAULT NULL, release_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE videogame_platform (videogame_id INT NOT NULL, platform_id INT NOT NULL, INDEX IDX_69BE5C8B25EB9E4B (videogame_id), INDEX IDX_69BE5C8BFFE6496F (platform_id), PRIMARY KEY(videogame_id, platform_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E4325EB9E4B FOREIGN KEY (videogame_id) REFERENCES videogame (id)');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224A1137ABCF FOREIGN KEY (album_id) REFERENCES album (id)');
        $this->addSql('ALTER TABLE music_playlist ADD CONSTRAINT FK_10914D0B399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_playlist ADD CONSTRAINT FK_10914D0B6BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE videogame_platform ADD CONSTRAINT FK_69BE5C8B25EB9E4B FOREIGN KEY (videogame_id) REFERENCES videogame (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE videogame_platform ADD CONSTRAINT FK_69BE5C8BFFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP FOREIGN KEY FK_39986E4325EB9E4B');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224A1137ABCF');
        $this->addSql('ALTER TABLE music_playlist DROP FOREIGN KEY FK_10914D0B399BBB13');
        $this->addSql('ALTER TABLE music_playlist DROP FOREIGN KEY FK_10914D0B6BBD148');
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112DA76ED395');
        $this->addSql('ALTER TABLE videogame_platform DROP FOREIGN KEY FK_69BE5C8B25EB9E4B');
        $this->addSql('ALTER TABLE videogame_platform DROP FOREIGN KEY FK_69BE5C8BFFE6496F');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE music');
        $this->addSql('DROP TABLE music_playlist');
        $this->addSql('DROP TABLE platform');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE videogame');
        $this->addSql('DROP TABLE videogame_platform');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
