<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\SimplePay;

use MLocati\Nexi\XPay\Entity;
use MLocati\Nexi\XPay\Entity\EntityWithMac;
use MLocati\Nexi\XPay\Entity\EntityWithMacTrait;
use MLocati\Nexi\XPay\Configuration;

/**
 * @see https://ecommerce.nexi.it/specifiche-tecniche/codicebase/avviopagamento.html
 */
class Request extends Entity implements EntityWithMac
{
    use EntityWithMacTrait;

    /**
     * Amount to be authorized expressed in cents, without separator.
     * The first 2 numbers on the right represent the euro cents
     *
     * Required
     * Minimum: 0
     * Maximum: 99999999
     *
     * @example 5000 corresponds to 50.00
     */
    public function getImporto(): ?int
    {
        return $this->_getInt('importo');
    }

    /**
     * Amount to be authorized, with separator.
     *
     * Required
     * Minimum: 0
     * Maximum: 999999.99
     *
     * @example '50.00' corresponds to 50.00
     */
    public function getImportoAsDecimal(): string
    {
        $value = (string) $this->getImporto();
        if ($value === '') {
            return '';
        }
        if (strlen($value) < 3) {
            $value = substr('00' . $value, -3);
        }
        $integers = substr($value, 0, -2);
        $decimals = substr($value, -2);

        return $integers . '.' . $decimals;
    }

    /**
     * Amount to be authorized expressed in cents, without separator.
     * The first 2 numbers on the right represent the euro cents
     *
     * Required
     * Minimum: 0
     * Maximum: 99999999
     *
     * @example 5000 corresponds to 50.00
     *
     * @return $this
     */
    public function setImporto(?int $value): self
    {
        return $value === null ? $this->_unset('importo') : $this->_set('importo', $value);
    }

    /**
     * Amount to be authorized, with separator.
     *
     * @param string|int|float|null $value
     *
     * Required
     * Minimum: 0
     * Maximum: 999999.99
     *
     * @example '50.00' corresponds to 50.00
     *
     * @return $this
     */
    public function setImportoAsDecimal($value): self
    {
        return is_numeric($value) ? $this->setImporto((int) round(100 * (float) $value)) : $this->setImporto(null);
    }

    /**
     * The code of the currency in which the amount is expressed.
     * Only value allowed: EUR (Euro)
     *
     * Required
     * Maximum length: 3
     */
    public function getDivisa(): ?string
    {
        return $this->_getString('divisa');
    }

    /**
     * The code of the currency in which the amount is expressed.
     * Only value allowed: EUR (Euro)
     *
     * Required
     * Maximum length: 3
     *
     * @return $this
     */
    public function setDivisa(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('divisa') : $this->_set('divisa', $value);
    }

    /**
     * Payment identification code composed of alphanumeric characters.
     * The code must be unique for each authorization request, only in case of a negative outcome of the authorization the merchant can re-submit the same request with the same codeTrans 2 more times,
     * During the configuration phase the merchant can choose to reduce the 3 attempts.
     *
     * Required
     * Minimum length: 2
     * Maximum length: 30
     * Excluded chars: # ' "
     */
    public function getCodTrans(): ?string
    {
        return $this->_getString('codTrans');
    }

    /**
     * Payment identification code composed of alphanumeric characters.
     * The code must be unique for each authorization request, only in case of a negative outcome of the authorization the merchant can re-submit the same request with the same codeTrans 2 more times,
     * During the configuration phase the merchant can choose to reduce the 3 attempts.
     *
     * Required
     * Minimum length: 2
     * Maximum length: 30
     * Excluded chars: # ' "
     *
     * @return $this
     */
    public function setCodTrans(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('codTrans') : $this->_set('codTrans', $value);
    }

    /**
     * URL of the merchant to which the gateway directs the user to complete the transaction, passing, in GET, the response parameters with the result of the transaction.
     * The customer is redirected to the address specified via this parameter, both in the event of a successful transaction and in the event of a negative outcome.
     * The field value must begin with "http://" or "https://" and standard ports 80 or 443 must be used.
     *
     * Required
     * Maximum length: 500
     */
    public function getUrl(): ?string
    {
        return $this->_getString('url');
    }

    /**
     * URL of the merchant to which the gateway directs the user to complete the transaction, passing, in GET, the response parameters with the result of the transaction.
     * The customer is redirected to the address specified via this parameter, both in the event of a successful transaction and in the event of a negative outcome.
     * The field value must begin with "http://" or "https://" and standard ports 80 or 443 must be used.
     *
     * Required
     * Maximum length: 500
     *
     * @return $this
     */
    public function setUrl(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('url') : $this->_set('url', $value);
    }

    /**
     * URL recalled if the user decides to abandon the transaction during the payment phase on the checkout page or if the call contains formal errors.
     * The field value must begin with "http://" or "https://" and standard ports 80 or 443 must be used.
     *
     * Required
     * Maximum length: 200
     */
    public function getUrl_back(): ?string
    {
        return $this->_getString('url_back');
    }

    /**
     * URL recalled if the user decides to abandon the transaction during the payment phase on the checkout page or if the call contains formal errors.
     * The field value must begin with "http://" or "https://" and standard ports 80 or 443 must be used.
     *
     * Required
     * Maximum length: 200
     *
     * @return $this
     */
    public function setUrl_back(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('url_back') : $this->_set('url_back', $value);
    }

    /**
     * URL to which XPay sends the outcome of the transaction, passing, in server to server mode with the POST method, the response parameters with the outcome of the transaction.
     * The field value must begin with “http://” or “https://” and standard ports 80 or 443 must be used.
     * The address indicated in this field must have a public certificate, must not be protected by authentication and must support the TLS 1.2 security protocol.
     * The POST notification has format "application/x-www-form-urlencoded".
     * To confirm receipt of the notification, the message returned from the call must be an "HTTP 200".
     * No action can be taken on the transaction until the outcome (HTTP 200) has been returned in response to the notification.
     *
     * Maximum length: 500
     */
    public function getUrlpost(): ?string
    {
        return $this->_getString('urlpost');
    }

    /**
     * URL to which XPay sends the outcome of the transaction, passing, in server to server mode with the POST method, the response parameters with the outcome of the transaction.
     * The field value must begin with “http://” or “https://” and standard ports 80 or 443 must be used.
     * The address indicated in this field must have a public certificate, must not be protected by authentication and must support the TLS 1.2 security protocol.
     * The POST notification has format "application/x-www-form-urlencoded".
     * To confirm receipt of the notification, the message returned from the call must be an "HTTP 200".
     * No action can be taken on the transaction until the outcome (HTTP 200) has been returned in response to the notification.
     *
     * Maximum length: 500
     */
    public function setUrlpost(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('urlpost') : $this->_set('urlpost', $value);
    }

    /**
     * The buyer's email address to which the payment result will be sent.
     *
     * Maximum length: 150
     */
    public function getMail(): ?string
    {
        return $this->_getString('mail');
    }

    /**
     * The buyer's email address to which the payment result will be sent.
     *
     * Maximum length: 150
     */
    public function setMail(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('mail') : $this->_set('mail', $value);
    }

    /**
     * Language identifier that will be displayed on the checkout page.
     * The available languages are those shown in the languageId coding table.
     * If this field is not specified or is left empty, the texts will be displayed as defined as default during the service configuration phase.
     *
     * Maximum length: 7
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificalanguageid.html
     */
    public function getLanguageId(): ?string
    {
        return $this->_getString('languageId');
    }

    /**
     * Language identifier that will be displayed on the checkout page.
     * The available languages are those shown in the languageId coding table.
     * If this field is not specified or is left empty, the texts will be displayed as defined as default during the service configuration phase.
     *
     * Maximum length: 7
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificalanguageid.html
     */
    public function setLanguageId(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('languageId') : $this->_set('languageId', $value);
    }

    /**
     * A description of the type of service offered.
     * For the MyBank service the field is sent to the bank to be included in the description of the SCT provision but is truncated at the 140th character.
     * With PayPal the past value will be made available in the payment detail available on the PayPal account.
     *
     * Maximum length: 2000
     * Special chars excluded: # ' "
     *
     * For MyBank:
     * Maximum length: 140
     * Allowed chars: A-Z a-z 0-9 / - : ( ) . , +
     *
     * For PayPal:
     * Maximum length: 127
     */
    public function getDescrizione(): ?string
    {
        return $this->_getString('descrizione');
    }

    /**
     * A description of the type of service offered.
     * For the MyBank service the field is sent to the bank to be included in the description of the SCT provision but is truncated at the 140th character.
     * With PayPal the past value will be made available in the payment detail available on the PayPal account.
     *
     * Maximum length: 2000
     * Excluded chars: # ' "
     *
     * For MyBank:
     * Maximum length: 140
     * Allowed chars: A-Z a-z 0-9 / - : ( ) . , +
     *
     * For PayPal:
     * Maximum length: 127
     */
    public function setDescrizione(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('descrizione') : $this->_set('descrizione', $value);
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function getNote1(): ?string
    {
        return $this->_getString('Note1');
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function setNote1(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('Note1') : $this->_set('Note1', $value);
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function getNote2(): ?string
    {
        return $this->_getString('Note2');
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function setNote2(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('Note2') : $this->_set('Note2', $value);
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function getNote3(): ?string
    {
        return $this->_getString('Note3');
    }

    /**
     * Information related to the order.
     *
     * Maximum length: 200
     */
    public function setNote3(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('Note3') : $this->_set('Note3', $value);
    }

    /**
     * User's Codice Fiscale (tax code) for XPay, necessary if the check between the tax code and the associated PAN number is active.
     * (optional security check that can be activated upon request) .
     *
     * Maximum length: 16
     */
    public function getOPTION_CF(): ?string
    {
        return $this->_getString('OPTION_CF');
    }

    /**
     * User's Codice Fiscale (tax code) for XPay, necessary if the check between the tax code and the associated PAN number is active.
     * (optional security check that can be activated upon request) .
     *
     * Maximum length: 16
     */
    public function setOPTION_CF(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('OPTION_CF') : $this->_set('OPTION_CF', $value);
    }

    /**
     * If specified, the payment page is shown allowing the user to make the payment only with the indicated payment circuits or methods.
     * This function is useful for those who want to include the choice of payment method on their check-out page.
     * The possible values are indicated in the Paper type coding table.
     * Separate the multiple values with a comma ",".
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificatipocarta.html
     */
    public function getSelectedcard(): ?string
    {
        return $this->_getString('selectedcard');
    }

    /**
     * If specified, the payment page is shown allowing the user to make the payment only with the indicated payment circuits or methods.
     * This function is useful for those who want to include the choice of payment method on their check-out page.
     * The possible values are indicated in the Paper type coding table.
     * Separate the multiple values with a comma ",".
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificatipocarta.html
     */
    public function setSelectedcard(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('selectedcard') : $this->_set('selectedcard', $value);
    }

    /**
     * The collection method that the merchant wants to apply to the individual transaction.
     * It may be:
     * - C (immediate) the transaction, if authorized, is also cashed without further intervention by the merchant and without considering the default profile set on the terminal.
     * - D (deferred) or the field is not entered, the transaction, if authorized, is managed as defined by the terminal profile.
     * Immediate collection is the one established as standard by Nexi.
     * If you want to manage deferred collections, ask technical support for authorization.
     * Once enabled, in the case of deferred collection, the collection is the responsibility of the merchant who can manage it from the back office, via API or on an automatic deadline communicated when configuring the profile.
     *
     * Maximum length: 20
     */
    public function getTCONTAB(): ?string
    {
        return $this->_getString('TCONTAB');
    }

    /**
     * The collection method that the merchant wants to apply to the individual transaction.
     * It may be:
     * - C (immediate) the transaction, if authorized, is also cashed without further intervention by the merchant and without considering the default profile set on the terminal.
     * - D (deferred) or the field is not entered, the transaction, if authorized, is managed as defined by the terminal profile.
     * Immediate collection is the one established as standard by Nexi.
     * If you want to manage deferred collections, ask technical support for authorization.
     * Once enabled, in the case of deferred collection, the collection is the responsibility of the merchant who can manage it from the back office, via API or on an automatic deadline communicated when configuring the profile.
     *
     * Maximum length: 20
     */
    public function setTCONTAB(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('TCONTAB') : $this->_set('TCONTAB', $value);
    }

    /**
     * Additional information related to the specific payment.
     * This information can be conveyed to the company based on prior agreements with the company itself.
     *
     * Maximum length: 35
     */
    public function getInfoc(): ?string
    {
        return $this->_getString('infoc');
    }

    /**
     * Additional information related to the specific payment.
     * This information can be conveyed to the company based on prior agreements with the company itself.
     *
     * Maximum length: 35
     */
    public function setInfoc(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('infoc') : $this->_set('infoc', $value);
    }

    /**
     * Additional information related to the specific payment.
     * This information can be conveyed to the bank based on prior agreements with the bank itself.
     *
     * Maximum length: 20
     */
    public function getInfob(): ?string
    {
        return $this->_getString('infob');
    }

    /**
     * Additional information related to the specific payment.
     * This information can be conveyed to the bank based on prior agreements with the bank itself.
     *
     * Maximum length: 20
     */
    public function setInfob(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('infob') : $this->_set('infob', $value);
    }

    /**
     * "VC" (Card Verification) to be used to perform a card verification: it is necessary to enter the amount with "0" (zero).
     * With this call type XPay only checks the validity of the card, no tokenisations or other operations are carried out.
     *
     * Maximum length: 2
     */
    public function getTipo_richiesta(): ?string
    {
        return $this->_getString('tipo_richiesta');
    }

    /**
     * "VC" (Card Verification) to be used to perform a card verification: it is necessary to enter the amount with "0" (zero).
     * With this call type XPay only checks the validity of the card, no tokenisations or other operations are carried out.
     *
     * Maximum length: 2
     */
    public function setTipo_richiesta(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('tipo_richiesta') : $this->_set('tipo_richiesta', $value);
    }

    /**
     * Payment session timeout, valued with the seconds of validity of the payment session.
     * The parameter overrides the value set in the XPay back office.
     * Compatible with payment cards and PayPal.
     */
    public function getXpayTimeout(): ?int
    {
        return $this->_getInt('xpayTimeout');
    }

    /**
     * Payment session timeout, valued with the seconds of validity of the payment session.
     * The parameter overrides the value set in the XPay back office.
     * Compatible with payment cards and PayPal.
     */
    public function setXpayTimeout(?int $value): self
    {
        return $value === null || $value === '' ? $this->_unset('xpayTimeout') : $this->_set('xpayTimeout', $value);
    }

    /**
     * First name of the person that's paying.
     *
     * Maximum length: 150
     */
    public function getNome(): ?string
    {
        return $this->_getString('nome');
    }

    /**
     * First name of the person that's paying.
     *
     * Maximum length: 150
     */
    public function setNome(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('nome') : $this->_set('nome', $value);
    }

    /**
     * Last name of the person that's paying.
     *
     * Maximum length: 150
     */
    public function getCognome(): ?string
    {
        return $this->_getString('cognome');
    }

    /**
     * Last name of the person that's paying.
     *
     * Maximum length: 150
     */
    public function setCognome(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('cognome') : $this->_set('cognome', $value);
    }

    /**
     * In order to use this parameter, dynamic 3D Secure must be enabled on the merchant's terminal.
     * This service allows you to send a 3D Secure exemption request which will be evaluated by the card issuer and possibly accepted.
     * Once the service is enabled, Nexi will automatically send the 3DS exemption request in all payments.
     * With this field you can request exemption or force 3D Secure authentication.
     * Possible values:
     * - 'SCA': 3D Secure will be requested from the customer on payment
     * - 'EXEMPT': the exemption request is sent.
     */
    public function get3dsDinamico(): ?string
    {
        return $this->_getString('3dsDinamico');
    }

    /**
     * In order to use this parameter, dynamic 3D Secure must be enabled on the merchant's terminal.
     * This service allows you to send a 3D Secure exemption request which will be evaluated by the card issuer and possibly accepted.
     * Once the service is enabled, Nexi will automatically send the 3DS exemption request in all payments.
     * With this field you can request exemption or force 3D Secure authentication.
     * Possible values:
     * - 'SCA': 3D Secure will be requested from the customer on payment
     * - 'EXEMPT': the exemption request is sent.
     */
    public function set3dsDinamico(?string $value): self
    {
        return $value === null || $value === '' ? $this->_unset('3dsDinamico') : $this->_set('3dsDinamico', $value);
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMacTrait::getAliasFieldName()
     */
    protected function getAliasFieldName(): string
    {
        return 'alias';
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'importo',
            'divisa',
            'codTrans',
            'url',
            'url_back',
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity\EntityWithMacTrait::getFieldsForMac()
     */
    protected function getFieldsForMac(Configuration $configuration): array
    {
        return [
            'codTrans' => $this->getCodTrans(),
            'divisa' => $this->getDivisa(),
            'importo' => $this->getImporto(),
        ];
    }
}
