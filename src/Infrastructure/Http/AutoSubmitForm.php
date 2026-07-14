<?php

declare(strict_types=1);

namespace Kowts\Sisp\Infrastructure\Http;

final class AutoSubmitForm
{
    /**
     * @param array<string,float|int|string> $fields
     */
    public static function render(string $action, array $fields, string $title = 'Redirecting to SISP'): string
    {
        $inputs = '';

        foreach ($fields as $name => $value) {
            $inputs .= sprintf(
                "<input type='hidden' name='%s' value='%s'>",
                self::escape((string) $name),
                self::escape((string) $value)
            );
        }

        return '<!DOCTYPE html>'
            . '<html><head>'
            . '<title>' . self::escape($title) . '</title>'
            . "<meta charset='utf-8'>"
            . '</head>'
            . "<body onload='document.forms[0].submit()'>"
            . "<form action='" . self::escape($action) . "' method='post'>"
            . $inputs
            . '</form>'
            . '<noscript><p>JavaScript is disabled. '
            . '<a href="#" onclick="document.forms[0].submit(); return false;">Click here</a> '
            . 'to continue.</p></noscript>'
            . '</body></html>';
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
