<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\ValueObject;

enum YandexAvatarSize: string
{
    case Size28 = 'islands-small';
    case Size34 = 'islands-34';
    case Size42 = 'islands-middle';
    case Size50 = 'islands-50';
    case Size56 = 'islands-retina-small';
    case Size68 = 'islands-68';
    case Size75 = 'islands-75';
    case Size84 = 'islands-retina-middle';
    case Size100 = 'islands-retina-50';
    case Size200 = 'islands-200';
}
