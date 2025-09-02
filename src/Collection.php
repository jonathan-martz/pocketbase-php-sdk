<?php

namespace Pb;

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
    private static string $token = '';

    /**
     * @param string $url
     * @param string $collection
     * @param string $token
     */
    public function __construct(string $url, string $collection, string $token = '')
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

    /**
     * @param string $email
     * @param string $password
     * @return void
     */
    public function authAsUser(string $email, string $password): string
    {
        $result = $this->doRequest($this->url . "/api/collections/users/auth-with-password", 'POST', ['identity' => $email, 'password' => $password]);
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
        $queryParams['perPage'] = 1;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $response = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records?" . $getParams, 'GET');
        return json_decode($response, JSON_FORCE_OBJECT)['items'][0];
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
        $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'DELETE');
    }

    /**
     * @param string $recordId
     * @param string $url
     * @param string $method
     * @return bool|string
     */
    public function doRequest(string $url, string $method, $bodyParams = []): string
    {
        // TODO move doRequestIntoService ?
        // TODO replace curl with HttpClient
        $ch = curl_init();

        if (self::$token != '') {
            $headers = array(
                'Authorization: ' . self::$token
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($bodyParams) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyParams);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;
    }

    /**
     * @param string $recordId
     * @param array $queryParams
     * @return mixed
     */
    public function getOne(string $recordId, array $queryParams = []): array
    {
        $output = $this->doRequest($this->url . "/api/collections/" . $this->collection . "/records/" . $recordId, 'GET');
        return json_decode($output, JSON_FORCE_OBJECT);
    }

    /**
     * @param string $email
     * @param string $password
     * @return void
     */
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
