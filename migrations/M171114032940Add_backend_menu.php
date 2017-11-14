<?php

namespace yuncms\authentication\migrations;

use yii\db\Migration;

class M171114032940Add_backend_menu extends Migration
{

    public function safeUp()
    {
        $this->insert('{{%admin_menu}}', [
            'name' => '实名认证',
            'parent' => 5,
            'route' => '/authentication/authentication/index',
            'icon' => 'fa fa-id-card',
            'sort' => NULL,
            'data' => NULL
        ]);

        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '实名认证', 'parent' => 5])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['认证查看', $id, '/authentication/authentication/view', 0, NULL],
            ['审核认证', $id, '/authentication/authentication/update', 0, NULL],
        ]);
    }

    public function safeDown()
    {
        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '实名认证', 'parent' => 5])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171114032940Add_backend_menu cannot be reverted.\n";

        return false;
    }
    */
}
