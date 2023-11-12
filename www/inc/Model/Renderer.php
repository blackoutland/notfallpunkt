<?php

namespace BlackoutLand\NotfallPunkt\Model;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;
use Asika\Autolink\Autolink;
use Twig\TwigFunction;
use voku\helper\HtmlMin;
use MatthiasMullie\Minify;

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
        $loader     = new FilesystemLoader(Utils::getRootDir() . '/tpl');
        $this->twig = new Environment($loader, [
            'cache' => '/temp',
            'debug' => DEBUG
        ]);

        $this->twig->addFilter(new TwigFilter('dump', function ($obj) {
            ob_start();
            dump($obj);

            return ob_get_clean();
        }));

        $this->twig->addFilter(new TwigFilter('preg_replace', function ($subject, $pattern, $replacement) {
            return preg_replace($pattern, $replacement, $subject);
        }));

        $this->twig->addFilter(new TwigFilter('autolinks', function ($text) {
            return $this->autoReplaceLinks($text);
        }));

        $this->twig->addFilter(new TwigFilter('addslashes', function ($text) {
            return addslashes($text);
        }));

        $this->twig->addFilter(new TwigFilter('decodeJSON', function ($str) {
            return json_decode($str, true);
        }));

        // @see https://packagist.org/packages/matthiasmullie/minify
        $function = new TwigFunction('minifyCss', function ($cssFiles) {
            $hashes = [];
            foreach ($cssFiles as $cssFile) {
                $hashes[] = md5_file(Utils::getHtdocsDir() . '/' . $cssFile);
            }
            $hash      = md5(implode('|', $hashes));
            $cacheFile = Utils::getHtdocsDir() . '/minified/' . $hash . '.css';

            if (!file_exists($cacheFile)) {
                $minifier = new Minify\CSS();
                foreach ($cssFiles as $cssFile) {
                    $minifier->add(Utils::getHtdocsDir() . '/' . $cssFile);
                }
                $hash = md5(implode('|', $hashes));
                $minifier->minify($cacheFile);
            }

            return '<link rel="stylesheet" href="/minified/' . $hash . '.css">';
        });
        $this->twig->addFunction($function);

        // @see https://packagist.org/packages/matthiasmullie/minify
        $function2 = new TwigFunction('minifyJs', function ($jsFiles) {
            $concatenatedFiles = [];
            $hashes            = [];
            foreach ($jsFiles as $jsFile) {
                if (preg_match("/^@(.*)/", $jsFile, $m)) {
                    $jsFile = $m[1];
                }
                $hashes[] = md5_file(Utils::getHtdocsDir() . '/' . $jsFile);
            }
            $hash      = md5(implode('|', $hashes));
            $cacheFile = Utils::getHtdocsDir() . '/minified/' . $hash . '.js';

            if (!file_exists($cacheFile)) {
                $minifier = new Minify\JS();
                foreach ($jsFiles as $jsFile) {
                    if (preg_match("/^@(.*)/", $jsFile, $m)) {
                        $concatenatedFiles[] = Utils::getHtdocsDir() . '/' . $m[1];
                    } else {
                        $minifier->add(Utils::getHtdocsDir() . '/' . $jsFile);
                    }
                }
                $hash = md5(implode('|', $hashes));
                $minifier->minify($cacheFile);
                if ($concatenatedFiles) {
                    foreach ($concatenatedFiles as $file) {
                        file_put_contents($cacheFile, "\n" . file_get_contents($file), FILE_APPEND);
                    }
                }
            }

            return '<script type="text/javascript" src="/minified/' . $hash . '.js">';
        });
        $this->twig->addFunction($function2);

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

        $html = $this->twig->render($tpl, $data);

        $settings = Utils::getSettings();
        if ($settings['ext_minify_html']) {
            // Minify HTML @see https://github.com/voku/HtmlMin
            $htmlMin = new HtmlMin();
            $htmlMin->doOptimizeViaHtmlDomParser();               // optimize html via "HtmlDomParser()"
            $htmlMin->doRemoveComments();                         // remove default HTML comments (depends on "doOptimizeViaHtmlDomParser(true)")
            $htmlMin->doSumUpWhitespace();                        // sum-up extra whitespace from the Dom (depends on "doOptimizeViaHtmlDomParser(true)")
            $htmlMin->doRemoveWhitespaceAroundTags();             // remove whitespace around tags (depends on "doOptimizeViaHtmlDomParser(true)")
            $htmlMin->doOptimizeAttributes();                     // optimize html attributes (depends on "doOptimizeViaHtmlDomParser(true)")
            $htmlMin->doRemoveHttpPrefixFromAttributes();         // remove optional "http:"-prefix from attributes (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveHttpsPrefixFromAttributes();        // remove optional "https:"-prefix from attributes (depends on "doOptimizeAttributes(true)")
            $htmlMin->doKeepHttpAndHttpsPrefixOnExternalAttributes(); // keep "http:"- and "https:"-prefix for all external links
            $htmlMin->doMakeSameDomainsLinksRelative(['example.com']); // make some links relative, by removing the domain from attributes
            $htmlMin->doRemoveDefaultAttributes();                // remove defaults (depends on "doOptimizeAttributes(true)" | disabled by default)
            $htmlMin->doRemoveDeprecatedAnchorName();             // remove deprecated anchor-jump (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveDeprecatedScriptCharsetAttribute(); // remove deprecated charset-attribute - the browser will use the charset from the HTTP-Header, anyway (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveDeprecatedTypeFromScriptTag();      // remove deprecated script-mime-types (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveDeprecatedTypeFromStylesheetLink(); // remove "type=text/css" for css links (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveDeprecatedTypeFromStyleAndLinkTag(); // remove "type=text/css" from all links and styles
            $htmlMin->doRemoveDefaultMediaTypeFromStyleAndLinkTag(); // remove "media="all" from all links and styles
            $htmlMin->doRemoveDefaultTypeFromButton();            // remove type="submit" from button tags
            $htmlMin->doRemoveEmptyAttributes();                  // remove some empty attributes (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveValueFromEmptyInput();              // remove 'value=""' from empty <input> (depends on "doOptimizeAttributes(true)")
            $htmlMin->doSortCssClassNames();                      // sort css-class-names, for better gzip results (depends on "doOptimizeAttributes(true)")
            $htmlMin->doSortHtmlAttributes();                     // sort html-attributes, for better gzip results (depends on "doOptimizeAttributes(true)")
            $htmlMin->doRemoveSpacesBetweenTags();                // remove more (aggressive) spaces in the dom (disabled by default)
            $htmlMin->doRemoveOmittedQuotes();                    // remove quotes e.g. class="lall" => class=lall
            $htmlMin->doRemoveOmittedHtmlTags();                  // remove ommitted html tags e.g. <p>lall</p> => <p>lall
            $html = $htmlMin->minify($html);
        }

        return $html;
    }
}
