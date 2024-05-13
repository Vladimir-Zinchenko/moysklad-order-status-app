<?php

namespace App\Component;

use App\Util\AppHelper;

/**
 * Class TemplateRenderer
 */
class TemplateRenderer
{
    protected array $data;

    protected string $template;

    /**
     * @param string $template
     *
     * @param array  $data
     */
    public function __construct(string $template, array $data = [])
    {
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * @param string $template
     * @param array  $data
     *
     * @return TemplateRenderer
     */
    public static function factory(string $template, array $data = []): TemplateRenderer
    {
        return new self($template, $data);
    }

    /**
     * @param array $data
     *
     * @return TemplateRenderer
     */
    public function setVars(array $data): TemplateRenderer
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return TemplateRenderer
     */
    public function setVar(string $key, $value): TemplateRenderer
    {
        $this->data['$key'] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return TemplateRenderer
     */
    public function unsetVar(string $key): TemplateRenderer
    {
        if (isset($this->data[$key])) {
            unset($this->data['$key']);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        extract($this->data);

        ob_start();

        require AppHelper::templatePath($this->template . '.php');

        $result = ob_get_contents();

        ob_clean();

        return $result;
    }
}
