<?php

namespace Hwcdn\Apiv1;

use Ark\Filecache\FileCache;
use GuzzleHttp\Client;

class Statistics
{
    const BASE_URL = "https://cdn.myhuaweicloud.com/v1.0/cdn/statistics/";

    /**
     * @var 认证鉴权  https://support.huaweicloud.com/api-cdn/cdn_02_0030.html
     */
    const TOKEN_URL = "https://iam.myhuaweicloud.com/v3/auth/tokens";

    /**
     * @var string token
     */
    private $token = '';
    /**
     * @var FileCache|null
     */
    protected $cacheHandle = null;

    protected $account = [];

    /**
     * Statistics constructor.
     * @param string$cachePath 缓存路径
     * @param int $expire 有效期
     */
    public function __construct($account, $cachePath, $expire)
    {
        $this->account = $account;
        $this->cacheHandle = new FileCache(
            [
                'root' => $cachePath,
                'ttl' => $expire,
                'compress' => false,
                'serialize' => 'json',
            ]
        );
    }

    /**
     * 功    能：getToken
     * 修改日期：2020/4/13
     *
     * @desc 补充描述
     * @return mixed
     */
    public function getToken()
    {
        // cache token
        $token = $this->cacheHandle->get('token');
        if ($token) {
            $this->token = $token;
            return $token;
        }
        $body = [
            "auth" => [
                "identity" => [
                    "methods" => ["password"],
                    "password" => [
                        "user" => [
                            "name" => $this->account['user'],
                            "password" => $this->account['pass'],
                            "domain" => [
                                "name" => $this->account['domainName']
                            ]
                        ]
                    ]
                ],
                "scope" => [
                    "domain" => [
                        "name" => $this->account['domainName']
                    ]
                ]
            ],
        ];
        try {
            $client = new Client([
                'base_uri' => self::TOKEN_URL,
                'headers' => [
                    'Content-Type' => 'application/json;charset=utf8'
                ],
                'body' => json_encode($body)
            ]);
            $body = $client->request('POST', '');
            $subjectToken = $body->getHeader('X-Subject-Token');
        } catch (\Exception $e) {
            return false;
        }
        if ($subjectToken && isset($subjectToken)) {
            $this->token = $subjectToken[0];
            $this->cacheHandle->set('token', $subjectToken[0]);
            return $subjectToken[0];
        }
        return false;
    }

    /**
     * 功    能：bandWidthDetail
     * 修改日期：2020/4/13
     *
     * @desc 补充描述
     * @param string $domain_name 域名
     * @param string $start_time 开始时间
     * @param string $end_time 结束时间
     * @param string $enterprise_project_id 项目id
     * @return array|mixed
     */
    public function bandwidth($domain_name, $start_time = '', $end_time = '', $enterprise_project_id = 'all')
    {
        $params = [
            'start_time' => $start_time * 1000,
            'end_time' => $end_time * 1000,
            'domain_name' => $domain_name,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->getToken()
                ],
            ]);
            $body = $client->get('bandwidth', [
                'query' => $params
            ]);
            return json_decode((string)$body->getBody(), true);
        } catch (\Exception $e) {
            return array();
        }
    }

    /**
     * 功    能：bandWidthDetail
     * 修改日期：2020/4/13
     *
     * @desc 补充描述
     * @param string $domain_name 域名
     * @param string $start_time 开始时间
     * @param string $end_time 结束时间
     * @param string $interval 间隔
     * @param string $enterprise_project_id 项目id
     * @return array|mixed
     */
    public function bandWidthDetail($domain_name, $start_time = '', $end_time = '', $interval = '300', $enterprise_project_id = 'all')
    {
        $params = [
            'start_time' => $start_time * 1000,
            'end_time' => $end_time * 1000,
            'domain_name' => $domain_name,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->getToken()
                ],
            ]);
            $body = $client->get('bandwidth-detail', [
                'query' => $params
            ]);
            return json_decode((string)$body->getBody(), true);
        } catch (\Exception $e) {
            return array();
        }
    }

    /**
     * 功    能：domainSummary
     * 修改日期：2020/4/13
     *
     * @desc 查询消耗统计
     * @param string $domain_name 域名
     * @param string $start_time 开始时间
     * @param string $end_time 结束时间
     * @param string $stat_type 查询类型
     * @param string $interval 间隔时间
     * @param string $enterprise_project_id 账号id
     * @return mixed
     */
    public function domainSummary($domain_name, $start_time = '', $end_time = '', $stat_type = 'bw', $interval = '300', $enterprise_project_id = 'all')
    {
        $params = [
            'start_time' => $start_time * 1000,
            'end_time' => $end_time * 1000,
            'domain_name' => $domain_name,
            'stat_type' => $stat_type,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->getToken()
                ],
            ]);
            $body = $client->get('domain-summary', [
                'query' => $params
            ]);
            return json_decode((string)$body->getBody(), true);
        } catch (\Exception $e) {
            return array();
        }
    }

    /**
     * 功    能：domainSummaryDetail
     * 修改日期：2020/4/13
     *
     * @desc 查询消耗统计
     * @param string $domain_name 域名
     * @param string $start_time 开始时间
     * @param string $end_time 结束时间
     * @param string $stat_type 查询类型
     * @param string $interval 间隔时间
     * @param string $enterprise_project_id 账号id
     * @return mixed
     */
    public function domainSummaryDetail($domain_name, $start_time = '', $end_time = '', $stat_type = 'bw', $interval = '300', $enterprise_project_id = 'all')
    {
        $params = [
            'start_time' => $start_time * 1000,
            'end_time' => $end_time * 1000,
            'domain_name' => $domain_name,
            'stat_type' => $stat_type,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->getToken()
                ],
            ]);
            $body = $client->get('domain-summary-detail', [
                'query' => $params
            ]);
            return json_decode((string)$body->getBody(), true);
        } catch (\Exception $e) {
            return array();
        }
    }
}
