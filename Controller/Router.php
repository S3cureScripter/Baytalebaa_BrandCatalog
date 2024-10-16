<?php
declare(strict_types=1);
namespace Baytalebaa\Shops\Controller;


use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
use Baytalebaa\Shops\Model\SubcatalogsFactory;
use \Psr\Log\LoggerInterface;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $subcatalogsFactory;
    protected $logger;

    public function __construct(
        ActionFactory $actionFactory, 
        SubcatalogsFactory $subcatalogsFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->actionFactory = $actionFactory;
        $this->subcatalogsFactory = $subcatalogsFactory;
        $this->logger = $logger;
    }

    public function match(RequestInterface $request)
    {
        try {
            $this->logger->debug('Custom router is invoked');

            // Get the current request details
            $moduleName = $request->getModuleName();
            $controllerName = $request->getControllerName();
            $actionName = $request->getActionName();

            // Avoid infinite loop: If the request is already targeting 'shops', don't remap it
            if ($moduleName === 'shops') {
                return null;
            }

            // Get the requested URL path and break it down
            $identifier = trim($request->getPathInfo(), '/');
            $identifierParts = explode('/', $identifier);

            // If the URL starts with 'shops', process it
            if (count($identifierParts) > 1 && $identifierParts[0] === 'shops') {
                $shopSlug = $identifierParts[1] ?? null;
                $catalogSlug = $identifierParts[2] ?? null;
                $subcatalogSlug = $identifierParts[3] ?? null;

                if ($shopSlug) {
                    // Load the shop using the slug (assuming 'url_slug' is correct)
                    $shop = $this->subcatalogsFactory->create()->load($shopSlug, 'url_slug');

                    if ($shop->getId()) {
                        // Handle different URL formats (with or without catalog and subcatalog)
                        if ($catalogSlug) {
                            if ($subcatalogSlug) {
                                // Handle /shops/<shop_slug>/<catalog_slug>/<subcatalog_slug>
                                $request->setModuleName('shops')
                                    ->setControllerName('index')
                                    ->setActionName('subcatalog')
                                    ->setParam('shop', $shop->getId())
                                    ->setParam('catalog', $catalogSlug)
                                    ->setParam('subcatalog', $subcatalogSlug);
                            } else {
                                // Handle /shops/<shop_slug>/<catalog_slug>
                                $request->setModuleName('shops')
                                    ->setControllerName('index')
                                    ->setActionName('view')
                                    ->setParam('shop', $shop->getId())
                                    ->setParam('catalog', $catalogSlug);
                            }
                        } else {
                            // Handle /shops/<shop_slug>
                            $request->setModuleName('shops')
                                ->setControllerName('index')
                                ->setActionName('index')
                                ->setParam('shop', $shop->getId());
                        }

                        // Forward the request to the correct controller/action
                        return $this->actionFactory->create(Forward::class);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Error in custom router: ' . $e->getMessage());
        }

        return null;
    }
}