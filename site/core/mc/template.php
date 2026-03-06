<?php

namespace Mc;

/**
 * simple template filler
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class Template
{
    public const PREFIX = "PREFIX";
    public const SUFFIX = "SUFFIX";

    public const COMMENT_MODIFIERS = [
        self::PREFIX => "<!-- ",
        self::SUFFIX => " -->",
    ];
    public const BRACKET_MODIFIERS = [
        self::PREFIX => "{{",
        self::SUFFIX => "}}",
    ];

    public const CM = self::COMMENT_MODIFIERS;
    public const BR = self::BRACKET_MODIFIERS;

    /**
     * template
     * @var string
     */
    protected string $template;
    protected array $modifiers = [
        self::PREFIX => "",
        self::SUFFIX => ""
    ];

    /**
     *
     * @param string $template
     * @param array $modifiers
     */
    public function __construct(string $template, array $modifiers = [])
    {
        $this->template = $template;
        if(isset($modifiers[self::PREFIX])){
            $this->modifiers[self::PREFIX] = $modifiers[self::PREFIX];
        }
        if(isset($modifiers[self::SUFFIX])){
            $this->modifiers[self::SUFFIX] = $modifiers[self::SUFFIX];
        }
    }

    /**
     * Create new template object from file
     * @param string $file
     * @param array $modifiers
     * @return \Mc\Template
     */
    public static function Load(string $file, array $modifiers = []): Template
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new \Exception("File not found: " . $file);
        }
        $content = file_get_contents($file);
        if ($content === false) {
            throw new \Exception("File not readable: " . $file);
        }
        return new Template($content, $modifiers);
    }

    /**
     * set filler prefix
     * @param string $prefix
     */
    public function SetPrefix(string $prefix): void
    {
        $this->modifiers[self::PREFIX] = $prefix;
    }

    /**
     * set filler suffix
     * @param string $suffix
     */
    public function SetSuffix(string $suffix)
    {
        $this->modifiers[self::SUFFIX] = $suffix;
    }

    /**
     * $data is a simple list of pairs <i>$pattern</i> => <i>$value</i>
     * Method replace <i>$pattern</i> with <i>$value</i>
     * and return new template object
     * Example:
     * <pre>$template->Fill($data1)->Fill(data2)->Value();</pre>
     * @param array $data
     * @return \Mc\Template
     */
    public function Fill(array $data): Template
    {
        $html = $this->template;
        foreach ($data as $pattern => $value) {
            $pattern = $this->modifiers[self::PREFIX] . $pattern . $this->modifiers[self::SUFFIX];
            $html = str_replace($pattern, $value, $html);
        }
        return new Template($html);
    }

    /**
     * Replace single $pattern with $value
     * @param string $pattern
     * @param string $value
     * @return \Mc\Template
     */
    public function FillValue(string $pattern, string $value): Template
    {
        return  new Template(str_replace($pattern, $value, $this->template));
    }

    /**
     * returns template value
     * @return string
     */
    public function Value(): string
    {
        return $this->template;
    }
}
