# Fraud Prevention for Magento 1 / OpenMage

**Blacklisting rules that detect and block fraudulent orders in Magento.**

![Fraud suspicion example](docs/images/screenshots/01-fraud-suspicion-example.webp)

Detects and blocks suspicious orders using your own blacklisting rules, without a third-party fraud service.

Suspicious orders, blacklisted customers and fraud records are managed in the Magento admin.

> [!NOTE]
> Fraud Prevention is free and open source under the MIT License (since 2026). Plugin Company no longer actively develops this extension, but issues and pull requests are welcome.

## Features

- Custom blacklisting rules based on any combination of customer attributes
- Automatic detection and blacklisting of suspicious orders
- Manage suspicious and blacklisted orders in the admin
- Block blacklisted orders from being paid and shipped
- Blacklist billing and/or shipping addresses
- Blacklist customer accounts and block guests during checkout
- Hide payment methods for blacklisted customers
- Add fraud strikes for failed payment transactions
- Configurable number of strikes before blacklisting
- Rules per customer group and store view
- Works without third-party fraud services

## Screenshots

<table>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/02-fraud-suspicion-and-customer-blacklisting-settings.webp" alt="Fraud suspicion and customer blacklisting settings"><br><sub>Fraud suspicion and customer blacklisting settings</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/03-payment-processing-and-failed-payment-transaction-settings.webp" alt="Payment processing and failed payment transaction settings"><br><sub>Payment processing and failed payment transaction settings</sub></td>
  </tr>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/04-sales-order-overview.webp" alt="Sales order overview"><br><sub>Sales order overview</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/05-mark-suspicious-order.webp" alt="Mark suspicious order"><br><sub>Mark suspicious order</sub></td>
  </tr>
  <tr>
    <td width="50%" valign="top"><img src="docs/images/screenshots/06-fraud-suspicion-options.webp" alt="Fraud suspicion options"><br><sub>Fraud suspicion options</sub></td>
    <td width="50%" valign="top"><img src="docs/images/screenshots/07-whitelist-blacklisted-order.webp" alt="Whitelist blacklisted order"><br><sub>Whitelist blacklisted order</sub></td>
  </tr>
</table>

More screenshots are on the [product page](https://plugin.company/magento1-extensions/fraud-prevention.html).

## Compatibility

- **Magento:** Community Edition 1.4.x – 1.9.x, Enterprise Edition 1.8.x – 1.14.x
- **Latest version:** 1.4.0 (released 2020-02-18)
- **Composer package:** `plugincompany/magento1-fraud-prevention`
- **Module:** `Plugincompany_Blacklist`

## Installation

### modman

```bash
cd /path/to/magento
modman init   # once per Magento installation
modman clone https://github.com/PluginCompany/magento1-fraud-prevention.git
```

modman creates symlinks, so enable *Allow Symlinks* under System > Configuration > Advanced > Developer > Template Settings.

### Composer

With [magento-hackathon/magento-composer-installer](https://github.com/Cotya/magento-composer-installer) (it reads the `modman` file of this repository):

```bash
composer config repositories.plugin.company composer https://plugin.company/
composer require plugincompany/magento1-fraud-prevention
```

Your project `composer.json` needs the Magento root directory in its `extra` section (and, with Composer 2.2+, the installer plugin allowed), for example:

```json
{
    "extra": { "magento-root-dir": "./" },
    "config": { "allow-plugins": { "magento-hackathon/magento-composer-installer": true } }
}
```

### Manual installation

Download the ZIP of the latest release from the [tags page](https://github.com/PluginCompany/magento1-fraud-prevention/tags) ([1.4.0](https://github.com/PluginCompany/magento1-fraud-prevention/archive/refs/tags/1.4.0.zip)) and copy the `app/` folder into your Magento root:

```bash
unzip magento1-fraud-prevention-1.4.0.zip
cp -R magento1-fraud-prevention-1.4.0/app /path/to/magento/
```

Step-by-step instructions are in the [installation guide](docs/02-installing-the-magento-extension.md).

### After installing

Clear the cache (System > Cache Management, or `rm -rf var/cache/*`), then log out of the admin and log in again so the new configuration sections and permissions are loaded.

### Upgrading from a purchased copy

Replace the old files with this release. No license key is needed; your settings and data stay as they are.

## Documentation

- [Documentation in this repository](docs/README.md) (Installation Guide, User Manual)
- Online documentation: <https://plugin.company/knowledge-base/docs/magento-fraud-prevention/>
- Product page: <https://plugin.company/magento1-extensions/fraud-prevention.html>
- Magento 2 version: [magento2-fraud-prevention](https://github.com/PluginCompany/magento2-fraud-prevention)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the release history.

## Contributing

Found a bug or have an improvement? Please open an [issue](https://github.com/PluginCompany/magento1-fraud-prevention/issues) or send a [pull request](https://github.com/PluginCompany/magento1-fraud-prevention/pulls). Since the extension is no longer actively developed by Plugin Company, fixes from the community are the best way to keep it working on current Magento 1 / OpenMage and PHP versions.

## License

Plugin Company's code in this repository is released under the [MIT License](LICENSE), Copyright (c) 2014-2026 Plugin Company.

Bundled third-party libraries keep their own licenses; see [THIRD-PARTY-NOTICES.md](THIRD-PARTY-NOTICES.md) for the full list.

In particular:

- A few files are copies or adaptations of Magento core code and keep their original Magento license headers (OSL-3.0 / AFL-3.0).
