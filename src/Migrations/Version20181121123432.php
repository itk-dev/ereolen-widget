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
final class Version20181121123432 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO itkdev_setting (name, type, form_type, description, value_text) VALUES (:name, :type, :form_type, :description, :value)', [
            'name' => 'users_manual',
            'type' => 'text',
            'form_type' => 'ckeditor',
            'description' => "User's manual",
            'value' => '<h1>User\'s manual</h1><p>To build a widget …</p>',
        ]);
        $this->addSql('INSERT INTO itkdev_setting (name, type, form_type, description, value_text) VALUES (:name, :type, :form_type, :description, :value)', [
            'name' => 'about_widgets',
            'type' => 'text',
            'form_type' => 'ckeditor',
            'description' => 'About widgets',
            'value' => '<h1>About widgets</h1><p>After building a widget …</p>',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM itkdev_setting WHERE name = 'about_widgets'");
        $this->addSql("DELETE FROM itkdev_setting WHERE name = 'users_manual'");
    }
}
