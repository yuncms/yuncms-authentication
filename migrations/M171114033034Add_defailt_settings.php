<?php

namespace yuncms\authentication\migrations;

use Yii;
use yii\db\Migration;

class M171114033034Add_defailt_settings extends Migration
{

    public function safeUp()
    {
        $this->batchInsert('{{%settings}}', ['type', 'section', 'key', 'value', 'active', 'created', 'modified'], [
            ['boolean', 'authentication', 'enableMachineReview', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],

            ['string', 'authentication', 'idCardUrl', '@web/uploads/id_card', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['string', 'authentication', 'idCardPath', '@webroot/uploads/id_card', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
        ]);

        Yii::$app->settings->clearCache();

    }

    public function safeDown()
    {
        $this->delete('{{%settings}}', ['section' => 'authentication']);
        Yii::$app->settings->clearCache();
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171114033034Add_defailt_settings cannot be reverted.\n";

        return false;
    }
    */
}
