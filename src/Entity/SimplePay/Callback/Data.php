<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity\SimplePay\Callback;

use MLocati\Nexi\XPay\Configuration;
use MLocati\Nexi\XPay\Entity\EntityWithMac;
use MLocati\Nexi\XPay\Entity\EntityWithMacTrait;
use stdClass;
use MLocati\Nexi\XPay\Entity\Response;

/**
 * @link https://ecommerce.nexi.it/specifiche-tecniche/codicebase/esito.html
 */
class Data extends Response implements EntityWithMac
{
    use EntityWithMacTrait;

    public function __construct(stdClass $data)
    {
        if (is_string($data->codiceEsito ?? null) && is_numeric($data->codiceEsito)) {
            $data->codiceEsito = (int) $data->codiceEsito;
        }
        if (is_string($data->importo ?? null) && is_numeric($data->importo)) {
            $data->importo = (int) $data->importo;
        }
        parent::__construct($data);
    }

    public static function fromCustomerRequest(array $getParameters = null): self
    {
        return new self((object) ($getParameters === null ? $_GET : $getParameters));
    }

    public static function fromServer2ServerRequest(array $postParameters = null): self
    {
        return new self((object) ($postParameters === null ? $_POST : $postParameters));
    }

    /**
     * Numeric code associated with the outcome of the transaction.
     * This parameter is always returned in card payment, but is not returned for all alternative payment methods.
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificadescrizioneesito.html
     */
    public function getCodiceEsito(): ?int
    {
        return $this->_getInt('codiceEsito');
    }

    /**
     * Provides a brief description of the payment outcome.
     *
     * @see https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificaesiti.html
     */
    public function getMessaggio(): string
    {
        return $this->_getString('messaggio', true);
    }

    /**
     * Authorization code assigned by the credit card issuer.
     * Present only with authorization granted.
     */
    public function getCodAut(): ?string
    {
        return $this->_getString('codAut');
    }

    /**
     * Merchant profile identification code (fixed value communicated by Nexi during the activation phase).
     */
    public function getAlias(): string
    {
        return $this->_getString('alias', true);
    }

    /**
     * Amount to be authorized expressed in cents without separator.
     * the first 2 numbers on the right represent the cents.
     */
    public function getImporto(): int
    {
        return $this->_getInt('importo', true);
    }

    /**
     * Amount to be authorized, with separator.
     * Minimum: 0
     * Maximum: 999999.99
     *
     * @example '50.00' corresponds to 50.00
     */
    public function getImportoAsDecimal(): string
    {
        $value = (string) $this->getImporto();
        if (strlen($value) < 3) {
            $value = substr('00' . $value, -3);
        }
        $integers = substr($value, 0, -2);
        $decimals = substr($value, -2);
        
        return $integers . '.' . $decimals;
    }

    /**
     * The code of the currency in which the amount is expressed.
     * Only value allowed: EUR (Euro)
     */
    public function getDivisa(): string
    {
        return $this->_getString('divisa', true);
    }

    /**
     * Payment identification code composed of alphanumeric characters.
     * The code must be unique for each authorization request, only in the event of a negative outcome of the authorization can the merchant re-submit the same request with the same codTrans 2 more times.
     * During the configuration phase the merchant can choose to reduce the 3 attempts.
     */
    public function getCodTrans(): string
    {
        return $this->_getString('codTrans', true);
    }

    /**
     * Transaction date.
     * Format: YYYYMMDD
     */
    public function getData(): string
    {
        return $this->_getString('data', true);
    }

    /**
     * Transaction time.
     * Format: HHMMSS
     */
    public function getOrario(): string
    {
        return $this->_getString('orario', true);
    }

    /**
     * Masked credit card number.
     * Only the first 6 and last 4 digits are clear.
     */
    public function getPan(): ?string
    {
        return $this->_getString('pan');
    }

    /**
     * Credit card expiration.
     * Format: YYYYMM
     */
    public function getScadenza_pan(): ?string
    {
        return $this->_getString('scadenza_pan');
    }

    /**
     * Type of card used by the user to make the payment.
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificatipocarta.html
     */
    public function getBrand(): string
    {
        return $this->_getString('brand', true);
    }

    /**
     * The nationality of the card that made the payment.
     */
    public function getNazionalita(): ?string
    {
        return $this->_getString('nazionalita');
    }

    /**
     * Language identifier that will be displayed on the checkout page.
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificalanguageid.html
     */
    public function getLanguageId(): ?string
    {
        return $this->_getString('languageId');
    }

    /**
     * Transaction type: the method with which the payment was made.
     * In case of unsuccessful payment, an empty string will be sent.
     *
     * @link https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificatipotransazione.html
     */
    public function getTipoTransazione(): ?string
    {
        return $this->_getString('tipoTransazione');
    }

    /**
     * If enabled, the macro-region to which the card used for payment belongs is returned (e.g.: Europe).
     */
    public function getRegione(): ?string
    {
        return $this->_getString('regione');
    }

    /**
     * Field specified by the merchant tha may describe the type of service offered.
     * For the MyBank service the field is sent to the bank to be included in the description of the SCT provision but is truncated at the 140th character.
     * With PayPal the past value will be made available in the payment detail available on the PayPal account.
     */
    public function getDescrizione(): ?string
    {
        return $this->_getString('descrizione');
    }

    /**
     * If enabled, the description of the card type used for payment is returned.
     * Composition of the parameter:
     * product description – type of use (CREDIT/DEBIT) – prepaid (S/N)
     * @example 'VISA CLASSIC - CREDIT – N'
     */
    public function getTipoProdotto(): ?string
    {
        return $this->_getString('tipoProdotto');
    }

    /**
     * Buyer's first name.
     */
    public function getNome(): ?string
    {
        return $this->_getString('nome');
    }

    /**
     * Buyer's last name.
     */
    public function getCognome(): ?string
    {
        return $this->_getString('cognome');
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
     * If required by the merchant's profile, this field contains the hash of the PAN of the card used for the payment.
     */
    public function getHash(): ?string
    {
        return $this->_getString('hash');
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
     * This information can be conveyed to the bank based on prior agreements with the bank itself.
     *
     * Maximum length: 20
     */
    public function getInfob(): ?string
    {
        return $this->_getString('infob');
    }

    /**
     * Merchant code assigned by the acquirer (if expected).
     */
    public function getCodiceConvenzione(): ?string
    {
        return $this->_getString('codiceConvenzione');
    }

    /**
     * Transaction identifier for the Bancomat Pay circuit.
     */
    public function getIdTransazioneBPay(): ?string
    {
        return $this->_getString('IdTransazioneBPay');
    }

    /**
     * Valued with 'Y': indicates that there are errors in the 3D Secure 2.2 parameters sent.
     * If the parameters are correct, this field is not returned.
     *
     * @see https://ecommerce.nexi.it/specifiche-tecniche/apibackoffice/warning.html
     */
    public function getEsito_informazioniSicurezza(): ?string
    {
        return $this->_getString('esito_informazioniSicurezza');
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     * @see \MLocati\Nexi\XPay\Entity\Response::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return array_merge(parent::getRequiredFields(), [
            'messaggio',
            'alias',
            'importo',
            'divisa',
            'codTrans',
            'data',
            'orario',
            'brand',
            'nazionalita',
            'languageId',
        ]);
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
            'esito' => $this->getEsito(),
            'importo' => $this->getImporto(),
            'divisa' => $this->getDivisa(),
            'data' => $this->getData(),
            'orario' => $this->getOrario(),
            'codAut' => $this->getCodAut(),
        ];
    }

}
