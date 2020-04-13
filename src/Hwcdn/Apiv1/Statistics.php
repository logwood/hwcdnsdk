<?php

namespace Hwcdn\Apiv1;

use GuzzleHttp\Client;

class Statistics
{
    const BASE_URL = "https://cdn.myhuaweicloud.com/v1.0/cdn/statistics/";

    /**
     * @var 认证鉴权  https://support.huaweicloud.com/api-cdn/cdn_02_0030.html
     */
    const TOKEN_URL = "https://iam.myhuaweicloud.com/v3/auth/tokens";

    private $token = '';

    /**
     * 功    能：getToken
     * 修改日期：2020/4/13
     *
     * @desc 补充描述
     * @param string $userName 用户名
     * @param string $password 密码
     * @param string $domainName 父账号
     * @return mixed
     */
    public function getToken($userName, $password, $domainName)
    {
        $body = [
            "auth" => [
                "identity" => [
                    "methods" => ["password"],
                    "password" => [
                        "user" => [
                            "name" => $userName,
                            "password" => $password,
                            "domain" => [
                                "name" => $domainName
                            ]
                        ]
                    ]
                ],
                "scope" => [
                    "domain" => [
                        "name" => $domainName
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
            'start_time' => $start_time,
            'end_time' => $end_time,
            'domain_name' => $domain_name,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->token
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
            'start_time' => $start_time,
            'end_time' => $end_time,
            'domain_name' => $domain_name,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->token
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
            'start_time' => $start_time,
            'end_time' => $end_time,
            'domain_name' => $domain_name,
            'stat_type' => $stat_type,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->token
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
            'start_time' => $start_time,
            'end_time' => $end_time,
            'domain_name' => $domain_name,
            'stat_type' => $stat_type,
            'interval' => $interval,
            'enterprise_project_id' => $enterprise_project_id,
        ];
        try {
            $client = new Client([
                'base_uri' => self::BASE_URL,
                'headers' => [
                    'x-auth-token' => $this->token
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
