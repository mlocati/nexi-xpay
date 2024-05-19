<?php

declare(strict_types=1);

namespace MLocati\Nexi\XPay\Dictionary;

use ReflectionClass;

/**
 * List of ISO 639-2 codes of the languages supported by Nexi XPay.
 *
 * @see https://ecommerce.nexi.it/specifiche-tecniche/tabelleecodifiche/codificalanguageid.html
 */
class Language
{
    /**
     * Arab
     *
     * @var string
     */
    const ID_ARA = 'ARA';
    
    /**
     * Chinese
     *
     * @var string
     */
    const ID_CHI = 'CHI';
    
    /**
     * English
     *
     * @var string
     */
    const ID_ENG = 'ENG';

    /**
     * French
     *
     * @var string
     */
    const ID_FRA = 'FRA';
    
    /**
     * German
     *
     * @var string
     */
    const ID_GER = 'GER';
    
    /**
     * Italian
     *
     * @var string
     */
    const ID_ITA = 'ITA';

    /**
     * Japanese
     *
     * @var string
     */
    const ID_JPN = 'JPN';
    
    /**
     * Portuguese
     *
     * @var string
     */
    const ID_POR = 'POR';
    
    /**
     * Russian
     *
     * @var string
     */
    const ID_RUS = 'RUS';
    
    /**
     * Spanish
     *
     * @var string
     */
    const ID_SPA = 'SPA';

    /**
     * @private
     */
    const NEXI_TO_ALPHA2 = [
        self::ID_ARA => 'ar',
        self::ID_CHI => 'zh',
        self::ID_ENG => 'en',
        self::ID_FRA => 'fr',
        self::ID_GER => 'de',
        self::ID_ITA => 'it',
        self::ID_JPN => 'ja',
        self::ID_POR => 'pt',
        self::ID_RUS => 'ru',
        self::ID_SPA => 'es',
    ];

    /**
     * @private
     */
    const ALPHA2_TO_NEXI = [
        'ar' => self::ID_ARA,
        'zh' => self::ID_CHI,
        'en' => self::ID_ENG,
        'fr' => self::ID_FRA,
        'de' => self::ID_GER,
        'it' => self::ID_ITA,
        'ja' => self::ID_JPN,
        'pt' => self::ID_POR,
        'ru' => self::ID_RUS,
        'es' => self::ID_SPA,
    ];

    /**
     * @return string[]
     */
    public function getAvailableIDs(): array
    {
        $result = [];
        $class = new ReflectionClass($this);
        foreach ($class->getConstants() as $name => $value) {
            if (strpos($name, 'ID_') === 0 && is_string($value)) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Resolve a locale identifier (for example: 'it-IT@UTF-8') to a Nexi language identifier (for example 'ITA')
     *
     * @return string empty string if no corresponding Nexi language identifier has been found
     */
    public function getNexiCodeFromLocale(string $localeID): string
    {
        [$language] = preg_split('/\\W/', str_replace('_', '-', $localeID), 2);

        return $this->getNexiCodeFromIso639Alpha2($language);
    }

    /**
     * Resolve an ISO-639 alpha2 language identifier (for example: 'it') to a Nexi language identifier (for example 'ITA')
     *
     * @return string empty string if no corresponding Nexi language identifier has been found
     */
    public function getNexiCodeFromIso639Alpha2(string $alpha2LanguageID): string
    {
        $alpha2LanguageID = strtolower(trim($alpha2LanguageID));
        $map = array_change_key_case(static::ALPHA2_TO_NEXI, CASE_LOWER);
        if (isset($map[$alpha2LanguageID])) {
            return $map[$alpha2LanguageID];
        }
        $ids = $this->getAvailableIDs();
        $map = array_change_key_case(array_combine($ids, $ids), CASE_LOWER);
        if (isset($map[$alpha2LanguageID])) {
            return $map[$alpha2LanguageID];
        }

        return '';
    }
}
