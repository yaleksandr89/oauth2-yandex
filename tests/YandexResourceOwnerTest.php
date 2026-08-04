<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use UnexpectedValueException;
use Yaleksandr\OAuth2\Client\Provider\YandexResourceOwner;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatar;
use Yaleksandr\OAuth2\Client\ValueObject\YandexAvatarSize;
use Yaleksandr\OAuth2\Client\ValueObject\YandexPhone;
use Yaleksandr\OAuth2\Client\ValueObject\YandexSex;

final class YandexResourceOwnerTest extends TestCase
{
    public function testAllTypedFieldsAndOriginalPayload(): void
    {
        $payload = self::fixtureData('user-info.json');
        $owner = new YandexResourceOwner($payload);

        self::assertSame('1000034426', $owner->getId());
        self::assertSame('ivan', $owner->getLogin());
        self::assertSame('4760187d81bc4b7799476b42b5103713', $owner->getClientId());
        self::assertSame('1.AAceCw.test', $owner->getPsuid());
        self::assertSame('uid-mmzxrnry', $owner->getOldSocialLogin());
        self::assertSame('Иван', $owner->getFirstName());
        self::assertSame('Иванов', $owner->getLastName());
        self::assertSame('ivan', $owner->getDisplayName());
        self::assertSame('Иван Иванов', $owner->getRealName());
        self::assertSame(YandexSex::Male, $owner->getSex());
        self::assertSame('0000-12-23', $owner->getBirthday());
        self::assertSame('test@yandex.ru', $owner->getDefaultEmail());
        self::assertSame(['test@yandex.ru', 'other-test@yandex.ru'], $owner->getEmails());
        self::assertSame($payload, $owner->toArray());

        $avatar = $owner->getDefaultAvatar();
        self::assertInstanceOf(YandexAvatar::class, $avatar);
        self::assertSame('131652443', $avatar->getId());
        self::assertFalse($avatar->isEmpty());
        self::assertSame(['id' => '131652443', 'empty' => false], $avatar->toArray());
        self::assertSame('131652443', $owner->getDefaultAvatarId());
        self::assertFalse($owner->isAvatarEmpty());
        self::assertSame(
            'https://avatars.yandex.net/get-yapic/131652443/islands-200',
            $owner->getAvatarUrl(),
        );
        self::assertSame(
            'https://avatars.yandex.net/get-yapic/131652443/islands-68',
            $owner->getAvatarUrl(YandexAvatarSize::Size68),
        );

        $phone = $owner->getDefaultPhone();
        self::assertInstanceOf(YandexPhone::class, $phone);
        self::assertSame(12345678, $phone->getId());
        self::assertSame('+79037659418', $phone->getNumber());
        self::assertSame(['id' => 12345678, 'number' => '+79037659418'], $phone->toArray());
    }

    public function testAbsentOptionalFieldsReturnNullOrEmptyList(): void
    {
        $owner = new YandexResourceOwner(self::fixtureData('user-info-minimal.json'));

        self::assertNull($owner->getOldSocialLogin());
        self::assertNull($owner->getFirstName());
        self::assertNull($owner->getLastName());
        self::assertNull($owner->getDisplayName());
        self::assertNull($owner->getRealName());
        self::assertNull($owner->getSex());
        self::assertNull($owner->getBirthday());
        self::assertNull($owner->getDefaultEmail());
        self::assertSame([], $owner->getEmails());
        self::assertNull($owner->getDefaultAvatar());
        self::assertNull($owner->getDefaultAvatarId());
        self::assertNull($owner->isAvatarEmpty());
        self::assertNull($owner->getDefaultPhone());
        self::assertNull($owner->getAvatarUrl());
    }

    public function testAvatarValueObjectBuildsSafeUrlsAndRepresentsPlaceholders(): void
    {
        $avatar = new YandexAvatar('12345/example-avatar', false);

        self::assertSame('12345/example-avatar', $avatar->getId());
        self::assertFalse($avatar->isEmpty());
        self::assertSame(['id' => '12345/example-avatar', 'empty' => false], $avatar->toArray());
        self::assertSame(
            'https://avatars.yandex.net/get-yapic/12345/example-avatar/islands-200',
            $avatar->getUrl(),
        );
        self::assertSame(
            'https://avatars.yandex.net/get-yapic/12345/example-avatar/islands-retina-50',
            $avatar->getUrl(YandexAvatarSize::Size100),
        );

        $placeholder = new YandexAvatar('placeholder', true);
        self::assertTrue($placeholder->isEmpty());
        self::assertNull($placeholder->getUrl());
    }

    public function testAvatarConstructorRejectsAnEmptyId(): void
    {
        $this->expectException(UnexpectedValueException::class);
        new YandexAvatar(' ', false);
    }

    /**
     * @param array<string, mixed> $avatarFields
     */
    #[DataProvider('malformedAvatarPayloads')]
    public function testIncompleteOrMalformedAvatarFieldsAreRejected(array $avatarFields): void
    {
        $payload = [...self::fixtureData('user-info-minimal.json'), ...$avatarFields];

        $this->expectException(UnexpectedValueException::class);
        new YandexResourceOwner($payload);
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function malformedAvatarPayloads(): iterable
    {
        yield 'missing empty flag' => [['default_avatar_id' => 'avatar-id']];
        yield 'missing id' => [['is_avatar_empty' => false]];
        yield 'non-string id' => [['default_avatar_id' => 42, 'is_avatar_empty' => false]];
        yield 'empty id' => [['default_avatar_id' => '', 'is_avatar_empty' => false]];
        yield 'non-boolean empty flag' => [['default_avatar_id' => 'avatar-id', 'is_avatar_empty' => 0]];
    }

    public function testFemaleSexIsTyped(): void
    {
        $payload = self::fixtureData('user-info-minimal.json');
        $payload['sex'] = 'female';

        self::assertSame(YandexSex::Female, (new YandexResourceOwner($payload))->getSex());
    }

    #[DataProvider('invalidSexValues')]
    public function testInvalidSexIsRejected(mixed $sex): void
    {
        $payload = self::fixtureData('user-info-minimal.json');
        $payload['sex'] = $sex;

        $this->expectException(UnexpectedValueException::class);
        new YandexResourceOwner($payload);
    }

    /**
     * @return iterable<string, array{mixed}>
     */
    public static function invalidSexValues(): iterable
    {
        yield 'unsupported string' => ['other'];
        yield 'non-string value' => [1];
    }

    public function testMalformedRequiredFieldsAreRejectedWithoutCoercion(): void
    {
        foreach (['id', 'login', 'client_id', 'psuid'] as $field) {
            $payload = self::fixtureData('user-info-minimal.json');
            $payload[$field] = $field === 'id' ? 1000034426 : '';

            try {
                new YandexResourceOwner($payload);
                self::fail(sprintf('Malformed field "%s" was accepted.', $field));
            } catch (UnexpectedValueException $exception) {
                self::assertStringContainsString($field, $exception->getMessage());
            }
        }
    }

    public function testPhoneValidatesItsClosedContract(): void
    {
        $phone = YandexPhone::fromArray(['id' => 12345678, 'number' => '+79037659418']);

        self::assertSame(12345678, $phone->getId());
        self::assertSame('+79037659418', $phone->getNumber());

        foreach (
            [
                ['id' => '12345678', 'number' => '+79037659418'],
                ['id' => 12345678, 'number' => ''],
                ['id' => 12345678],
            ] as $malformed
        ) {
            try {
                YandexPhone::fromArray($malformed);
                self::fail('Malformed phone payload was accepted.');
            } catch (UnexpectedValueException) {
                self::addToAssertionCount(1);
            }
        }

        try {
            new YandexPhone(12345678, '');
            self::fail('An empty phone number was accepted by the constructor.');
        } catch (UnexpectedValueException) {
            self::addToAssertionCount(1);
        }

        $payload = self::fixtureData('user-info-minimal.json');
        $payload['default_phone'] = 'not-an-object';

        $this->expectException(UnexpectedValueException::class);
        new YandexResourceOwner($payload);
    }

    public function testAvatarEnumContainsExactDocumentedNamesValuesAndSizes(): void
    {
        self::assertSame(
            [
                'Size28' => ['islands-small', '28x28'],
                'Size34' => ['islands-34', '34x34'],
                'Size42' => ['islands-middle', '42x42'],
                'Size50' => ['islands-50', '50x50'],
                'Size56' => ['islands-retina-small', '56x56'],
                'Size68' => ['islands-68', '68x68'],
                'Size75' => ['islands-75', '75x75'],
                'Size84' => ['islands-retina-middle', '84x84'],
                'Size100' => ['islands-retina-50', '100x100'],
                'Size200' => ['islands-200', '200x200'],
            ],
            array_reduce(
                YandexAvatarSize::cases(),
                static function (array $sizes, YandexAvatarSize $size): array {
                    $pixels = substr($size->name, 4);
                    $sizes[$size->name] = [$size->value, sprintf('%1$sx%1$s', $pixels)];

                    return $sizes;
                },
                [],
            ),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function fixtureData(string $name): array
    {
        $contents = file_get_contents(__DIR__ . '/Fixtures/' . $name);

        if ($contents === false) {
            throw new RuntimeException(sprintf('Unable to read fixture "%s".', $name));
        }

        $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Fixture "%s" is not a JSON object.', $name));
        }

        /** @var array<string, mixed> $data */
        return $data;
    }
}
