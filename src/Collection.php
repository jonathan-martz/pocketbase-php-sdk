<?php

namespace Pb;

use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class Collection
{
    /**
     * @var string
     */
    private string $collection;

    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    public static string $token = '';

    /**
     * @param string $url
     * @param string $collection
     * @param string $token
     */
    public function __construct(string $url,
                                string $collection,
                                string $token = ''
    )
    {
        $this->url = $url;
        $this->collection = $collection;
        if (!empty($token)) {
            self::$token = $token;
        }
    }

    /**
     * @param int $start
     * @param int $end
     * @param array $queryParams
     * @return array
     */
    public function getList(int $start = 1, int $end = 50, array $queryParams = []): array
    {
        $queryParams['perPage'] = $end;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');

        return json_decode($response, JSON_FORCE_OBJECT);
    }

    /**
     * @param string $recordId
     * @param string $field
     * @param string $filepath
     * @return void
     */
    public function upload(string $recordId, string $field, string $filepath): void
    {
        $ch = curl_init($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => array(
                $field => new \CURLFile($filepath)
            )
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $headers = array('Content-Type: multipart/form-data');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
    }

    public function authAsUser(string $email, string $password): string
    {
        $result = $this->doRequest($this->url . "/api/collections/users/auth-with-password", 'POST', ['identity' => $email, 'password' => $password]);
        var_dump($result);
        if (!empty($result['token'])) {
            self::$token = $result['token'];
        }
        return $result;
    }

    /**
     * @param int $batch
     * @param array $queryParams
     * @return array
     */
    public function getFullList(array $queryParams, int $batch = 200): array
    {
        $queryParams = [... $queryParams, 'perPage' => $batch];
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');

        return json_decode($response, JSON_FORCE_OBJECT);
    }

    /**
     * @param string $filter
     * @param array $queryParams
     * @return array
     */
    public function getFirstListItem(string $filter, array $queryParams = []): array
    {
        // TODO filter
        $queryParams['perPage'] = 1;
        $queryParams['filter'] = $filter;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');

        $data = json_decode($response, JSON_FORCE_OBJECT);
        if (empty($data['items']) || count($data['items']) < 1) {
            throw new exception\FirstListItemNotFoundException('First doesnt exists');
        }

        return $data['items'][0] ?? [];
    }

    /**
     * @param array $bodyParams
     * @param array $queryParams
     * @return void
     */
    public function create(array $bodyParams = [], array $queryParams = []): string
    {
        return $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records", 'POST', $bodyParams);
    }

    /**
     * @param string $recordId
     * @param array $bodyParams
     * @param array $queryParams
     * @return void
     */
    public function update(string $recordId, array $bodyParams = [], array $queryParams = []): void
    {
        // Todo bodyParams equals json, currently workaround
        $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'PATCH', $bodyParams);
    }

    /**
     * @param string $recordId
     * @param array $queryParams
     * @return void
     */
    public function delete(string $recordId, array $queryParams = []): void
    {
        // TODO params ?
        $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'DELETE');
    }

    /**
     * @throws GuzzleException
     */
    public function doRequest(string $url, string $method, $bodyParams = []): string
    {
        $tmp = $bodyParams;
        $bodyParams = [];
        $bodyParams['json'] = $tmp;
        $bodyParams['headers']['Content-Type'] = 'application/json';

        if (self::$token != '') {
            $bodyParams['headers']['Authorization'] = 'Bearer ' . self::$token;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request($method, $url, $bodyParams);
        return $response->getBody()->getContents() ?? '';
    }

    /**
     * @param string $recordId
     * @param array $queryParams
     * @return mixed
     */
    public function getOne(string $recordId, array $queryParams = []): array
    {
        // TODO params ?
        $output = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'GET');
        return json_decode($output, JSON_FORCE_OBJECT);
    }

    public function authAsAdmin(string $email, string $password): string
    {
        $bodyParams['identity'] = $email;
        $bodyParams['password'] = $password;
        $output = $this->doRequest($this->url . "/api/collections/_superusers/auth-with-password", 'POST', $bodyParams);

        $token = json_decode($output, true)['token'];
        if ($token) {
            self::$token = $token;
        }

        return $output;
    }
}
