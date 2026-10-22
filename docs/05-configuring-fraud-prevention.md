# Configuring Fraud Prevention

To configure the extension, navigate to **System > Configuration** using the top Admin Panel menu and select **Fraud Prevention** in the left side menu.

Using the extension's config screen you can modify the settings for fraud suspicions, customer blacklisting, payment processing and failed payment transactions as further explained below.

It’s possible to configure the extension per store view by changing the ‘Current Configuration Scope’ at the top left corner of the page.

To save any changes made in the Fraud Prevention settings, click the 'Save Config' button in the top right corner of the configuration page.

## Fraud Suspicion Settings

In the Fraud Suspicion Settings you can:

- Select the default address type which is included in the fraud suspicion record, choosing from the customer's shipping address, billing address or both. Please note that if 'Both shipping and billing address' is selected, two fraud suspicion records will be created for each order, which might count as two 'strikes' when executing [blacklisting rules](./08-creating-blacklisting-rules.md). You can also choose the address type when [submitting a fraud suspicion](./06-managing-suspicious-and-blacklisted-orders.md#suspicious-orders).
- Enable the comment field when submitting fraud suspicions.
- Choose whether the order confirmation e-mail should be send to the customer if an order is blacklisted.

![fraud suspicion settings](images/KB-FraudPrevention01-1.png)

## Customer Blacklisting Settings

In the Customer Blacklisting Settings you can:

- Choose to blacklist a customer account when a customer tries to place or update a fraudulent order (ie. an order triggering a blacklisting rule). The order is still submitted in the back-end, where it's marked as fraudulent, but the customer will be directly logged out and blocked from logging in.
- Choose to blacklist a customer account when a customer adds a blacklisted address during checkout or in his/her account details. The customer will be directly logged out and blocked from logging in. This option **also applies to guests** trying to use a blacklisted address during checkout, which will empty their shopping cart and delete their billing information.
- Enter a custom error message to be displayed when a blacklisted customer is logged out or blocked from logging in.

![customer blacklisting settings](images/KB-FraudPrevention01-2.png)

Example of front-end error message when customer or guest is blacklisted:

![customer blacklisting magento](images/KB-FraudPrevention01-3.png)

The fraud status of each customer account is shown in the 'Fraud Status' column of the customer grid (**Customers > Manage Customers**). You can manage the Fraud Status of a customer at the bottom of the Account Information section of the customer view, choosing from ‘Neutral’, ‘Blacklisted’ or ‘Whitelisted’.

![manage customer fraud status](images/KB-FraudPrevention01-4.png)

## Payment Processing Settings

In the Payment Processing Settings you can:

- Prevent payments from being processed by hiding the payment methods section during checkout if a blacklisted address is used.
- Enter a custom error message which will be shown instead of the payment method section.

![payment processing settings](images/KB-FraudPrevention01-5.png)

Example of front-end error message in the hidden payment information section:

![block payment processing magento](images/KB-FraudPrevention01-6.png)

## Failed Payment Transaction Settings

In this section you can choose to add a fraud suspicion strike to the customer's shipping and/or billing address when a payment transaction fails, which might trigger a blacklisting rule.

![failed payment transaction settings](images/KB-FraudPrevention01-7.png)

---

[Documentation index](./README.md) · [README](../README.md) · [Online version](https://plugin.company/knowledge-base/docs/magento-fraud-prevention/user-manual/configuring-fraud-prevention/)
