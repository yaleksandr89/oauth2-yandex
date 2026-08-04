<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\ValueObject;

use UnexpectedValueException;

final readonly class YandexPhone
{
    public function __construct(
        private int $id,
        private string $number,
    ) {
        if (trim($number) === '') {
            throw new UnexpectedValueException('Yandex phone number must be a non-empty string.');
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        if (!isset($data['id']) || !is_int($data['id'])) {
            throw new UnexpectedValueException('Yandex phone field "id" must be an integer.');
        }

        if (!isset($data['number']) || !is_string($data['number']) || trim($data['number']) === '') {
            throw new UnexpectedValueException('Yandex phone field "number" must be a non-empty string.');
        }

        return new self($data['id'], $data['number']);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return array{id: int, number: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
        ];
    }
}
