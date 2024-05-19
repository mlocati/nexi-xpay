<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Entity;

use MLocati\Nexi\XPay\Entity;
use stdClass;

abstract class Response extends Entity
{
    const ESITO_OK = 'OK';

    const ESITO_KO = 'KO';

    const ESITO_CANCEL = 'ANNULLO';

    const ESITO_ERROR = 'ERRORE';

    const ESITO_PENDING = 'PEN';
    
    /**
     * @throws \MLocati\Nexi\XPay\Exception\MissingField
     * @throws \MLocati\Nexi\XPay\Exception\WrongFieldType
     * @throws \MLocati\Nexi\XPay\Exception\HttpError
     */
    public function __construct(stdClass $data)
    {
        if (is_string($data->timeStamp ?? null)) {
            if (is_numeric($data->timeStamp)) {
                $data->timeStamp = (int) $data->timeStamp;
            }
        }
        parent::__construct($data);
    }

    /**
     * Result of the operation.
     * Possible values: see the RESULT_... constants.
     */
    public function getEsito(): ?string
    {
        return $this->_getString('esito');
    }

    /**
     * {@inheritdoc}
     *
     * @see \MLocati\Nexi\XPay\Entity::getRequiredFields()
     */
    protected function getRequiredFields(): array
    {
        return [
            'esito',
        ];
    }
}
