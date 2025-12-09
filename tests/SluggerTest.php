<?php declare(strict_types=1);

namespace resist\Slugger\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use resist\Slugger\Slugger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Slugger\AsciiSlugger;

final class SluggerTest extends TestCase
{
    #[DataProvider('getSlugTestCases')]
    public function testSlug(string|int|float $string, string $allowedChars, string $replacement, bool $lowercase, string $output): void
    {
        $cleaner = new Slugger(new AsciiSlugger());

        self::assertEquals($cleaner->customSlug($string, $allowedChars, $replacement, $lowercase), $output);
    }

    /**
     * @return array{string, string, string, bool, string} input, allowed, replacement, toLowercase, expected
     */
    public static function getSlugTestCases(): array
    {
        return [
            ['valid string', '', '-', true, 'valid-string'],
            ['valid string', ' ', '-', true, 'valid string'],
            ['@valid string?', ' @', '_', false, '@valid string_'],
            ['@ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', true, 'arvizturotukorfurogep'],
            ['ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP?', '', '-', true, 'arvizturotukorfurogep'],
            ['Á@RVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', true, 'a-rvizturotukorfurogep'],
            ['ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP', '', '-', false, 'ARVIZTUROTUKORFUROGEP'],
            ['@Á:RVÍZTŰRŐTÜKÖRFÚRÓGÉP', 'ÁÍŰŐÜÖÚÓÉ', '-', false, 'Á-RVÍZTŰRŐTÜKÖRFÚRÓGÉP'],
            [' Trimmed String With Special Character_ ', '', '-', true, 'trimmed-string-with-special-character'],
            [' Trimmed String With Special Characte?r ', '', '-', true, 'trimmed-string-with-special-characte-r'],
            ['Magyar szöveg speciális karakterek tiltásával és szóközökkel. Üzbég.', ' ÁÍŰŐÜÖÚÓÉáíűőüöúóé', '', false, 'Magyar szöveg speciális karakterek tiltásával és szóközökkel Üzbég'],
            ['[ABC] a.d-2 (mód.txt', ' ÁÍŰŐÜÖÚÓÉáíűőüöúóé._-', '', false, 'ABC a.d-2 mód.txt'],
        ];
    }
}
