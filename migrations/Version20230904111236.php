<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904111236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722F5E237E06 ON avatar (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722F2D710CF2 ON avatar (poster)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3952D0CB5E237E06 ON platform (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3952D0CB2D710CF2 ON platform (poster)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94D9ED725E237E06 ON videogame (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_94D9ED722D710CF2 ON videogame (poster)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1677722F5E237E06 ON avatar');
        $this->addSql('DROP INDEX UNIQ_1677722F2D710CF2 ON avatar');
        $this->addSql('DROP INDEX UNIQ_3952D0CB5E237E06 ON platform');
        $this->addSql('DROP INDEX UNIQ_3952D0CB2D710CF2 ON platform');
        $this->addSql('DROP INDEX UNIQ_94D9ED725E237E06 ON videogame');
        $this->addSql('DROP INDEX UNIQ_94D9ED722D710CF2 ON videogame');
    }
}
