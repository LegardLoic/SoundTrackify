<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904110936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39986E435E237E06 ON album (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_39986E43CC919E68 ON album (main_theme_url)');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_CD52224A36AC99F1 ON music (link)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('DROP INDEX UNIQ_CD52224A36AC99F1 ON music');
        $this->addSql('DROP INDEX UNIQ_39986E435E237E06 ON album');
        $this->addSql('DROP INDEX UNIQ_39986E43CC919E68 ON album');
    }
}
