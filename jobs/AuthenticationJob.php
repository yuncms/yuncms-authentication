<?php

namespace yuncms\authentication\jobs;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Json;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;
use yii\httpclient\Client;
use yuncms\authentication\models\Authentication;

/**
 * 实名认证任务处理类
 * @package common\jobs
 */
class AuthenticationJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var int 用户ID
     */
    public $userId;

    /**
     * 执行实名认证任务
     * @param Queue $queue
     * @throws \yii\httpclient\Exception
     */
    public function execute($queue)
    {
        if (($authentication = Authentication::findOne(['user_id' => $this->userId, 'id_type' => Authentication::TYPE_ID])) != null) {
            $fileContent = file_get_contents($authentication->passport_cover);
            $base64Content = base64_encode($fileContent);
            $oci = $this->ocrIdCard($base64Content);
            if ($oci && (isset($oci['name']) && isset($oci['name']))) {
                if ($oci['name'] == $authentication->real_name && $oci['num'] == $authentication->id_card) {//比对身份证
                    $result = Yii::$app->id98->getIdCard($authentication->real_name, $authentication->id_card);
                    if ($result['success'] == true) {
                        if ($result['data'] == 1) {
                            $authentication->status = Authentication::STATUS_AUTHENTICATED;
                            $authentication->failed_reason = '信息比对一致';
                        } else if ($result['data'] == 2) {
                            $authentication->status = Authentication::STATUS_REJECTED;
                            $authentication->failed_reason = '姓名和身份证号码不一致';
                        } else if ($result['data'] == 3) {
                            $authentication->status = Authentication::STATUS_REJECTED;
                            $authentication->failed_reason = '身份证中心查无此身份证号码';
                        }
                    }
                } else {
                    $authentication->status = Authentication::STATUS_REJECTED;
                    $authentication->failed_reason = '姓名和身份证号码不一致';
                }
            } else {
                $authentication->status = Authentication::STATUS_REJECTED;
                $authentication->failed_reason = '身份证图像识别失败了，请上传清晰的身份证照片。';
            }
            $authentication->save(false);
        }
    }


    /**
     * Ocr图像识别
     * @param string $dataValue
     * @return mixed
     */
    public function ocrIdCard($dataValue)
    {
        $httpClient = new Client([
            'baseUrl' => 'https://dm-51.data.aliyun.com',
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        $bodys = "{\"inputs\": [{\"image\": {\"dataType\": 50,\"dataValue\": \"{$dataValue}\"},\"configure\": {\"dataType\": 50,\"dataValue\": \"{\\\"side\\\":\\\"face\\\"}\"}}]}";
        $response = $httpClient->createRequest()
            ->setUrl('rest/160601/ocr/ocr_idcard.json')
            ->setMethod('POST')
            ->setContent($bodys)
            ->addHeaders([
                'Authorization' => 'APPCODE ' . Yii::$app->settings->get('authentication', 'ociAppCode'),
                'Content-Type' => 'application/json',
            ])
            ->send();

        if ($response->isOk && isset($response->data['outputs'][0]['outputValue']['dataValue'])) {
            return Json::decode($response->data['outputs'][0]['outputValue']['dataValue']);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}
