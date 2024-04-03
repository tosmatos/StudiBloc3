<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240403144238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE order_offer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT fk_f52993986d128938');
        $this->addSql('DROP INDEX idx_f52993986d128938');
        $this->addSql('ALTER TABLE "order" DROP user_order_id');
        $this->addSql('ALTER TABLE order_offer DROP CONSTRAINT fk_aa48f3c38d9f6d38');
        $this->addSql('ALTER TABLE order_offer DROP CONSTRAINT FK_AA48F3C353C674EE');
        $this->addSql('DROP INDEX idx_aa48f3c38d9f6d38');
        $this->addSql('ALTER TABLE order_offer DROP CONSTRAINT order_offer_pkey');
        $this->addSql('ALTER TABLE order_offer ADD orderr_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_offer ADD type INT NOT NULL');
        $this->addSql('ALTER TABLE order_offer RENAME COLUMN order_id TO id');
        $this->addSql('ALTER TABLE order_offer ADD CONSTRAINT FK_AA48F3C37742FDB3 FOREIGN KEY (orderr_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_offer ADD CONSTRAINT FK_AA48F3C353C674EE FOREIGN KEY (offer_id) REFERENCES offer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AA48F3C37742FDB3 ON order_offer (orderr_id)');
        $this->addSql('ALTER TABLE order_offer ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE order_offer_id_seq CASCADE');
        $this->addSql('ALTER TABLE "order" ADD user_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT fk_f52993986d128938 FOREIGN KEY (user_order_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f52993986d128938 ON "order" (user_order_id)');
        $this->addSql('ALTER TABLE order_offer DROP CONSTRAINT FK_AA48F3C37742FDB3');
        $this->addSql('ALTER TABLE order_offer DROP CONSTRAINT fk_aa48f3c353c674ee');
        $this->addSql('DROP INDEX IDX_AA48F3C37742FDB3');
        $this->addSql('DROP INDEX order_offer_pkey');
        $this->addSql('ALTER TABLE order_offer ADD order_id INT NOT NULL');
        $this->addSql('ALTER TABLE order_offer DROP id');
        $this->addSql('ALTER TABLE order_offer DROP orderr_id');
        $this->addSql('ALTER TABLE order_offer DROP type');
        $this->addSql('ALTER TABLE order_offer ADD CONSTRAINT fk_aa48f3c38d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_offer ADD CONSTRAINT fk_aa48f3c353c674ee FOREIGN KEY (offer_id) REFERENCES offer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_aa48f3c38d9f6d38 ON order_offer (order_id)');
        $this->addSql('ALTER TABLE order_offer ADD PRIMARY KEY (order_id, offer_id)');
    }
}
