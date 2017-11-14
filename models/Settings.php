<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\authentication\models;

use Yii;
use yii\base\Model;

/**
 * 附件上传设置
 * @package yuncms\attachment\models
 */
class Settings extends Model
{
    /**
     * @var boolean 是否开启机器审查
     */
    public $enableMachineReview;

    /**
     * @var string 阿里云图像识别AppCode
     */
    public $ociAppCode;

    /**
     * @var integer 身份证图片访问URL
     */
    public $idCardUrl;

    /**
     * @var integer 身份证图片存储路径
     */
    public $idCardPath;

    /**
     * 定义字段类型
     * @return array
     */
    public function getTypes()
    {
        return [
            'enableMachineReview' => 'boolean',
            'ociAppCode' => 'string',
            'idCardUrl' => 'string',
            'idCardPath' => 'string',
        ];
    }

    public function rules()
    {
        return [
            [['enableMachineReview',], 'boolean'],
            [['enableMachineReview'], 'default', 'value' => true],
            [['idCardUrl', 'idCardPath', 'ociAppCode'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableMachineReview' => Yii::t('authentication', 'Enable Machine Review'),
            'ociAppCode' => Yii::t('authentication', 'Machine Review Code'),
            'idCardUrl' => Yii::t('authentication', 'idCard Url'),
            'idCardPath' => Yii::t('authentication', 'idCard Save Path'),
        ];
    }

    /**
     * 返回标识
     */
    public function formName()
    {
        return 'authentication';
    }
}