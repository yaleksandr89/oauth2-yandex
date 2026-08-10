# 参与贡献

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/CONTRIBUTING.md) | [English](./CONTRIBUTING_en.md) | [Español](./CONTRIBUTING_es.md) | **中文** | [Français](./CONTRIBUTING_fr.md) | [Deutsch](./CONTRIBUTING_de.md) |

感谢您愿意改进 OAuth2 Yandex。本指南用于帮助您准备更易于审查、安全集成和维护的变更。

## 开始之前

- 可复现的缺陷请通过 GitHub Issue 报告。
- 新功能或改进请创建 feature request。
- 安全问题请遵循[安全策略](../../.github/SECURITY.md)，不要公开敏感细节。
- 如果变更较大，或会破坏公共 API、Yandex ID OAuth 合约或 `league/oauth2-client` 兼容性，请在实现前先通过 Issue 与维护者讨论。

## 包的约定

- 本包是面向 `league/oauth2-client` 的 framework-agnostic Yandex ID provider client。
- `Yandex` 继承 `League\OAuth2\Client\Provider\AbstractProvider`，并复用 League 标准的 grant 和 access token 生命周期。
- 用户资料由 `YandexResourceOwner` 表示；资料中的类型化部分由 `YandexAvatar`、`YandexPhone`、`YandexSex` 和 `YandexAvatarSize` 表示。
- 获取资料时，access token 通过 `Authorization: OAuth <token>` 发送，不得出现在 URL 中。
- provider-specific 授权参数、endpoints 和错误处理必须符合当前 Yandex ID 合约以及兼容的 `league/oauth2-client` API。
- 空 `scope` 不得序列化为 `scope=`；显式提供的非空 scopes 必须保持预期行为。
- 如无单独且充分的理由，不应为本包增加 framework-specific dependency。
- 公共 API 的变更必须是有意的，并考虑 SemVer 以及 `1.x` 系列中的向后兼容性。
- 不要加入与当前问题无关的 abstractions、storage、logging、framework adapters 或其他功能。

## 分支

请使用能体现变更目的的简短名称，例如：

```text
feature/add-yandex-option
fix/oauth-error-handling
docs/update-yandex-guide
```

## Commits

建议使用 Conventional Commits。例如：

```text
feat: add Yandex authorization option
fix: handle Yandex OAuth error
docs: clarify native OAuth flow
test: cover malformed profile response
chore: update CI configuration
```

## 本地检查

安装依赖并运行完整检查：

```shell
composer install
composer check
```

也可以运行以下针对性检查：

```shell
composer test
composer analyse
composer cs
```

只有需要覆盖率报告时才单独运行 `composer coverage`；并非每次变更都必须运行。

## Pull Request

Pull Request 描述中请说明：

- 问题以及所做的变更；
- 已执行的检查；
- 对公共 API、Yandex ID OAuth 合约或 `league/oauth2-client` 兼容性的影响；
- 新增或更新的测试；
- 文档变更；
- 如果 CONTRIBUTING 或 SECURITY 策略发生变化，是否同步了全部翻译。

提交前请确认：

- 未加入真实的 Client Secret、authorization code、access token、refresh token 或其他私有配置；
- 代码、日志、Issues 或测试数据中不包含真实用户资料或其他个人数据；
- fixtures 和 examples 使用合成且已脱敏的数据；
- `vendor/`、`composer.lock`、PHPUnit cache 和覆盖率输出未加入仓库。
