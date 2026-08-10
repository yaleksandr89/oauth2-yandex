# 安全策略

## 选择语言

| Русский | English | Español | 中文 | Français | Deutsch |
|---|---|---|---|---|---|
| [Русский](../../.github/SECURITY.md) | [English](./SECURITY_en.md) | [Español](./SECURITY_es.md) | **已选择** | [Français](./SECURITY_fr.md) | [Deutsch](./SECURITY_de.md) |

## 支持的版本

安全修复会为当前稳定的 `1.x` 系列发布。

| 版本 | 是否支持 |
|---|---|
| `1.x` | 是 |

## 哪些问题属于安全漏洞

安全问题包括但不限于：

- OAuth Client Secret、authorization code、access token 或 refresh token
  的泄露、替换或不安全传输；
- token 出现在 URL、日志或错误消息中；
- 绕过 OAuth 响应验证，或将无效响应当作成功响应；
- 访问其他用户的数据；
- Yandex ID 资料与本地账户之间的错误关联；
- 资料处理缺陷导致用户数据泄露。

不包含敏感数据的普通缺陷、使用问题和功能请求可以通过 GitHub Issues 提交。

## 报告安全漏洞

首选渠道是 GitHub Private Vulnerability Reporting：

1. 打开仓库的 **Security** 标签页。
2. 进入 **Advisories**。
3. 选择 **Report a vulnerability**。
4. 私下提交报告，不要在公开 issue 中发布详细信息。

请勿公开：

- Client Secret；
- authorization code；
- access token 或 refresh token；
- 真实用户的个人数据；
- 可用的 exploit，或在修复发布前即可复现攻击的详细信息。

如果 Private Vulnerability Reporting 不可用，请创建一个不含敏感信息的最小
公开 issue，并请求私下沟通渠道。

## 报告中应包含的内容

在可能的情况下，请提供：

- 受影响的版本或 commit；
- 影响说明；
- 最小复现步骤；
- 预期行为和实际行为；
- 已脱敏的请求或响应示例；
- 已知情况下的修复建议。

请勿使用真实 token、secret 或用户数据，应替换为虚构值。

## 报告处理

我们会在条件允许时确认并评估报告，但不保证固定 SLA。

在公开详细信息前，请与维护者协调披露。确认漏洞后，将按照协调披露流程
准备修复和受影响版本说明。

本项目不提供漏洞奖励计划。
