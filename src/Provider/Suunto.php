<?php
namespace League\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Suunto extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string
     */
    const BASE_AUTHENTICATION_URL = 'https://cloudapi-oauth.suunto.com/oauth/authorize';

    /**
     * @var string
     */
    const BASE_TOKEN_URL = 'https://cloudapi-oauth.suunto.com/oauth/token';

    /**
     * @var string
     */
    const BASE_API_URL = 'https://cloudapi.suunto.com/v2';


    /**
     * @var string
     */
    protected $apiVersion = '2';

    /**
     * Returns a prepared request for requesting an access token.
     *
     * @param array $params Query string parameters
     * @return RequestInterface
     */
    public function getBasicRequest($method, $url, array $options = [])
    {
        $options['headers']['Authorization'] = 'Basic ' . base64_encode(implode(':', [
                $this->clientId,
                $this->clientSecret,
            ]));
        return $this->getRequest($method, $url, $options);
    }

    public function getResourceOwnerDetailsUrl(AccessToken $accessToken)
    {
        return null;
    }


    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return self::BASE_AUTHENTICATION_URL;
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return self::BASE_TOKEN_URL;
    }

    /**
     *
     * Get the default scopes used by this provider.
     *
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                'Forbidden',
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return null;
    }

    /**
     * @return string
     */
    public function getBaseDataUrl()
    {
        return self::BASE_DATA_URL;
    }

    /**
     * @return string
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultHeaders()
    {
        return [
            'Accept-Encoding' => 'gzip'
        ];
    }
}
