<?php

declare(strict_types=1);

namespace Yaleksandr\OAuth2\Client\ValueObject;

enum YandexSex: string
{
    case Male = 'male';
    case Female = 'female';
}
