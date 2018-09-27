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
final class Version20180926191038 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE widget_request_item (id INT AUTO_INCREMENT NOT NULL, widget_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', created_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_848CD765FBE885E2 (widget_id), INDEX search_idx (widget_id, type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE widget_request_item ADD CONSTRAINT FK_848CD765FBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE widget_request_item');
    }
}
