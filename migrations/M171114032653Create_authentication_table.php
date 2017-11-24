<?php

namespace yuncms\authentication\migrations;

use yii\db\Migration;

class M171114032653Create_authentication_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%authentications}}', [
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User ID'),
            'real_name' => $this->string()->comment('Real Name'),
            'id_type' => $this->string(10)->notNull()->comment('ID Type'),
            'id_card' => $this->string()->notNull()->comment('ID Card'),
            'passport_cover' => $this->string()->comment('Passport Cover'),
            'passport_person_page' => $this->string()->comment('Passport Person Page'),
            'passport_self_holding' => $this->string()->comment('Passport Self Holding'),
            'status' => $this->smallInteger(1)->unsigned()->defaultValue(0)->comment('Status'),
            'failed_reason' => $this->string()->comment('Failed Reason'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->addPrimaryKey('{{%authentications}}', '{{%authentications}}', 'user_id');
        $this->addForeignKey('{{%authentications_fk_1}}', '{{%authentications}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%authentications}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171114032653Create_authentication_table cannot be reverted.\n";

        return false;
    }
    */
}
