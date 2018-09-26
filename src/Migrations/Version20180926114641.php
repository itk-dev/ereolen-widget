<?php

declare(strict_types=1);

/*
 * This file is part of eReolen widget.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180926114641 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), INDEX IDX_957A6479B03A8386 (created_by_id), INDEX IDX_957A6479896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479B03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE widget ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES fos_user (id)');
        $this->addSql('CREATE INDEX IDX_85F91ED0B03A8386 ON widget (created_by_id)');
        $this->addSql('CREATE INDEX IDX_85F91ED0896DBBDE ON widget (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0B03A8386');
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0896DBBDE');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479B03A8386');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479896DBBDE');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP INDEX IDX_85F91ED0B03A8386 ON widget');
        $this->addSql('DROP INDEX IDX_85F91ED0896DBBDE ON widget');
        $this->addSql('ALTER TABLE widget DROP created_by_id, DROP updated_by_id, DROP created_at, DROP updated_at');
    }
}
