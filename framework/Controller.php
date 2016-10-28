<?php
declare(strict_types=1);
namespace BarkaneArts\Framework;

use BarkaneArts\Framework\Reusable\Security;
use ParagonIE\Cookie\Session;
use ParagonIE\EasyDB\EasyDB;

/**
 * Class Controller
 * @package BarkaneArts\Framework
 */
class Controller
{
    use Security;

    /**
     * @var array
     */
    protected $modelCache = [];

    /**
     * @var \Twig_Environment
     */
    protected $twig = null;

    /**
     * @var EasyDB
     */
    protected $db = null;

    /**
     * @var string
     */
    protected $namespace = __NAMESPACE__;

    /**
     * Controller constructor.
     * @param \Twig_Environment|null $twig
     * @param EasyDB|null $db
     * @param Session $session
     * @param string $namespace
     */
    public function __construct(
        \Twig_Environment $twig = null,
        EasyDB $db = null,
        Session $session = null,
        string $namespace = ''
    ) {
        $this->twig = $twig;
        $this->db = $db;
        if (!$namespace) {
            // Figure the namespace out automatically:
            $ns = new \ReflectionClass(self::class);
            $namespace = $ns->getNamespaceName();
        }
        $this->namespace = $namespace;
    }

    /**
     * Load a Model object
     *
     * @param string $name     The name of the model to load.
     * @param bool $skipCache  Skip the Model cache? Not recommended.
     * @param mixed[] ...$args Additional constructor arguments
     * @return Model
     */
    protected function model(string $name, bool $skipCache = false, ...$args): Model
    {
        if (!$skipCache && isset($this->modelCache[$name])) {
            return $this->modelCache[$name];
        }
        if (\strpos($name, '\\') === false) {
            // Replace Controller with Model automagically:
            $pieces = \explode('\\', $this->namespace);
            $ending = \array_pop($pieces);
            if ($ending === 'Controller') {
                \array_push($ending, 'Model');
                $name = \implode('\\', $pieces) . '\\' . $name;
            }
        }

        /**
         * @var Model
         */
        $class = new $name($this->db, ...$args);
        return $class;
    }

    /**
     * Load/render a template.
     *
     * @param string $template
     * @param array $parameters
     * @param bool $dontExit
     */
    protected function view(string $template, array $parameters = [], bool $dontExit = false)
    {
        echo $this->twig->render($template . '.twig', $parameters);
        if (!$dontExit) {
            exit(0);
        }
    }

    /**
     * NOP. Overload this in inherited classes.
     */
    public function beforeRoute()
    {
        $this->traitSecuritySetup();
    }
}
