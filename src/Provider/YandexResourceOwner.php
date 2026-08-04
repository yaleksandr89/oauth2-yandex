<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use UnexpectedValueException;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatar;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatarSize;
use Yaleksandr\OAuth2\Client\ValueObject\YandexPhone;
use Yaleksandr\OAuth2\Client\ValueObject\YandexSex;

final readonly class YandexResourceOwner implements ResourceOwnerInterface
{
    private string $id;
    private string $login;
    private string $clientId;
    private string $psuid;
    private ?YandexAvatar $defaultAvatar;
    private ?YandexPhone $defaultPhone;
    private ?YandexSex $sex;

    /**
     * @param array<string, mixed> $response
     */
    public function __construct(private array $response)
    {
        $this->id = self::requiredString($response, 'id');
        $this->login = self::requiredString($response, 'login');
        $this->clientId = self::requiredString($response, 'client_id');
        $this->psuid = self::requiredString($response, 'psuid');
        $this->defaultAvatar = self::createDefaultAvatar($response);
        $this->defaultPhone = self::createDefaultPhone($response);
        $this->sex = self::createSex($response);
    }

    #[\Override]
    public function getId(): string
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getPsuid(): string
    {
        return $this->psuid;
    }

    public function getOldSocialLogin(): ?string
    {
        return $this->optionalString('old_social_login');
    }

    public function getFirstName(): ?string
    {
        return $this->optionalString('first_name');
    }

    public function getLastName(): ?string
    {
        return $this->optionalString('last_name');
    }

    public function getDisplayName(): ?string
    {
        return $this->optionalString('display_name');
    }

    public function getRealName(): ?string
    {
        return $this->optionalString('real_name');
    }

    public function getSex(): ?YandexSex
    {
        return $this->sex;
    }

    public function getBirthday(): ?string
    {
        return $this->optionalString('birthday');
    }

    public function getDefaultEmail(): ?string
    {
        return $this->optionalString('default_email');
    }

    /**
     * @return list<string>
     */
    public function getEmails(): array
    {
        $emails = $this->response['emails'] ?? null;

        if (!is_array($emails)) {
            return [];
        }

        return array_values(array_filter(
            $emails,
            static fn (mixed $email): bool => is_string($email) && trim($email) !== '',
        ));
    }

    public function getDefaultAvatarId(): ?string
    {
        return $this->defaultAvatar?->getId();
    }

    public function isAvatarEmpty(): ?bool
    {
        return $this->defaultAvatar?->isEmpty();
    }

    public function getDefaultAvatar(): ?YandexAvatar
    {
        return $this->defaultAvatar;
    }

    public function getDefaultPhone(): ?YandexPhone
    {
        return $this->defaultPhone;
    }

    public function getAvatarUrl(YandexAvatarSize $size = YandexAvatarSize::Size200): ?string
    {
        return $this->defaultAvatar?->getUrl($size);
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->response;
    }

    /**
     * @param array<string, mixed> $response
     */
    private static function requiredString(array $response, string $field): string
    {
        $value = $response[$field] ?? null;

        if (!is_string($value) || trim($value) === '') {
            throw new UnexpectedValueException(sprintf(
                'Required Yandex resource owner field "%s" must be a non-empty string.',
                $field,
            ));
        }

        return $value;
    }

    private function optionalString(string $field): ?string
    {
        $value = $this->response[$field] ?? null;

        return is_string($value) ? $value : null;
    }

    /**
     * @param array<string, mixed> $response
     */
    private static function createDefaultAvatar(array $response): ?YandexAvatar
    {
        $id = $response['default_avatar_id'] ?? null;
        $empty = $response['is_avatar_empty'] ?? null;

        if ($id === null && $empty === null) {
            return null;
        }

        if (!is_string($id) || !is_bool($empty)) {
            throw new UnexpectedValueException(
                'Yandex avatar fields "default_avatar_id" and "is_avatar_empty" '
                . 'must be a string and boolean.',
            );
        }

        return new YandexAvatar($id, $empty);
    }

    /**
     * @param array<string, mixed> $response
     */
    private static function createSex(array $response): ?YandexSex
    {
        $value = $response['sex'] ?? null;

        if ($value === null) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException('Yandex resource owner field "sex" must be "male" or "female".');
        }

        return YandexSex::tryFrom($value) ?? throw new UnexpectedValueException(
            'Yandex resource owner field "sex" must be "male" or "female".',
        );
    }

    /**
     * @param array<string, mixed> $response
     */
    private static function createDefaultPhone(array $response): ?YandexPhone
    {
        if (!array_key_exists('default_phone', $response) || $response['default_phone'] === null) {
            return null;
        }

        $phone = $response['default_phone'];

        if (!is_array($phone)) {
            throw new UnexpectedValueException('Yandex resource owner field "default_phone" must be an object.');
        }

        return YandexPhone::fromArray([
            'id' => $phone['id'] ?? null,
            'number' => $phone['number'] ?? null,
        ]);
    }
}
