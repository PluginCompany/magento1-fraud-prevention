# Changelog: Fraud Prevention for Magento 1 / OpenMage

All releases of this extension, newest first. This repository contains the latest release, tagged [`1.4.0`](https://github.com/PluginCompany/magento1-fraud-prevention/releases/tag/1.4.0); earlier versions are listed for reference and have no tag.

## 1.4.0 — 2020-02-18

- Added Magento admin area order grid mass actions for adding / removing fraud suspicion records, whitelisting orders and whitelisting customers

## 1.3.6 — 2019-06-18

- Fixed order grid date filtering issue caused by conflict with external extension(s) / code
- Removed leftover debug logging statement

## 1.3.5 — 2019-06-08

- Removed Mage_Sales_Model_Order rewrite for compatibility with 3rd party extensions
- Added parsing for X-Forwarded-For header so only client IP is used for customer IP field
- Fixed 'Not Logged In' customer filter issue

## 1.3.4 — 2019-05-24

- Fixed customer grid bug when custom grid customization extension is used

## 1.3.3 — 2018-11-01

- Additional fix for Magento 1.7 and below regarding order e-mail confirmation

## 1.3.2 — 2018-10-30

- Fixed issue for Magento 1.7 and below regarding order e-mail confirmation

## 1.3.1 — 2018-09-19

- Made closed orders also blacklistable
- Fixed issue regarding customer ID for customers creating an account during checkout

## 1.3.0 — 2018-07-16

- Re-enabled e-mail domain as attribute for blacklisting rules
- Added e-mail domain exclude list, which can be used for excluding common domain names from being blacklisted

## 1.2.8 — 2018-06-07

- Fix for order grid not working in admin customer account when no orders had been placed yet. (Magento EE)
- Fixed compatibility issue with Mirasvit RMA

## 1.2.7 — 2018-04-12

- Fixed customer IP saving issue
- Disabled 'Blacklisted' status for refunded orders

## 1.2.6 — 2017-12-23

- Some minor code changes for Magento Marketplace
- Bug fix for 'correlation name' error

## 1.2.5 — 2017-09-27

- Customer IP blacklist issue fix

## 1.2.4 — 2017-06-22

- Fixed notice level error when adding product to cart

## 1.2.3 — 2017-03-09

- Added positive number validation to permitted strikes field in admin area

## 1.2.2 — 2017-02-03

- Fixed mage 1.6 compatibility
- Fixed ambiguous column issue in order grid

## 1.2.1 — 2016-11-10

- Fixed disabled rule issue

## 1.2.0 — 2016-10-30

- Added X_Forwarded_For IP support
- Added fraud status in customer grid
- Account blocked error message is now only shown for a short period of time

## 1.1.1 — 2016-10-19

- Applied order email sending fix for Magento < 1.9.1

## 1.1.0 — 2016-09-01

- Added whitelisting options for blacklisted orders
- Added option to blacklist customer accounts and block guests during checkout
- Added option to block orders before payment
- Added option to blacklist orders based on failed payment transaction
- Added IP address and e-mail domain to blacklisting rules
- Added option to enable blacklisting rules per customer group
- Fixed blacklisting trigger issue when all values are null
- Fixed order confirmation issue for blacklisted orders
- Fixed compatibility with Amasty Order Grid extension
- Made compatible with latest security patch
- Removed notice and warning errors

## 1.0.3 — 2015-05-28

- Fixed observer bug

## 1.0.2 — 2015-05-16

- Added option to not send order confirmation e-mail when order is blacklisted

## 1.0.1 — 2015-04-08

- Fixed some minor bugs

## 1.0.0 — 2014-10-31

- First release of Magento Fraud Prevention extension by Plugin Company.
