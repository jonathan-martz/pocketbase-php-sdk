<?php

namespace PocketBase;

use PocketBase\ViewModel\CustomRecordViewModel;
use PocketBase\ViewModel\RecordListViewModel;
use PocketBase\ViewModel\RecordViewModel;

class Collection
{
    public HttpClient $http;
    private Client $client;
    private string $name;
    public string $path;

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): Collection
    {
        $this->path = $path;
        return $this;
    }

    public function __construct(string $url = null, string $name = 'users')
    {
        $this->name = $name;
        $this->http = new HttpClient();
        $this->client = new Client();
    }

    public function getFullList(int $batch, $bodyParams = [], array $queryParams = []): RecordListViewModel
    {
        $queryParams['limit'] = $batch;
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        return new RecordListViewModel(
            json_decode(
                $this->http->doRequest(
                    $this->client->getBaseUrl() . $this->path . '?' . $getParams,
                    'GET',
                    $bodyParams,
                    $this->client->token
                ),
                true)
        );
    }

    public function getList(int $page, int $limit, $bodyParams = [], array $queryParams = []): RecordListViewModel
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        return new RecordListViewModel(json_decode(
            $this->http->doRequest(
                $this->client->getBaseUrl() . $this->path . '?' . $getParams,
                'GET',
                $bodyParams,
                $this->client->token
            ), true));
    }

    public function authAsUser(string $email, string $password): ?string
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $data = json_decode(
            $this->http->doRequest(
                $this->client->getBaseUrl() . '/api/collections/users/auth-with-password' . '?' . $getParams,
                'POST',
                [
                    'identity' => $email,
                    'password' => $password,
                ]
            ), true);
        return $data['token'];
    }

    public function authAsAdmin(string $email, string $password): ?string
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        $data = json_decode($this->http->doRequest(
            $this->client->baseurl . "/api/collections/_superusers/auth-with-password" . '?' . $getParams,
            'POST',
            [
                'identity' => $email,
                'password' => $password,
            ]), true);
        return $data['token'];
    }

    public function create(array $bodyParams, array $queryParams = []): CustomRecordViewModel
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";

        return new CustomRecordViewModel(json_decode(
            $this->http->doRequest(
                $this->client->getBaseUrl() . $this->getPath() . '?' . $getParams,
                'POST',
                $bodyParams,
                $this->client->token
            ), true) ?? []);
    }

    public function update($id, $bodyParams, $token = '', array $queryParams = []): RecordViewModel
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        return new RecordViewModel(json_decode($this->http->doRequest(
            $this->client->getBaseUrl() . '/api/collections/test/records/' . $id . '?' . $getParams
            , 'PATCH', json_encode($bodyParams), $token), true));
    }

    public function getOne($id, $token = '', array $queryParams = []): CustomRecordViewModel
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        return new CustomRecordViewModel(json_decode($this->http->doRequest(
            $this->client->getBaseUrl() . '/api/collections/test/records/' . $id . '?' . $getParams#
            , 'GET', [], $token), true));
    }

    public function delete($id, $token = '', array $queryParams = []): CustomRecordViewModel
    {
        $getParams = !empty($queryParams) ? http_build_query($queryParams) : "";
        return new CustomRecordViewModel(json_decode($this->http->doRequest(
            $this->client->getBaseUrl() . '/api/collections/test/records/' . $id . '?' . $getParams
            , 'DELETE', [], $token), true));
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthToken(): ?string
    {
        return $this->client->token;
    }

    public function setHttp(HttpClient $httpClient): void
    {
        $this->http = $httpClient;
    }
}