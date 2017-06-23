<?php
declare(strict_types=1);
namespace ParagonIE\Sapient\Adapter;

use ParagonIE\ConstantTime\Base64UrlSafe;
use ParagonIE\Sapient\Exception\{
    InvalidMessageException
};
use ParagonIE\Sapient\CryptographyKeys\{
    SealingPublicKey,
    SharedAuthenticationKey,
    SharedEncryptionKey,
    SigningSecretKey
};
use ParagonIE\Sapient\Sapient;
use ParagonIE\Sapient\Simple;
use Psr\Http\Message\{
    RequestInterface,
    ResponseInterface,
    StreamInterface
};
use Slim\Http\{
    Headers,
    Request,
    Response,
    Stream,
    Uri
};

/**
 * Class Slim
 * @package ParagonIE\Sapient\Adapter
 */
class Slim implements AdapterInterface, ConvenienceInterface
{
    /**
     * Create an HTTP request object with a JSON body that is authenticated
     * with a pre-shared key. The authentication tag is stored in a
     * Body-HMAC-SHA512256 header.
     *
     * @param string $method
     * @param string $uri
     * @param array $arrayToJsonify
     * @param SharedAuthenticationKey $key
     * @param array $headers
     * @return RequestInterface
     * @throws InvalidMessageException
     */
    public function createSymmetricAuthenticatedJsonRequest(
        string $method,
        string $uri,
        array $arrayToJsonify,
        SharedAuthenticationKey $key,
        array $headers = []
    ): RequestInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSymmetricAuthenticatedRequest(
            $method,
            $uri,
            $body,
            $key,
            $headers
        );
    }

    /**
     * Create an HTTP response object with a JSON body that is authenticated
     * with a pre-shared key. The authentication tag is stored in a
     * Body-HMAC-SHA512256 header.
     *
     * @param int $status
     * @param array $arrayToJsonify
     * @param SharedAuthenticationKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     * @throws InvalidMessageException
     */
    public function createSymmetricAuthenticatedJsonResponse(
        int $status,
        array $arrayToJsonify,
        SharedAuthenticationKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSymmetricAuthenticatedResponse(
            $status,
            $body,
            $key,
            $headers,
            $version
        );
    }

    /**
     * Create an HTTP request object with a JSON body that is encrypted
     * with a pre-shared key.
     *
     * @param string $method
     * @param string $uri
     * @param array $arrayToJsonify
     * @param SharedEncryptionKey $key
     * @param array $headers
     * @return RequestInterface
     * @throws InvalidMessageException
     */
    public function createSymmetricEncryptedJsonRequest(
        string $method,
        string $uri,
        array $arrayToJsonify,
        SharedEncryptionKey $key,
        array $headers = []
    ): RequestInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSymmetricEncryptedRequest(
            $method,
            $uri,
            $body,
            $key,
            $headers
        );
    }

    /**
     * Create an HTTP response object with a JSON body that is encrypted
     * with a pre-shared key.
     *
     * @param int $status
     * @param array $arrayToJsonify
     * @param SharedEncryptionKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     * @throws InvalidMessageException
     */
    public function createSymmetricEncryptedJsonResponse(
        int $status,
        array $arrayToJsonify,
        SharedEncryptionKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSymmetricEncryptedResponse(
            $status,
            $body,
            $key,
            $headers,
            $version
        );
    }

    /**
     * Create an HTTP request object with a JSON body that is encrypted
     * with the server's public key.
     *
     * @param string $method
     * @param string $uri
     * @param array $arrayToJsonify
     * @param SealingPublicKey $key
     * @param array $headers
     * @return RequestInterface
     * @throws InvalidMessageException
     */
    public function createSealedJsonRequest(
        string $method,
        string $uri,
        array $arrayToJsonify,
        SealingPublicKey $key,
        array $headers = []
    ): RequestInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSealedRequest(
            $method,
            $uri,
            $body,
            $key,
            $headers
        );
    }

    /**
     * Create an HTTP response object with a JSON body that is encrypted
     * with the server's public key.
     *
     * @param int $status
     * @param array $arrayToJsonify
     * @param SealingPublicKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     * @throws InvalidMessageException
     */
    public function createSealedJsonResponse(
        int $status,
        array $arrayToJsonify,
        SealingPublicKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSealedResponse(
            $status,
            $body,
            $key,
            $headers,
            $version
        );
    }

    /**
     * Creates a JSON-signed API request to be sent to an API.
     * Enforces hard-coded Ed25519 keys.
     *
     * @param string $method
     * @param string $uri
     * @param array $arrayToJsonify
     * @param SigningSecretKey $key
     * @param array $headers
     * @return RequestInterface
     * @throws InvalidMessageException
     */
    public function createSignedJsonRequest(
        string $method,
        string $uri,
        array $arrayToJsonify,
        SigningSecretKey $key,
        array $headers = []
    ): RequestInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSignedRequest(
            $method,
            $uri,
            $body,
            $key,
            $headers
        );
    }

    /**
     * Creates a JSON-signed API response to be returned from an API.
     * Enforces hard-coded Ed25519 keys.
     *
     * @param int $status
     * @param array $arrayToJsonify
     * @param SigningSecretKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     * @throws InvalidMessageException
     */
    public function createSignedJsonResponse(
        int $status,
        array $arrayToJsonify,
        SigningSecretKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        if (empty($headers['Content-Type'])) {
            $headers['Content-Type'] = 'application/json';
        }
        /** @var string $body */
        $body = \json_encode($arrayToJsonify, JSON_PRETTY_PRINT);
        if (!\is_string($body)) {
            throw new InvalidMessageException('Cannot JSON-encode this message.');
        }
        return $this->createSignedResponse(
            $status,
            $body,
            $key,
            $headers,
            $version
        );
    }

    /**
     * Authenticate your HTTP request with a pre-shared key.
     *
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param SharedAuthenticationKey $key
     * @param array $headers
     * @return RequestInterface
     */
    public function createSymmetricAuthenticatedRequest(
        string $method,
        string $uri,
        string $body,
        SharedAuthenticationKey $key,
        array $headers = []
    ): RequestInterface {
        $mac = \ParagonIE_Sodium_Compat::crypto_auth($body, $key->getString(true));
        if (isset($headers[Sapient::HEADER_SIGNATURE_NAME])) {
            $headers[Sapient::HEADER_AUTH_NAME][] = Base64UrlSafe::encode($mac);
        } else {
            $headers[Sapient::HEADER_AUTH_NAME] = Base64UrlSafe::encode($mac);
        }
        return new Request(
            $method,
            Uri::createFromString($uri),
            new Headers($headers),
            [],
            [],
            $this->stringToStream($body),
            []
        );
    }

    /**
     * Authenticate your HTTP response with a pre-shared key.
     *
     * @param int $status
     * @param string $body
     * @param SharedAuthenticationKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     */
    public function createSymmetricAuthenticatedResponse(
        int $status,
        string $body,
        SharedAuthenticationKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        $mac = \ParagonIE_Sodium_Compat::crypto_auth($body, $key->getString(true));
        if (isset($headers[Sapient::HEADER_SIGNATURE_NAME])) {
            $headers[Sapient::HEADER_AUTH_NAME][] = Base64UrlSafe::encode($mac);
        } else {
            $headers[Sapient::HEADER_AUTH_NAME] = Base64UrlSafe::encode($mac);
        }
        return new Response(
            $status,
            new Headers($headers),
            $this->stringToStream($body)
        );
    }

    /**
     * Encrypt your HTTP request with a pre-shared key.
     *
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param SharedEncryptionKey $key
     * @param array $headers
     * @return RequestInterface
     */
    public function createSymmetricEncryptedRequest(
        string $method,
        string $uri,
        string $body,
        SharedEncryptionKey $key,
        array $headers = []
    ): RequestInterface {
        return new Request(
            $method,
            Uri::createFromString($uri),
            new Headers($headers),
            [],
            [],
            $this->stringToStream(
                Base64UrlSafe::encode(Simple::encrypt($body, $key))
            ),
            []
        );
    }

    /**
     * Encrypt your HTTP response with a pre-shared key.
     *
     * @param int $status
     * @param string $body
     * @param SharedEncryptionKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     */
    public function createSymmetricEncryptedResponse(
        int $status,
        string $body,
        SharedEncryptionKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        return new Response(
            $status,
            new Headers($headers),
            $this->stringToStream(
                Base64UrlSafe::encode(Simple::encrypt($body, $key))
            )
        );
    }

    /**
     * Encrypt your HTTP request with the server's public key, so that only
     * the server can decrypt the message.
     *
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param SealingPublicKey $key
     * @param array $headers
     * @return RequestInterface
     */
    public function createSealedRequest(
        string $method,
        string $uri,
        string $body,
        SealingPublicKey $key,
        array $headers = []
    ): RequestInterface {
        return new Request(
            $method,
            Uri::createFromString($uri),
            new Headers($headers),
            [],
            [],
            $this->stringToStream(
                Base64UrlSafe::encode(Simple::seal($body, $key))
            ),
            []
        );
    }

    /**
     * Encrypt your HTTP response with the client's public key, so that only
     * the client can decrypt the message.
     *
     * @param int $status
     * @param string $body
     * @param SealingPublicKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     */
    public function createSealedResponse(
        int $status,
        string $body,
        SealingPublicKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        return new Response(
            $status,
            new Headers($headers),
            $this->stringToStream(
                Base64UrlSafe::encode(Simple::seal($body, $key))
            )
        );
    }

    /**
     * Ed25519-sign a request body.
     *
     * This adds an HTTP header (Body-Signature-Ed25519) which is the base64url
     * encoded Ed25519 signature of the HTTP request body.
     *
     * @param string $method
     * @param string $uri
     * @param string $body
     * @param SigningSecretKey $key
     * @param array $headers
     * @return RequestInterface
     */
    public function createSignedRequest(
        string $method,
        string $uri,
        string $body,
        SigningSecretKey $key,
        array $headers = []
    ): RequestInterface {
        $signature = \ParagonIE_Sodium_Compat::crypto_sign_detached(
            $body,
            $key->getString(true)
        );
        if (isset($headers[Sapient::HEADER_SIGNATURE_NAME])) {
            $headers[Sapient::HEADER_SIGNATURE_NAME][] = Base64UrlSafe::encode($signature);
        } else {
            $headers[Sapient::HEADER_SIGNATURE_NAME] = Base64UrlSafe::encode($signature);
        }
        return new Request(
            $method,
            Uri::createFromString($uri),
            new Headers($headers),
            [],
            [],
            $this->stringToStream($body),
            []
        );
    }

    /**
     * Ed25519-sign a response body.
     *
     * This adds an HTTP header (Body-Signature-Ed25519) which is the base64url
     * encoded Ed25519 signature of the HTTP response body.
     *
     * @param int $status
     * @param string $body
     * @param SigningSecretKey $key
     * @param array $headers
     * @param string $version
     * @return ResponseInterface
     */
    public function createSignedResponse(
        int $status,
        string $body,
        SigningSecretKey $key,
        array $headers = [],
        string $version = '1.1'
    ): ResponseInterface {
        $signature = \ParagonIE_Sodium_Compat::crypto_sign_detached(
            $body,
            $key->getString(true)
        );
        if (isset($headers[Sapient::HEADER_SIGNATURE_NAME])) {
            $headers[Sapient::HEADER_SIGNATURE_NAME][] = Base64UrlSafe::encode($signature);
        } else {
            $headers[Sapient::HEADER_SIGNATURE_NAME] = Base64UrlSafe::encode($signature);
        }
        return new Response(
            $status,
            new Headers($headers),
            $this->stringToStream($body)
        );
    }

    /**
     * Adapter-specific way of converting a string into a StreamInterface
     *
     * @param string $input
     * @return StreamInterface
     * @throws \Error
     */
    public function stringToStream(string $input): StreamInterface
    {
        /** @var resource $stream */
        $stream = \fopen('php://temp', 'w+');
        if (!\is_resource($stream)) {
            throw new \Error('Could not create stream');
        }
        \fwrite($stream, $input);
        \rewind($stream);
        return new Stream($stream);
    }
}
