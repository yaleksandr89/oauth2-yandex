<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\ValueObject;

use UnexpectedValueException;

final readonly class YandexAvatar
{
    public function __construct(
        private string $id,
        private bool $empty,
    ) {
        if (trim($id) === '') {
            throw new UnexpectedValueException('Yandex avatar ID must be a non-empty string.');
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEmpty(): bool
    {
        return $this->empty;
    }

    public function getUrl(YandexAvatarSize $size = YandexAvatarSize::Size200): ?string
    {
        if ($this->empty) {
            return null;
        }

        $avatarId = implode(
            '/',
            array_map(
                static fn (string $part): string => rawurlencode($part),
                explode('/', $this->id),
            ),
        );

        return sprintf(
            'https://avatars.yandex.net/get-yapic/%s/%s',
            $avatarId,
            $size->value,
        );
    }

    /**
     * @return array{id: string, empty: bool}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'empty' => $this->empty,
        ];
    }
}
