<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Asika\Autolink\Autolink;

class Renderer
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $templateVarDefaults = [];

    /**
     * @param string $text
     * @return string
     */
    private function autoReplaceLinks($text)
    {
        $options = [
            'strip_scheme'   => false,
            'text_limit'     => false,
            'auto_title'     => false,
            'escape'         => true,
            'link_no_scheme' => false
        ];

        $schemes  = ['http', 'https'];
        $autolink = new Autolink($options, $schemes);
        return $autolink->convert($text, ['rel' => 'noopener noreferrer', 'target' => '_blank', 'class' => 'autolink']);
    }

    public function __construct()
    {
        $loader = new FilesystemLoader(Utils::getRootDir() . '/tpl');
        $this->twig = new Environment($loader, [
            'cache' => '/temp',
            'debug' => DEBUG
        ]);

        $this->twig->addFilter(new TwigFilter('dump', function ($obj) {
            ob_start();
            dump($obj);

            return ob_get_clean();
        }));

        $this->twig->addFilter(new TwigFilter('autolinks', function ($text) {
            return $this->autoReplaceLinks($text);
        }));

        $this->twig->addFilter(new TwigFilter('decodeJSON', function ($str) {
            return json_decode($str, true);
        }));
    }

    /**
     * @param string $tpl
     * @param array $data
     *
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render($tpl, array $data)
    {
        $data = array_merge(Utils::getTemplateDefaultVars(), $data);

        return $this->twig->render($tpl, $data);
    }
}
