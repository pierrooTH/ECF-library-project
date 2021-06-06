<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210605002121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrower ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE borrower ADD CONSTRAINT FK_DB904DB4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB904DB4A76ED395 ON borrower (user_id)');
        $this->addSql('ALTER TABLE loan ADD borrower_id INT NOT NULL');
        $this->addSql('ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0311CE312B FOREIGN KEY (borrower_id) REFERENCES borrower (id)');
        $this->addSql('CREATE INDEX IDX_C5D30D0311CE312B ON loan (borrower_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrower DROP FOREIGN KEY FK_DB904DB4A76ED395');
        $this->addSql('DROP INDEX UNIQ_DB904DB4A76ED395 ON borrower');
        $this->addSql('ALTER TABLE borrower DROP user_id');
        $this->addSql('ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0311CE312B');
        $this->addSql('DROP INDEX IDX_C5D30D0311CE312B ON loan');
        $this->addSql('ALTER TABLE loan DROP borrower_id');
    }
}
