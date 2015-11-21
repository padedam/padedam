<?php
/**
 * Created by PhpStorm.
 * User: renaldas
 * Date: 15.11.6
 * Time: 11.05
 */

namespace NFQ\AssistanceBundle\Twig;

use NFQ\AssistanceBundle\Service\AssistanceManager;

/**
 * Class AssitanceExtension
 * @package NFQ\AssistanceBundle\Twig
 */
class AssitanceExtension extends \Twig_Extension
{
    /**
     * @var AssistanceManager
     */
    private $manager;

    public function __construct(AssistanceManager $manager)
    {
        $this->manager = $manager;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'get_assistance_categories',
                [$this, 'getAssistanceCategories'],
                ['is_safe' => ['html']]
            )
        ];
    }

    /**
     * @return array|string
     */
    public function getAssistanceCategories()
    {
        return $this->manager->getCategoryTree();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assistance_extension';
    }
}