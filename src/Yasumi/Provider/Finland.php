<?php
/**
 *  This file is part of the Yasumi package.
 *
 *  Copyright (c) 2015 - 2016 AzuyaLabs
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 * @author Sacha Telgenhof <stelgenhof@gmail.com>
 */

namespace Yasumi\Provider;

use DateTime;
use DateTimeZone;
use Yasumi\Holiday;

/**
 * Provider for all holidays in Finland.
 */
class Finland extends AbstractProvider
{
    use CommonHolidays, ChristianHolidays;

    /**
     * Initialize holidays for Finland.
     */
    public function initialize()
    {
        $this->timezone = 'Europe/Helsinki';

        // Add common holidays
        $this->addHoliday($this->newYearsDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->internationalWorkersDay($this->year, $this->timezone, $this->locale));

        // Add common Christian holidays (common in Finland)
        $this->addHoliday($this->epiphany($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->goodFriday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->easter($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->easterMonday($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->ascensionDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->pentecost($this->year, $this->timezone, $this->locale));
        $this->calculatestJohnsDay(); // aka Midsummer's Day
        $this->addHoliday($this->allSaintsDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->christmasDay($this->year, $this->timezone, $this->locale));
        $this->addHoliday($this->secondChristmasDay($this->year, $this->timezone, $this->locale));

        // Calculate other holidays
        $this->calculateIndependenceDay();
    }

    /**
     * St. John's Day / Midsummer.
     *
     * Midsummer, also known as St John's Day, is the period of time centred upon the summer solstice, and more
     * specifically the Northern European celebrations that accompany the actual solstice or take place on a day
     * between June 19 and June 25 and the preceding evening. The exact dates vary between different cultures.
     * The Christian Church designated June 24 as the feast day of the early Christian martyr St John the Baptist, and
     * the observance of St John's Day begins the evening before, known as St John's Eve.
     *
     * In Finland since 1955, the holiday has always been on a Saturday (between June 20 and June 26). Earlier it was
     * always on June 24. Many of the celebrations of midsummer take place on midsummer eve, when many workplaces are
     * closed and shops must close their doors at noon.
     *
     * @link https://en.wikipedia.org/wiki/Midsummer#Finland
     */
    public function calculatestJohnsDay()
    {
        $translation = ['fi_FI' => 'Juhannuspäivä'];
        $shortName   = 'stJohnsDay';
        $date        = new DateTime("$this->year-6-24", new DateTimeZone($this->timezone)); // Default date

        if ($this->year < 1955) {
            $this->addHoliday(new Holiday($shortName, $translation, $date, $this->locale));
        } else {

            // Check between the 20th and 26th day which one is a Saturday
            for ($d = 20; $d <= 26; ++$d) {
                $date->setDate($this->year, 6, $d);
                if ($date->format('l') === 'Saturday') {
                    break;
                }
            }

            $this->addHoliday(new Holiday($shortName, $translation, $date, $this->locale));
        }
    }

    /*
     * Independence Day
     *
     * Finland's Independence Day (Finnish: itsenäisyyspäivä, Swedish: självständighetsdagen) is a national public
     * holiday, and a flag day, held on 6 December to celebrate Finland's declaration of independence from the Russian
     * Republic in 1917.
     *
     * Independence Day was first celebrated in 1917. However, during the first years of independence, 6 December in
     * some parts of Finland was only a minor holiday compared to 16 May, the Whites' day of celebration for prevailing
     * in the Finnish Civil War.
     *
     * @link https://en.wikipedia.org/wiki/Independence_Day_(Finland)
     */
    public function calculateIndependenceDay()
    {
        if ($this->year >= 1917) {
            $this->addHoliday(new Holiday('independenceDay', ['fi_FI' => 'Itsenäisyyspäivä'],
                new DateTime("$this->year-12-6", new DateTimeZone($this->timezone)), $this->locale));
        }
    }
}
